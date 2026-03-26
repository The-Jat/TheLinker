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
use Altum\Models\Domain;

defined('ALTUMCODE') || die();

class Links extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Check for the plan limit */
        $total_links = [];
        $total_links_result = database()->query("SELECT COUNT(`type`) AS `total`, `type` FROM `links` WHERE `user_id` = {$this->user->user_id} GROUP BY `type`");
        while($row = $total_links_result->fetch_object()) {
            if(isset($_GET['type']) && $_GET['type'] == $row->type) {
                $total_links[$row->type] = $row->total;
            }

            if(!isset($_GET['type'])) {
                $total_links[$row->type] = $row->total;
            }
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'type', 'project_id', 'domain_id', 'pixels_ids'], ['url', 'location_url'], ['link_id', 'last_datetime', 'datetime', 'clicks', 'url'], [], ['pixels_ids' => 'json_contains']));
        $filters->set_default_order_by($this->user->preferences->links_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `user_id` = {$this->user->user_id}  {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('links?' . $filters->get_get() . '&page=%d')));

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Get the links list for the project */
        $links_result = database()->query("
            SELECT 
                *
            FROM 
                `links`
            WHERE 
                `user_id` = {$this->user->user_id} 
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $links = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = $row->domain_id && isset($domains[$row->domain_id]) ? $domains[$row->domain_id]->scheme . $domains[$row->domain_id]->host . '/' . ($domains[$row->domain_id]->link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;
            $row->settings = json_decode($row->settings);
            $links[] = $row;
        }

        /* Export handler */
        process_export_csv($links, ['link_id', 'user_id', 'project_id', 'pixels_ids', 'type', 'url', 'location_url', 'start_date', 'end_date', 'clicks', 'is_verified', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('links.title')));
        process_export_json($links, ['link_id', 'user_id', 'project_id', 'pixels_ids', 'type', 'url', 'location_url', 'settings', 'start_date', 'end_date', 'clicks', 'is_verified', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('links.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Create Link Modal */
        $view = new \Altum\View('links/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run(['domains' => $domains]), 'modals');

        /* Delete Modal */
        $view = new \Altum\View('links/link_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the Links Content View */
        $data = [
            'links'             => $links,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'projects'          => $projects,
            'domains'           => $domains,
            'links_types'       => require APP_PATH . 'includes/links_types.php',
        ];
        $view = new \Altum\View('links/links_content', (array) $this);
        $this->add_view_content('links_content', $view->run($data));

        /* Prepare the view */
        $data = [
            'total_links'=> $total_links,
        ];

        $view = new \Altum\View('links/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        \Altum\Authentication::guard();

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('links');
        }

        if(!isset($_POST['type'])) {
            redirect('links');
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

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.links')) {
                        Alerts::add_error(l('global.info_message.team_no_access'));
                        redirect('links');
                    }

                    foreach($_POST['selected'] as $link_id) {
                        if($link = db()->where('link_id', $link_id)->where('user_id', $this->user->user_id)->getOne('links', ['link_id'])) {
                            /* Delete the resource */
                            (new \Altum\Models\Link())->delete($link->link_id);
                        }
                    }

                    break;

            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('links');
    }

    public function reset() {
        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.links')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('links');
        }

        if (empty($_POST)) {
            throw_404();
        }

        $link_id = (int) $_POST['link_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('links');
        }

        /* Make sure the link id is created by the logged in user */
        if(!$link = db()->where('link_id', $link_id)->where('user_id', $this->user->user_id)->getOne('links', ['link_id'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Reset data */
            db()->where('link_id', $link_id)->update('links', [
                'clicks' => 0,
            ]);

            /* Remove data */
            db()->where('link_id', $link_id)->delete('track_links');

            /* Clear the cache */
            cache()->deleteItem('link?link_id=' . $link->link_id);
            cache()->deleteItemsByTag('link_id=' . $link->link_id);
            cache()->deleteItem('links?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.update2'));

            redirect('links');

        }

        redirect('links');
    }

}
