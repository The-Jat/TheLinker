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

class AdminGuestsPayments extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['guest_payment_id', 'biolink_block_id', 'link_id', 'user_id', 'payment_processor_id', 'project_id', 'type', 'processor', 'status'], ['email', 'name'], ['guest_payment_id', 'total_amount', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->guests_payments_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `guests_payments` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/guests-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $guests_payments = [];
        $guests_payments_result = database()->query("
            SELECT
                `guests_payments`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`, `biolinks_blocks`.`settings` 
            FROM
                `guests_payments`
            LEFT JOIN
                `users` ON `guests_payments`.`user_id` = `users`.`user_id`
            LEFT JOIN 
                `biolinks_blocks` ON `biolinks_blocks`.`biolink_block_id` = `guests_payments`.`biolink_block_id`
            
            WHERE
                1 = 1
                {$filters->get_sql_where('guests_payments')}
                {$filters->get_sql_order_by('guests_payments')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $guests_payments_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $row->biolink_block_name = $row->settings->name ?? null;

            $guests_payments[] = $row;
        }

        /* Export handler */
        process_export_csv_new($guests_payments, ['guest_payment_id', 'biolink_block_id', 'biolink_block_name', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'data', 'status', 'datetime'], sprintf(l('guests_payments.title')), ['data']);
        process_export_json($guests_payments, ['guest_payment_id', 'biolink_block_id', 'biolink_block_name', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'data', 'status', 'datetime'], sprintf(l('guests_payments.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'guests_payments' => $guests_payments,
            'filters' => $filters,
            'pagination' => $pagination,
            'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
        ];

        $view = new \Altum\View('admin/guests-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/guests-payments');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/guests-payments');
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

                    $users_ids = [];

                    foreach($_POST['selected'] as $guest_payment_id) {
                        db()->where('guest_payment_id', $guest_payment_id)->delete('guests_payments');
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/guests-payments');
    }

    public function delete() {

        $guest_payment_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$guest_payment = db()->where('guest_payment_id', $guest_payment_id)->getOne('guests_payments', ['guest_payment_id', 'user_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('guest_payment_id', $guest_payment_id)->delete('guests_payments');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

        }

        redirect('admin/guests-payments');
    }

}
