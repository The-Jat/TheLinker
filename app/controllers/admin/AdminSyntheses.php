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

class AdminSyntheses extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender'], ['name'], ['synthesis_id', 'last_datetime', 'datetime', 'name', 'characters']));
        $filters->set_default_order_by($this->user->preferences->syntheses_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `syntheses` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/syntheses?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $syntheses = [];
        $syntheses_result = database()->query("
            SELECT
                `syntheses`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`
            FROM
                `syntheses`
            LEFT JOIN
                `users` ON `syntheses`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('syntheses')}
                {$filters->get_sql_order_by('syntheses')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $syntheses_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $syntheses[] = $row;
        }

        /* Export handler */
        process_export_csv($syntheses, ['synthesis_id', 'project_id', 'user_id', 'name', 'input', 'file', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender',  'characters', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('syntheses.title')));
        process_export_json($syntheses, ['synthesis_id', 'project_id', 'user_id', 'name', 'input', 'file', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender', 'settings', 'characters', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('syntheses.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Languages */
        $ai_languages = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_languages.php');

        /* Voices */
        $ai_voices = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_voices.php');

        /* Engines/Models */
        $ai_engines = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_engines.php');

        /* Formats */
        $ai_formats = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_formats.php');

        /* Main View */
        $data = [
            'syntheses' => $syntheses,
            'filters' => $filters,
            'pagination' => $pagination,
            'ai_voices' => $ai_voices,
            'ai_languages' => $ai_languages,
            'ai_engines' => $ai_engines,
            'ai_formats' => $ai_formats,
        ];

        $view = new \Altum\View('admin/syntheses/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/syntheses');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/syntheses');
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

                    foreach($_POST['selected'] as $synthesis_id) {

                        $synthesis = db()->where('synthesis_id', $synthesis_id)->getOne('syntheses', ['user_id', 'file']);

                        /* Delete file */
                        \Altum\Uploads::delete_uploaded_file($synthesis->file, 'syntheses');

                        /* Delete the resource */
                        db()->where('synthesis_id', $synthesis_id)->delete('syntheses');

                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/syntheses');
    }

    public function delete() {

        $synthesis_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$synthesis = db()->where('synthesis_id', $synthesis_id)->getOne('syntheses', ['user_id', 'name', 'file'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($synthesis->file, 'syntheses');

            /* Delete the resource */
            db()->where('synthesis_id', $synthesis_id)->delete('syntheses');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $synthesis->name . '</strong>'));

        }

        redirect('admin/syntheses');
    }

}
