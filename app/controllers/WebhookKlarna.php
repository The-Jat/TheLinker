<?php

namespace Altum\Controllers;

use Altum\Date;
use Altum\Models\Payments;

defined('ALTUMCODE') || die();

class WebhookKlarna extends Controller {

	public function index() {

        /* Make sure no cache is being used on the endpoint */
		header('Cache-Control: no-store');

        if(!in_array(settings()->license->type, ['Extended License', 'extended'])) {
            throw_404();
        }

        if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
            throw_404();
        }

        /* Get the headers */
        $headers = getallheaders();

        /* Get the payload */
        $payload = trim(@file_get_contents('php://input'));

        /* Log for debugging purposes */
        debug_log('[' . \Altum\Router::$controller . '] ' . print_r(['headers' => $headers, 'payload' => $payload], true));

		/* Klarna sometimes sends empty payloads when a checkout session is created */
		if(empty($payload)) {
			echo 'empty webhook';
			die();
		}

		$data = json_decode($payload);

		/* Validate basic payload structure */
		if(!$data || empty($data->session) || empty($data->session->session_id)) {
			http_response_code(400);
			echo 'Invalid Klarna payload';
			die();
		}

		/* Extract session info */
		$session_id = $data->session->session_id;
		$status = strtoupper($data->session->status ?? '');
		$authorization_token = $data->session->authorization_token ?? null;

		/* Only process COMPLETED sessions */
		if($status !== 'COMPLETED') {
			echo 'Ignored - status not completed';
			die();
		}

		if(!$authorization_token) {
			http_response_code(400);
			echo 'Missing authorization token';
			die();
		}

		/* details about the payment */
		$payment = db()->where('payment_id', $session_id)->where('status', 'pending')->getOne('payments');

		if(!$payment) {
			http_response_code(400); die();
		}

		$payment->plan = json_decode($payment->plan ?? '');

		/* details about the user who paid */
		$user = db()->where('user_id', $payment->user_id)->getOne('users');

		/* plan that the user has paid for */
		$plan = (new \Altum\Models\Plan())->get_plan_by_id($payment->plan_id);

		/* Klarna credentials */
		$klarna_username = settings()->klarna->username;
		$klarna_password = settings()->klarna->password;
		$klarna_base_url = settings()->klarna->mode;

		/* Finalize Klarna order with auto_capture */
		$create_order_url = $klarna_base_url . '/payments/v1/authorizations/' . urlencode($authorization_token) . '/order';

		/* Build the payload */
		$finalize_payload = [
			'auto_capture' => true,
			'purchase_country' => $user->country,
			'purchase_currency' => $payment->currency,
			'order_amount' => $payment->total_amount * 100,
			'order_tax_amount' => 0,
			'order_lines' => [
				[
					'type' => 'digital',
					'reference' => (string) $plan->plan_id,
					'name' => settings()->business->brand_name . ' - ' . $plan->name,
					'quantity' => 1,
					'unit_price' => $payment->total_amount * 100,
					'total_amount' => $payment->total_amount * 100,
					'tax_rate' => 0,
					'total_tax_amount' => 0
				]
			]
		];

		try {
			$create_response = \Unirest\Request::post(
				$create_order_url,
				[
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic ' . base64_encode($klarna_username . ':' . $klarna_password)
				],
				json_encode($finalize_payload)
			);
		} catch(\Exception $exception) {
			http_response_code(400);
			echo 'Klarna order creation failed: ' . $exception->getMessage();
			die();
		}

		/* Validate Klarna response */
		if($create_response->code >= 400 || empty($create_response->body->order_id)) {
			http_response_code(400);
			echo 'Invalid Klarna create order response: ' . json_encode($create_response->body);
			die();
		}

		$klarna_order = $create_response->body;

		/* Make sure fraud check passed */
		if(isset($klarna_order->fraud_status) && $klarna_order->fraud_status !== 'ACCEPTED') {
			echo 'Fraud check not accepted, manual review required';
			die();
		}

		/* Make sure the code that was potentially used exists */
		$codes_code = db()->where('code', $payment->code)->where('type', 'discount')->getOne('codes');

		if($codes_code) {
			/* Check if we should insert the usage of the code or not */
			if(!db()->where('user_id', $payment->user_id)->where('code_id', $codes_code->code_id)->has('redeemed_codes')) {

				/* Update the code usage */
				db()->where('code_id', $codes_code->code_id)->update('codes', ['redeemed' => db()->inc()]);

				/* Add log for the redeemed code */
				db()->insert('redeemed_codes', [
					'code_id'   => $codes_code->code_id,
					'user_id'   => $user->user_id,
					'datetime'  => get_date()
				]);
			}
		}

		/* Give the plan to the user */
		$current_plan_expiration_date = $payment->plan_id == $user->plan_id ? $user->plan_expiration_date : '';
		$modifier = match ($payment->frequency) {
			'monthly' => '+30 days +12 hours',
			'quarterly' => '+3 months +12 hours',
			'biannual' => '+6 months +12 hours',
			'annual' => '+12 months +12 hours',
			'lifetime' => '+100 years +12 hours',
		};
		$plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify($modifier)->format('Y-m-d H:i:s');

		/* Database query */
		db()->where('user_id', $user->user_id)->update('users', [
			'plan_id' => $payment->plan_id,
			'plan_settings' => json_encode($plan->settings),
			'plan_expiration_date' => $plan_expiration_date,
			'plan_expiry_reminder' => 0,
			'payment_processor' => 'klarna',
			'payment_total_amount' => $payment->total_amount,
			'payment_currency' => $payment->currency,
		]);

		/* Clear the cache */
		cache()->deleteItemsByTag('user_id=' . $user->user_id);

		/* Send notification to the user */
		$email_template = get_email_template(
			[],
			l('global.emails.user_payment.subject'),
			[
                '{{PAYMENT_ID}}' => $payment->id,
				'{{NAME}}' => $user->name,
				'{{PLAN_NAME}}' => $plan->name,
				'{{PLAN_EXPIRATION_DATE}}' => Date::get($plan_expiration_date, 2),
				'{{USER_PLAN_LINK}}' => url('account-plan'),
				'{{USER_PAYMENTS_LINK}}' => url('account-payments'),
			],
			l('global.emails.user_payment.body')
		);

		send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

		/* Currency exchange in case its needed */
		$total_amount_default_currency = $payment->total_amount;

		if(settings()->payment->default_currency != $payment->currency && settings()->payment->currency_exchange_api_key) {
			try {
				$response = \Unirest\Request::get('https://api.freecurrencyapi.com/v1/latest?apikey=' . settings()->payment->currency_exchange_api_key . '&base_currency=' . $payment->currency . '&currencies=' . settings()->payment->default_currency);

				if($response->code == 200) {
					$total_amount_default_currency = $payment->total_amount * $response->body->data->{settings()->payment->default_currency};
					$total_amount_default_currency = number_format($total_amount_default_currency, 2, '.', '');
				}
			} catch (\Exception $exception) {
				/* :) */
			}
		}

		/* Update the payment */
		db()->where('id', $payment->id)->update('payments', [
			'payment_id' => $klarna_order->order_id,
			'total_amount_default_currency' => $total_amount_default_currency,
			'status' => 'paid',
		]);

		/* Affiliate */
		(new Payments())->affiliate_payment_check($payment->id, $total_amount_default_currency, settings()->payment->default_currency, $user);

		/* Reply OK to Klarna */
		echo 'successful';
	}
}
