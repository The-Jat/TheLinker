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

class AdminPaymentCreate extends Controller {

    public function index() {

        if(!in_array(settings()->license->type, ['Extended License', 'extended'])) {
            redirect('admin');
        }

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Payment processors */
        $payment_processors = require APP_PATH . 'includes/payment_processors.php';

        /* Get the available taxes from the system */
        $taxes = db()->get('taxes');

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['payment_id'] = input_clean($_POST['payment_id'], 128);
            $_POST['user_id'] = (int) $_POST['user_id'];
            $_POST['email'] = input_clean($_POST['email'], 256);
            $_POST['plan_id'] = input_clean($_POST['plan_id']);
            $_POST['payment_processor'] = input_clean($_POST['payment_processor']);
            $_POST['payment_type'] = in_array($_POST['payment_type'], ['one_time', 'recurring']) ? input_clean($_POST['payment_type']) : 'one_time';
            $_POST['payment_frequency'] = in_array($_POST['payment_frequency'], ['monthly', 'quarterly', 'biannual', 'annual', 'lifetime']) ? input_clean($_POST['payment_frequency']) : 'monthly';
            $_POST['taxes_ids'] = isset($_POST['taxes_ids']) && is_array($_POST['taxes_ids']) ? $_POST['taxes_ids'] : [];
            $_POST['code'] = input_clean($_POST['code'], 32);
            $_POST['discount_amount'] = (float) $_POST['discount_amount'];
            $_POST['base_amount'] = (float) $_POST['base_amount'];
            $_POST['total_amount'] = (float) $_POST['total_amount'];
            $_POST['currency'] = array_key_exists($_POST['currency'], get_currencies_array()) ? $_POST['currency'] : settings()->payment->default_currency;
            $_POST['datetime'] = \Altum\Date::validate($_POST['datetime']) ? $_POST['datetime'] : get_date();

            $payment_proof = \Altum\Uploads::process_upload(null, 'offline_payment_proofs', 'payment_proof', 'payment_proof_remove', settings()->offline_payment->proof_size_limit);

            if(!$user = db()->where('user_id', $_POST['user_id'])->getOne('users', ['billing'])) {
                Alerts::add_error(l('admin_payments.error_message.user_id_not_exists'), 'user_id');
            }

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('payments', [
                    'payment_id' => $_POST['payment_id'],
                    'user_id' => $_POST['user_id'],
                    'email' => $_POST['email'],
                    'name' => $_POST['name'],
                    'plan' => json_encode(db()->where('plan_id', $_POST['plan_id'])->getOne('plans', ['plan_id', 'name'])),
                    'billing' => settings()->payment->taxes_and_billing_is_enabled && $user->billing ? $user->billing : null,
                    'business' => json_encode(settings()->business),
                    'plan_id' => $_POST['plan_id'],
                    'processor' => $_POST['payment_processor'],
                    'type' => $_POST['payment_type'],
                    'frequency' => $_POST['payment_frequency'],
                    'taxes_ids' => json_encode($_POST['taxes_ids']),
                    'code' => $_POST['code'],
                    'discount_amount' => $_POST['discount_amount'],
                    'base_amount' => $_POST['base_amount'],
                    'total_amount' => $_POST['total_amount'],
                    'currency' => $_POST['currency'],
                    'payment_proof' => $payment_proof,
                    'datetime' => $_POST['datetime'],
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['payment_id'] . '</strong>'));

                redirect('admin/payments');
            }
        }

        /* Set default values */
        $values = [
            'payment_id' => $_POST['payment_id'] ?? null,
            'user_id' => $_POST['user_id'] ?? null,
            'email' => $_POST['email'] ?? null,
            'name' => $_POST['name'] ?? null,
            'plan_id' => $_POST['plan_id'] ?? null,
            'payment_processor' => $_POST['payment_processor'] ?? array_key_first($payment_processors),
            'payment_type' => $_POST['payment_type'] ?? 'one_time',
            'payment_frequency' => $_POST['payment_frequency'] ?? 'monthly',
            'taxes_ids' => $_POST['taxes_ids'] ?? [],
            'code' => $_POST['code'] ?? null,
            'discount_amount' => $_POST['discount_amount'] ?? 0,
            'base_amount' => $_POST['base_amount'] ?? 0,
            'total_amount' => $_POST['total_amount'] ?? 0,
            'currency' => $_POST['currency'] ?? settings()->payment->default_currency,
            'datetime' => $_POST['datetime'] ?? get_date(),
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'payment_processors' => $payment_processors,
            'plans' => $plans,
            'taxes' => $taxes,
        ];

        $view = new \Altum\View('admin/payment-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
