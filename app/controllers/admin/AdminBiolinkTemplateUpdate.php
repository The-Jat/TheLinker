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

class AdminBiolinkTemplateUpdate extends Controller {

    public function index() {

        $biolink_template_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$biolink_template = db()->where('biolink_template_id', $biolink_template_id)->getOne('biolinks_templates')) {
            redirect('admin/biolinks-templates');
        }
        $biolink_template->settings = json_decode($biolink_template->settings ?? '');

        $biolinks = [];
        $result = database()->query("
            SELECT 
                `links`.`link_id`, `links`.`domain_id`, `links`.`url`,
                `domains`.`scheme`, `domains`.`host`, `domains`.`link_id` as `domain_link_id`
            FROM 
                `links` 
            LEFT JOIN `users` ON `users`.`user_id` = `links`.`user_id`
            LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id`
            WHERE 
                `users`.`type` = 1
                AND `links`.`type` = 'biolink'
        ");

        while($row = $result->fetch_object()) {
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . ($row->domain_link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;
            $biolinks[$row->link_id] = $row;
        }

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['link_id'] = (int) $_POST['link_id'];
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
            $_POST['url'] = $biolinks[$_POST['link_id']]->full_url ?? '';

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!db()->where('link_id', $_POST['link_id'])->where('type', 'biolink')->getOne('links')) {
                Alerts::add_field_error('link_id', l('admin_biolinks_templates.error_message.link_id'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $settings = json_encode([]);

                /* Database query */
                db()->where('biolink_template_id', $biolink_template_id)->update('biolinks_templates', [
                    'link_id' => $_POST['link_id'],
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('biolinks_templates');

                /* Refresh the page */
                redirect('admin/biolink-template-update/' . $biolink_template_id);

            }

        }

        /* Main View */
        $data = [
            'biolink_template' => $biolink_template,
            'biolinks' => $biolinks,
        ];

        $view = new \Altum\View('admin/biolink-template-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
