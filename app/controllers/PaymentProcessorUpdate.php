<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
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

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class PaymentProcessorUpdate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.payment_processors')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('payment-processors');
        }

        $payment_processor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$payment_processor = db()->where('payment_processor_id', $payment_processor_id)->where('user_id', $this->user->user_id)->getOne('payment_processors')) {
            redirect('payment-processors');
        }
        $payment_processor->settings = json_decode($payment_processor->settings ?? '');

        if(!empty($_POST)) {
            $settings = [];

            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['processor'] = isset($_POST['processor']) && in_array($_POST['processor'], ['paypal', 'stripe', 'crypto_com', 'razorpay', 'paystack', 'mollie']) ? query_clean($_POST['processor']) : 'https://';

            switch($_POST['processor']) {
                case 'paypal':
                    $settings['mode'] = $_POST['mode'] = in_array($_POST['mode'], ['live', 'sandbox']) ? $_POST['mode'] : 'live';
                    $settings['client_id'] = $_POST['client_id'] = input_clean($_POST['client_id']);
                    $settings['secret'] = $_POST['secret'] = input_clean($_POST['secret']);
                    break;

                case 'stripe':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;

                case 'crypto_com':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;

                case 'paystack':
                    $settings['public_key'] = $_POST['public_key'] = input_clean($_POST['public_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    break;

                case 'razorpay':
                    $settings['key_id'] = $_POST['key_id'] = input_clean($_POST['key_id']);
                    $settings['key_secret'] = $_POST['key_secret'] = input_clean($_POST['key_secret']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;

                case 'mollie':
                    $settings['api_key'] = $_POST['api_key'] = input_clean($_POST['api_key']);
                    break;
            }

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = json_encode($settings);

                /* Database query */
                db()->where('payment_processor_id', $payment_processor->payment_processor_id)->update('payment_processors', [
                    'name' => $_POST['name'],
                    'processor' => $_POST['processor'],
                    'settings' => $settings,
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItemsByTag('payment_processors?user_id=' . $this->user->user_id);

                redirect('payment-processor-update/' . $payment_processor_id);
            }
        }

        /* Prepare the view */
        $data = [
            'payment_processor' => $payment_processor,
        ];

        $view = new \Altum\View('payment-processor-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
