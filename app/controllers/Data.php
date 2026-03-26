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

class Data extends Controller {

    public function index() {

        if(!settings()->links->biolinks_is_enabled) {
            throw_404();
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['datum_id', 'biolink_block_id', 'link_id', 'project_id', 'user_id', 'type', 'is_enabled'], [], ['datum_id', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->data_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `data` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('data?' . $filters->get_get() . '&page=%d')));

        /* Get the data list for the user */
        $data = [];
        $data_result = database()->query("
            SELECT `data`.*, `biolinks_blocks`.`settings` 
            FROM `data` 
            LEFT JOIN `biolinks_blocks` ON `biolinks_blocks`.`biolink_block_id` = `data`.`biolink_block_id`
            WHERE 
                `data`.`user_id` = {$this->user->user_id} 
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
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'data'              => $data,
            'total_data'        => $total_rows,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'biolink_blocks'    => require APP_PATH . 'includes/biolink_blocks.php',
        ];

        $view = new \Altum\View('data/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('data');
        }

        if(!isset($_POST['type'])) {
            redirect('data');
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
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.data')) {
                        Alerts::add_error(l('global.info_message.team_no_access'));
                        redirect('data');
                    }

                    foreach($_POST['selected'] as $datum_id) {
                        db()->where('user_id', $this->user->user_id)->where('datum_id', $datum_id)->delete('data');
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('data');
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.data')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('data');
        }

        if (empty($_POST)) {
            throw_404();
        }

        $datum_id = (int) $_POST['datum_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$datum = db()->where('datum_id', $datum_id)->where('user_id', $this->user->user_id)->getOne('data', ['datum_id'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('datum_id', $datum_id)->delete('data');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('data');
        }

        redirect('data');
    }
}
