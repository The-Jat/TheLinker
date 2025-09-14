<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
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
use Altum\Title;

defined('ALTUMCODE') || die();

class SynthesisUpdate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.syntheses')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $synthesis_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get syntheses details */
        if(!$synthesis = db()->where('synthesis_id', $synthesis_id)->where('user_id', $this->user->user_id)->getOne('syntheses')) {
            redirect();
        }

        $synthesis->settings = json_decode($synthesis->settings ?? '');

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* AI Syntheses */
        $ai_syntheses_apis = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_apis.php';

        /* Selected AI model */
        $this->user->plan_settings->syntheses_api = $this->user->plan_settings->syntheses_api ?? 'aws_polly';
        $ai_api = $ai_syntheses_apis[$this->user->plan_settings->syntheses_api];

        /* Languages */
        $ai_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_languages.php';

        /* Voices */
        $ai_voices = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_voices.php';

        /* Engines/Models */
        $ai_engines = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_engines.php';

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('synthesis_id', $synthesis->synthesis_id)->update('syntheses', [
                    'project_id' => $_POST['project_id'],
                    'name' => $_POST['name'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('synthesis-update/' . $synthesis->synthesis_id);
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('synthesis_update.title'), $synthesis->name));

        /* Main View */
        $data = [
            'synthesis' => $synthesis,
            'ai_languages' => $ai_languages,
            'ai_voices' => $ai_voices,
            'projects' => $projects ?? [],
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/synthesis-update/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

}
