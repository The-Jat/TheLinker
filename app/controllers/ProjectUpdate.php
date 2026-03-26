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

class ProjectUpdate extends Controller {

    public function index() {

        if(!settings()->links->projects_is_enabled) {
            throw_404();
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.projects')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('projects');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `projects` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->projects_limit != -1 && $total_rows > $this->user->plan_settings->projects_limit) {
            redirect('projects');
        }

        $project_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$project = db()->where('project_id', $project_id)->where('user_id', $this->user->user_id)->getOne('projects')) {
            redirect('projects');
        }

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['color'] = !verify_hex_color($_POST['color']) ? '#000000' : $_POST['color'];

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('project_id', $project->project_id)->update('projects', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('projects?user_id=' . $this->user->user_id);

                redirect('project-update/' . $project_id);
            }
        }

        /* Prepare the view */
        $data = [
            'project' => $project,
        ];

        $view = new \Altum\View('project-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
