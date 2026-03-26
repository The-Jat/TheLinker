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

class AdminTranscriptions extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            throw_404();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'language'], ['name'], ['transcription_id', 'last_datetime', 'datetime', 'name', 'words']));
        $filters->set_default_order_by($this->user->preferences->transcriptions_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `transcriptions` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/transcriptions?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $transcriptions = [];
        $transcriptions_result = database()->query("
            SELECT
                `transcriptions`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`
            FROM
                `transcriptions`
            LEFT JOIN
                `users` ON `transcriptions`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('transcriptions')}
                {$filters->get_sql_order_by('transcriptions')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $transcriptions_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $transcriptions[] = $row;
        }

        /* Export handler */
        process_export_csv($transcriptions, ['transcription_id', 'project_id', 'user_id', 'name', 'input', 'content', 'words', 'language', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('transcriptions.title')));
        process_export_json($transcriptions, ['transcription_id', 'project_id', 'user_id', 'name', 'input', 'content', 'words', 'language', 'settings', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('transcriptions.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* AI Languages */
        $ai_transcriptions_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_transcriptions_languages.php';

        /* Main View */
        $data = [
            'transcriptions' => $transcriptions,
            'filters' => $filters,
            'pagination' => $pagination,
            'ai_transcriptions_languages' => $ai_transcriptions_languages,
        ];

        $view = new \Altum\View('admin/transcriptions/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/transcriptions');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/transcriptions');
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

                    foreach($_POST['selected'] as $transcription_id) {

                        $transcription = db()->where('transcription_id', $transcription_id)->getOne('transcriptions', ['user_id']);

                        /* Delete the resource */
                        db()->where('transcription_id', $transcription->transcription_id)->delete('transcriptions');

                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/transcriptions');
    }

    public function delete() {

        $transcription_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$transcription = db()->where('transcription_id', $transcription_id)->getOne('transcriptions', ['transcription_id', 'user_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('transcription_id', $transcription->transcription_id)->delete('transcriptions');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $transcription->name . '</strong>'));

        }

        redirect('admin/transcriptions');
    }

}
