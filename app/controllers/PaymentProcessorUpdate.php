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

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class PaymentProcessorUpdate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            throw_404();
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.payment_processors')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('payment-processors');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payment_processors` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->payment_processors_limit != -1 && $total_rows > $this->user->plan_settings->payment_processors_limit) {
            redirect('payment-processors');
        }

        $payment_processor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$payment_processor = db()->where('payment_processor_id', $payment_processor_id)->where('user_id', $this->user->user_id)->getOne('payment_processors')) {
            redirect('payment-processors');
        }
        $payment_processor->settings = json_decode($payment_processor->settings ?? '');

        if(!empty($_POST)) {
            $settings = [];

            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['processor'] = isset($_POST['processor']) && in_array($_POST['processor'], include \Altum\Plugin::get('payment-blocks')->path . 'payment_blocks_payment_processors.php') ? query_clean($_POST['processor']) : 'https://';

            switch($_POST['processor']) {
                case 'paypal':
                    $settings['mode'] = $_POST['mode'] = in_array($_POST['mode'], ['live', 'sandbox']) ? $_POST['mode'] : 'live';
                    $settings['client_id'] = $_POST['client_id'] = input_clean($_POST['client_id'], 512);
                    $settings['secret'] = $_POST['secret'] = input_clean($_POST['secret'], 512);
                    break;

                case 'stripe':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key'], 512);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key'], 512);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret'], 512);
                    break;

                case 'crypto_com':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key'], 512);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key'], 512);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret'], 512);
                    break;

                case 'paystack':
                    $settings['public_key'] = $_POST['public_key'] = input_clean($_POST['public_key'], 512);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key'], 512);
                    break;

                case 'razorpay':
                    $settings['key_id'] = $_POST['key_id'] = input_clean($_POST['key_id'], 512);
                    $settings['key_secret'] = $_POST['key_secret'] = input_clean($_POST['key_secret'], 512);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret'], 512);
                    break;

                case 'mollie':
                    $settings['api_key'] = $_POST['api_key'] = input_clean($_POST['api_key'], 512);
                    break;

                case 'plisio':
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    $settings['accepted_cryptocurrencies'] = $_POST['accepted_cryptocurrencies'] = isset($_POST['accepted_cryptocurrencies']) ?
                        array_filter($_POST['accepted_cryptocurrencies'], function($cryptocurrency) {
                            return in_array($cryptocurrency, settings()->plisio->accepted_cryptocurrencies);
                        }) : [];
                    $settings['default_cryptocurrency'] = $_POST['default_cryptocurrency'] = isset($_POST['default_cryptocurrency']) && in_array($_POST['default_cryptocurrency'], $_POST['accepted_cryptocurrencies']) ? $_POST['default_cryptocurrency'] : reset($_POST['accepted_cryptocurrencies']);

                    break;

                case 'plisio_whitelabel':
                    foreach(settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [] as $cryptocurrency) {
                        $settings[$cryptocurrency . '_wallet'] = $_POST[$cryptocurrency . '_wallet'] = input_clean($_POST[$cryptocurrency . '_wallet'], 512);
                    }
                    $settings['default_cryptocurrency'] = $_POST['default_cryptocurrency'] = isset($_POST['default_cryptocurrency']) && in_array($_POST['default_cryptocurrency'], (settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [])) ? $_POST['default_cryptocurrency'] : null;

                    break;

                case 'offline_payment':
                    $settings['instructions'] = $_POST['instructions'] = input_clean($_POST['instructions'], 1024);
                    break;
            }

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
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
