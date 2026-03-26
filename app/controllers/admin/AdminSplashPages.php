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

class AdminSplashPages extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['name'], ['splash_page_id', 'last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by($this->user->preferences->splash_pages_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `splash_pages` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/splash-pages?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $splash_pages = [];
        $splash_pages_result = database()->query("
            SELECT
                `splash_pages`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`
            FROM
                `splash_pages`
            LEFT JOIN
                `users` ON `splash_pages`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('splash_pages')}
                {$filters->get_sql_order_by('splash_pages')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $splash_pages_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $splash_pages[] = $row;
        }

        /* Export handler */
        process_export_csv_new($splash_pages, ['splash_page_id', 'user_id', 'name', 'settings', 'last_datetime', 'datetime'], ['settings'], sprintf(l('splash_pages.title')));
        process_export_json($splash_pages, ['splash_page_id', 'user_id', 'name', 'settings', 'last_datetime', 'datetime'], sprintf(l('splash_pages.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'splash_pages' => $splash_pages,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/splash-pages/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/splash-pages');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/splash-pages');
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

                    foreach($_POST['selected'] as $splash_page_id) {

                        (new \Altum\Models\SplashPages())->delete($splash_page_id);

                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/splash-pages');
    }

    public function delete() {

        $splash_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$splash_page = db()->where('splash_page_id', $splash_page_id)->getOne('splash_pages', ['splash_page_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\SplashPages())->delete($splash_page->splash_page_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $splash_page->name . '</strong>'));

        }

        redirect('admin/splash-pages');
    }

}
