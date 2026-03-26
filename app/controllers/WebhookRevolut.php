<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Date;
use Altum\Models\Payments;
use Altum\PaymentGateways\Revolut;

defined('ALTUMCODE') || die();

class WebhookRevolut extends Controller {

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

        $data = json_decode($payload);

        /* Validate basic payload structure */
        if(!$data || empty($data->event)) {
            http_response_code(400);
            die('Invalid payload');
        }

        /* Make sure the event is the expected one */
        if($data->event !== 'ORDER_COMPLETED') {
            echo 'Ignored - status not completed';
            die();
        }

        /* Extract */
        $external_payment_id = $data->order_id;

        try {
            $response = \Unirest\Request::get(
                Revolut::get_api_url() . 'api/orders/' . $external_payment_id,
                [
                    'Authorization' => 'Bearer ' . settings()->revolut->secret_key,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Revolut-Api-Version' => '2024-09-01',
                ],
            );
        } catch (\Exception $exception) {
            http_response_code(400);
            echo 'Invalid response: ' . json_encode($exception->getMessage());
            die();
        }

        if($response->code >= 400) {
            http_response_code(400);
            echo 'Invalid response: ' . $response->code . ': ' . $response->raw_body;
            die();
        }

        /* Get data from the response */
        if($response->body->state != 'completed') {
            die('Payment is not completed');
        }

        /* Start getting the payment details */
        $payment_subscription_id = null;
        $external_payment_id = $response->body->id;
        $payment_currency = $response->body->currency;
        $payment_total = in_array($payment_currency, get_zero_decimal_currencies_array()) ? $response->body->amount : $response->body->amount / 100;
        $payment_type = 'one_time';

        /* Payment payer details */
        $payer_email = $response->body->customer->email ?? '';
        $payer_name = $response->body->customer->full_name ?? '';

        /* Process meta data */
        $metadata = $response->body->metadata;
        $user_id = (int) $metadata->user_id;
        $plan_id = (int) $metadata->plan_id;
        $payment_frequency = $metadata->payment_frequency;
        $code = isset($metadata->code) ? $metadata->code : '';
        $discount_amount = isset($metadata->discount_amount) ? $metadata->discount_amount : 0;
        $base_amount = isset($metadata->base_amount) ? $metadata->base_amount : 0;
        $taxes_ids = isset($metadata->taxes_ids) ? $metadata->taxes_ids : null;

        /* Process payment */
        (new Payments())->webhook_process_payment(
            'revolut',
            $external_payment_id,
            $payment_total,
            $payment_currency,
            $user_id,
            $plan_id,
            $payment_frequency,
            $code,
            $discount_amount,
            $base_amount,
            $taxes_ids,
            $payment_type,
            $payment_subscription_id,
            $payer_email,
            $payer_name
        );

        /* Reply OK */
        echo 'successful';
    }
}
