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

class AdminTemplatesCategories extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            throw_404();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['template_category_id', 'datetime', 'name']));
        $filters->set_default_order_by('template_category_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `templates_categories` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/templates-categories?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $templates_categories = [];
        $templates_categories_result = database()->query("
            SELECT
                *
            FROM
                `templates_categories`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $templates_categories_result->fetch_object()) {
            $templates_categories[] = $row;
        }

        /* Export handler */
        process_export_json($templates_categories, ['template_category_id', 'name', 'settings', 'icon', 'emoji', 'color', 'background', 'order', 'is_enabled', 'datetime', 'last_datetime']);
        process_export_csv($templates_categories, ['template_category_id', 'name', 'icon', 'emoji', 'color', 'background', 'order', 'is_enabled', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'templates_categories' => $templates_categories,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/templates-categories/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $template_category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template_category = db()->where('template_category_id', $template_category_id)->getOne('templates_categories', ['template_category_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('template_category_id', $template_category_id)->delete('templates_categories');

            /* Clear the cache */
            cache()->deleteItem('templates_categories');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $template_category->name . '</strong>'));

        }

        redirect('admin/templates-categories');
    }

}
