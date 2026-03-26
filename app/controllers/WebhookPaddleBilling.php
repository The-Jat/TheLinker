<?php
/*
 * Copyright (c) 2026 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\controllers;

use Altum\Models\Payments;

defined('ALTUMCODE') || die();

class WebhookPaddleBilling extends Controller {

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

        /* Get signature header */
        $signature_header = $_SERVER['HTTP_PADDLE_SIGNATURE'] ?? null;

        if(!$signature_header) {
            http_response_code(400);
            die('Missing Paddle signature');
        }

        /* Parse signature header */
        $signature_segments = explode(';', $signature_header);

        if(count($signature_segments) < 2) {
            http_response_code(400);
            die('Invalid signature format');
        }

        $time_parameter = explode('=', $signature_segments[0]);
        $signature_parameter = explode('=', $signature_segments[1]);

        if(!isset($time_parameter[1]) || !isset($signature_parameter[1])) {
            http_response_code(400);
            die('Invalid signature parameters');
        }

        $timestamp = trim($time_parameter[1]);
        $received_signature = trim($signature_parameter[1]);

        /* Build signed payload */
        $signed_payload = $timestamp . ':' . $payload;

        /* Compute HMAC SHA256 signature */
        $computed_signature = hash_hmac('sha256', $signed_payload, settings()->paddle_billing->secret_key);

        /* Validate */
        if(!hash_equals($computed_signature, $received_signature)) {
            http_response_code(400);
            die('Invalid Paddle signature');
        }

        /* Decode JSON */
        $event = json_decode($payload);

        if(!$event || !isset($event->event_type)) {
            http_response_code(400);
            die('Invalid Paddle event');
        }

        /* Allow only relevant events */
        $allowed_events = [
            'subscription.created',
            'subscription.updated',
            'transaction.paid'
        ];

        if(!in_array($event->event_type, $allowed_events)) {
            die('Event not needed, returning ok.');
        }

        $data = $event->data->object ?? $event->data;
        $external_payment_id = $data->id ?? null;

        /* Setup defaults */
        $payer_email = null;
        $payer_name = null;

        /* Event-based logic */
        switch($event->event_type) {

            /* Trial or subscription started */
            case 'subscription.created':
                if($data->status == 'canceled' || !isset($data->transaction_id)) {
                    die();
                }

                $payment_currency = mb_strtoupper($data->currency_code);
                $payment_total = in_array($payment_currency, get_zero_decimal_currencies_array()) ? $data->items[0]->price->unit_price->amount : $data->items[0]->price->unit_price->amount / 100;

                $metadata = $data->items[0]->price->custom_data ?? null;

                $user_id = (int) $metadata->user_id;
                $plan_id = (int) $metadata->plan_id;
                $payment_frequency = $metadata->payment_frequency;
                $code = $metadata->code ?? '';
                $discount_amount = $metadata->discount_amount ?? 0;
                $base_amount = $metadata->base_amount ?? 0;
                $taxes_ids = $metadata->taxes_ids ?? null;
                $payment_type = 'recurring';
                $payment_subscription_id = $data->id;
                $external_payment_id = $data->transaction_id;

                /* Record payment */
                (new Payments())->webhook_process_payment(
                    'paddle_billing',
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

                break;

            /* Recurring or one-time payment succeeded */
            case 'transaction.paid':
                $payment_currency = mb_strtoupper($data->details->totals->currency_code);
                $payment_total = in_array($payment_currency, get_zero_decimal_currencies_array()) ? $data->details->totals->total : $data->details->totals->total / 100;

                $metadata = $data->items[0]->price->custom_data ?? null;

                $user_id = (int) $metadata->user_id;
                $plan_id = (int) $metadata->plan_id;
                $payment_frequency = $metadata->payment_frequency;
                $code = $metadata->code ?? '';
                $discount_amount = $metadata->discount_amount ?? 0;
                $base_amount = $metadata->base_amount ?? 0;
                $taxes_ids = $metadata->taxes_ids ?? null;

                $payment_subscription_id = $data->subscription_id ?? null;
                $payment_type = $metadata->payment_type;

                if($payment_type == 'one_time' || $payment_subscription_id) {
                    /* Record payment */
                    (new Payments())->webhook_process_payment(
                        'paddle_billing',
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
                }

                break;
        }

        echo 'successful';
    }

}
