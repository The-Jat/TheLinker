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

class AdminTemplateCategoryCreate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            throw_404();
        }

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['icon'] = input_clean($_POST['icon'], 64);
            $_POST['emoji'] = input_clean($_POST['emoji'], 64);
            $_POST['color'] = !verify_hex_color($_POST['color']) ? '#ffffff' : $_POST['color'];
            $_POST['background'] = !verify_hex_color($_POST['background']) ? '#000000' : $_POST['background'];
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            $settings = json_encode(['translations' => $_POST['translations']]);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('templates_categories', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'icon' => $_POST['icon'],
                    'emoji' => $_POST['emoji'],
                    'color' => $_POST['color'],
                    'background' => $_POST['background'],
                    'order' => $_POST['order'],
                    'is_enabled' => $_POST['is_enabled'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('templates_categories');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/templates-categories');
            }
        }

        /* Main View */
        $data = [];

        $view = new \Altum\View('admin/template-category-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
