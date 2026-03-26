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

class AdminData extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['datum_id', 'biolink_block_id', 'link_id', 'project_id', 'user_id', 'type', 'is_enabled'], [], ['datum_id', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->data_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `data` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/data?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                `data`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`, `biolinks_blocks`.`settings`
            FROM
                `data`
            LEFT JOIN
                `users` ON `data`.`user_id` = `users`.`user_id`
            LEFT JOIN 
                `biolinks_blocks` ON `biolinks_blocks`.`biolink_block_id` = `data`.`biolink_block_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('data')}
                {$filters->get_sql_order_by('data')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {
            $row->data = json_decode($row->data);
            $row->settings = json_decode($row->settings ?? '');
            $row->biolink_block_name = $row->settings->name ?? null;

            $data[] = $row;
        }

        /* Export handler */
        process_export_csv_new($data, ['datum_id', 'link_id', 'biolink_block_id', 'biolink_block_name', 'user_id', 'project_id', 'type', 'data', 'datetime'], ['data'], sprintf(l('data.title')));
        process_export_json($data, ['datum_id', 'link_id', 'biolink_block_id', 'biolink_block_name', 'user_id', 'project_id', 'type', 'data', 'datetime'], sprintf(l('data.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'data' => $data,
            'filters' => $filters,
            'pagination' => $pagination,
            'biolink_blocks'    => require APP_PATH . 'includes/biolink_blocks.php',
        ];

        $view = new \Altum\View('admin/data/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/data');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/data');
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

                    foreach($_POST['selected'] as $datum_id) {
                        db()->where('datum_id', $datum_id)->delete('data');
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/data');
    }

    public function delete() {

        $datum_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$data = db()->where('datum_id', $datum_id)->getOne('data', ['datum_id'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('datum_id', $datum_id)->delete('data');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

        }

        redirect('admin/data');
    }

}
