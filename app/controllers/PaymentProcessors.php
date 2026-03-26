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

class PaymentProcessors extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            throw_404();
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['payment_processor_id', 'processor', 'is_enabled'], ['name'], ['payment_processor_id', 'last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->payment_processors_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payment_processors` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('payment-processors?' . $filters->get_get() . '&page=%d')));

        /* Get the data list for the user */
        $payment_processors = [];
        $payment_processors_result = database()->query("SELECT * FROM `payment_processors` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $payment_processors_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $payment_processors[] = $row;
        }

        /* Export handler */
        process_export_csv_new($payment_processors, ['payment_processor_id', 'user_id', 'name', 'processor', 'settings', 'is_enabled', 'last_datetime', 'datetime'], ['settings'], sprintf(l('payment_processors.title')));
        process_export_json($payment_processors, ['payment_processor_id', 'user_id', 'name', 'processor', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('payment_processors.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'payment_processors' => $payment_processors,
            'total_payment_processors' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('payment-processors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('payment-processors');
        }

        if(!isset($_POST['type'])) {
            redirect('payment-processors');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            session_write_close();

            $_POST['selected'] = is_array($_POST['selected']) ? array_unique(array_map('intval', $_POST['selected'])) : [];

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.payment_processors')) {
                        Alerts::add_error(l('global.info_message.team_no_access'));
                        redirect('payment-processors');
                    }

                    foreach($_POST['selected'] as $payment_processor_id) {
                        db()->where('user_id', $this->user->user_id)->where('payment_processor_id', $payment_processor_id)->delete('payment_processors');
                    }

                    break;
            }

            /* Clear the cache */
            cache()->deleteItemsByTag('payment_processors?user_id=' . $this->user->user_id);

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('payment-processors');
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.payment_processors')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('payment-processors');
        }

        if (empty($_POST)) {
            throw_404();
        }

        $payment_processor_id = (int) $_POST['payment_processor_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$payment_processor = db()->where('payment_processor_id', $payment_processor_id)->where('user_id', $this->user->user_id)->getOne('payment_processors', ['name', 'payment_processor_id'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('payment_processor_id', $payment_processor_id)->delete('payment_processors');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $payment_processor->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItemsByTag('payment_processors?user_id=' . $this->user->user_id);

            redirect('payment-processors');
        }

        redirect('payment-processors');
    }
}
