<?php

namespace Altum\Controllers;

use Altum\Models\Payments;
use Altum\PaymentGateways\Plisio;

defined('ALTUMCODE') || die();

class WebhookPlisioWhitelabel extends Controller {

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

		/* No empty webhooks */
		if(empty($_POST)) {
			die();
		}

        if(!Plisio::validate_hash(settings()->plisio->secret_key)) {
            die('Invalid request');
        }

        if($_POST['status'] != 'completed') {
            die('Invalid event');
        }

		/* Extract */
		$external_payment_id = trim($_POST['txn_id']);

        /* Start getting the payment details */
        $payment_total = (float) $_POST['source_amount'];
        $payment_currency = $_POST['source_currency'];
        $payment_type = 'one_time';
        $payment_subscription_id = '';

        /* Payment payer details */
        $payer_email = '';
        $payer_name = '';

        /* Process meta data */
        $metadata = explode('&', $_POST['order_name']);
        $user_id = (int) $metadata[0];
        $plan_id = (int) $metadata[1];
        $payment_frequency = $metadata[2];
        $base_amount = $metadata[3];
        $code = $metadata[4];
        $discount_amount = $metadata[5] ? $metadata[5] : 0;
        $taxes_ids = $metadata[6] ?: null;

        /* Process payment */
        (new Payments())->webhook_process_payment(
            'plisio_whitelabel',
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
