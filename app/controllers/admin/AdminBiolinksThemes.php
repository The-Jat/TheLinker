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

class AdminBiolinksThemes extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['biolink_theme_id', 'datetime', 'last_datetime', 'name', 'order']));
        $filters->set_default_order_by('biolink_theme_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `biolinks_themes` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/biolinks-themes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $biolinks_themes = [];
        $biolinks_themes_result = database()->query("
            SELECT
                `biolinks_themes`.*,
                COUNT(`links`.`biolink_theme_id`) AS `total_usage`
            FROM
                `biolinks_themes`
            LEFT JOIN `links` ON `biolinks_themes`.`biolink_theme_id` = `links`.`biolink_theme_id`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
            GROUP BY `biolinks_themes`.`biolink_theme_id`
                {$filters->get_sql_order_by()}
                {$paginator->get_sql_limit()}
        ");
        while($row = $biolinks_themes_result->fetch_object()) {
            $biolinks_themes[] = $row;
        }

        /* Export handler */
        process_export_csv($biolinks_themes, ['biolink_theme_id', 'name', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_biolinks_themes.title')));
        process_export_json($biolinks_themes, ['biolink_theme_id', 'name', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_biolinks_themes.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'biolinks_themes' => $biolinks_themes,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/biolinks-themes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/biolinks-themes');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/biolinks-themes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            session_write_close();

            $_POST['selected'] = is_array($_POST['selected']) ? array_unique(array_map('intval', $_POST['selected'])) : [];

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $biolink_theme_id) {
                        $biolink_theme = db()->where('biolink_theme_id', $biolink_theme_id)->getOne('biolinks_themes');

                        if(!$biolink_theme) {
                            continue;
                        }

                        $biolink_theme->settings = json_decode($biolink_theme->settings ?? '');

                        /* Offload deleting */
                        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            if(!empty($biolink_theme->settings->biolink->background) && file_exists(UPLOADS_PATH . 'backgrounds' . '/' . $biolink_theme->settings->biolink->background)) {
                                $s3->deleteObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/backgrounds/' . $biolink_theme->settings->biolink->background,
                                ]);
                            }
                        }

                        /* Local deleting */
                        else {
                            if(!empty($biolink_theme->settings->biolink->background) && file_exists(UPLOADS_PATH . 'backgrounds/' . $biolink_theme->settings->biolink->background)) {
                                unlink(UPLOADS_PATH . 'backgrounds/' . $biolink_theme->settings->biolink->background);
                            }
                        }

                        /* Delete the resource */
                        db()->where('biolink_theme_id', $biolink_theme_id)->delete('biolinks_themes');
                    }

                    /* Clear the cache */
                    cache()->deleteItem('biolinks_themes');

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/biolinks-themes');
    }

    public function duplicate() {

        if (empty($_POST)) {
            throw_404();
        }

        $biolink_theme_id = (int) $_POST['biolink_theme_id'];

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$biolink_theme = db()->where('biolink_theme_id', $biolink_theme_id)->getOne('biolinks_themes')) {
            redirect('admin/biolinks-themes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            $biolink_theme->settings = json_decode($biolink_theme->settings ?? '');
            if($biolink_theme->settings->biolink->background_type == 'image') $biolink_theme->settings->biolink->background = \Altum\Uploads::copy_uploaded_file($biolink_theme->settings->biolink->background, 'backgrounds/', 'backgrounds/');

            /* Insert to database */
            $biolink_theme_id = db()->insert('biolinks_themes', [
                'name' => string_truncate($biolink_theme->name . ' - ' . l('global.duplicated'), 64, null),
                'settings' => json_encode($biolink_theme->settings),
                'is_enabled' => $biolink_theme->is_enabled,
                'order' => $biolink_theme->order + 1,
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($biolink_theme->name) . '</strong>'));

            /* Redirect */
            redirect('admin/biolink-theme-update/' . $biolink_theme_id);

        }

        redirect('admin/biolinks-themes');
    }

    public function delete() {

        $biolink_theme_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$biolink_theme = db()->where('biolink_theme_id', $biolink_theme_id)->getOne('biolinks_themes')) {
            redirect('admin/biolinks-themes');
        }

        $biolink_theme->settings = json_decode($biolink_theme->settings ?? '');

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Offload deleting */
            if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                if(!empty($biolink_theme->settings->biolink->background) && file_exists(UPLOADS_PATH . 'backgrounds' . '/' . $biolink_theme->settings->biolink->background)) {
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/backgrounds/' . $biolink_theme->settings->biolink->background,
                    ]);
                }
            }

            /* Local deleting */
            else {
                if(!empty($biolink_theme->settings->biolink->background) && file_exists(UPLOADS_PATH . 'backgrounds/' . $biolink_theme->settings->biolink->background)) {
                    unlink(UPLOADS_PATH . 'backgrounds/' . $biolink_theme->settings->biolink->background);
                }
            }

            /* Delete the resource */
            db()->where('biolink_theme_id', $biolink_theme->biolink_theme_id)->delete('biolinks_themes');

            /* Clear the cache */
            cache()->deleteItem('biolinks_themes');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $biolink_theme->name . '</strong>'));

        }

        redirect('admin/biolinks-themes');
    }

}
