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

class AdminPaymentProcessors extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'payment_processor_id', 'processor', 'is_enabled'], ['name'], ['payment_processor_id', 'last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->payment_processors_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payment_processors` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/payment-processors?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $payment_processors = [];
        $payment_processors_result = database()->query("
            SELECT
                `payment_processors`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`
            FROM
                `payment_processors`
            LEFT JOIN
                `users` ON `payment_processors`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('payment_processors')}
                {$filters->get_sql_order_by('payment_processors')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $payment_processors_result->fetch_object()) {
            $payment_processors[] = $row;
        }

        /* Export handler */
        process_export_csv($payment_processors, ['payment_processor_id', 'user_id', 'name', 'processor', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('payment_processors.title')));
        process_export_json($payment_processors, ['payment_processor_id', 'user_id', 'name', 'processor', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('payment_processors.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'payment_processors' => $payment_processors,
            'filters' => $filters,
            'pagination' => $pagination,
        ];

        $view = new \Altum\View('admin/payment-processors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/payment-processors');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/payment-processors');
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

                    foreach($_POST['selected'] as $payment_processor_id) {
                        if($payment_processor = db()->where('payment_processor_id', $payment_processor_id)->getOne('payment_processors', ['payment_processor_id', 'user_id'])) {
                            db()->where('payment_processor_id', $payment_processor_id)->delete('payment_processors');

                            if(!in_array($payment_processor->user_id, $users_ids)) {
                                $users_ids[] = $payment_processor->user_id;
                            }
                        }
                    }

                    foreach($users_ids as $user_id) {
                        /* Clear the cache */
                        cache()->deleteItemsByTag('payment_processors?user_id=' . $user_id);
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/payment-processors');
    }

    public function delete() {

        $payment_processor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$payment_processor = db()->where('payment_processor_id', $payment_processor_id)->getOne('payment_processors', ['payment_processor_id', 'user_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('payment_processor_id', $payment_processor_id)->delete('payment_processors');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $payment_processor->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItemsByTag('payment_processors?user_id=' . $payment_processor->user_id);

        }

        redirect('admin/payment-processors');
    }

}
