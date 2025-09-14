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

defined('ALTUMCODE') || die();

class AdminBiolinkThemeUpdate extends Controller {

    public function index() {

        $biolink_theme_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$biolink_theme = db()->where('biolink_theme_id', $biolink_theme_id)->getOne('biolinks_themes')) {
            redirect('admin/biolinks-themes');
        }
        $biolink_theme->settings = json_decode($biolink_theme->settings ?? '');

        $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
        $links_types = require APP_PATH . 'includes/links_types.php';

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            $_POST['additional_custom_css'] = mb_substr(trim($_POST['additional_custom_css']), 0, 10000);
            $_POST['additional_custom_js'] = mb_substr(trim($_POST['additional_custom_js']), 0, 10000);

            /* Width */
            $_POST['width'] = isset($_POST['biolink_width']) && in_array($_POST['biolink_width'], [6, 8, 10, 12]) ? (int) $_POST['biolink_width'] : 8;

            /* Block spacing */
            $_POST['block_spacing'] = isset($_POST['biolink_block_spacing']) && in_array($_POST['biolink_block_spacing'], [1, 2, 3,]) ? (int) $_POST['biolink_block_spacing'] : 2;

            /* Link hover animation */
            $_POST['hover_animation'] = isset($_POST['biolink_hover_animation']) && in_array($_POST['biolink_hover_animation'], ['false', 'smooth', 'instant',]) ? input_clean($_POST['biolink_hover_animation']) : 'smooth';


            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for errors & process  potential uploads */
            $background_new_name = $biolink_theme->settings->biolink->background_type == 'image' ? $biolink_theme->settings->biolink->background : null;

            if($_POST['biolink_background_type'] == 'image') {
                $background_new_name = \Altum\Uploads::process_upload($biolink_theme->settings->biolink->background, 'biolink_background', 'biolink_background_image', 'biolink_background_image_remove', null);
            }

            if($background_new_name && $_POST['biolink_background_type'] != 'image') {
                $background_new_name = null;
                \Altum\Uploads::delete_uploaded_file($background_new_name, 'biolink_background');
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $biolink_background = $background_new_name ?? $_POST['biolink_background'] ?? null;

                $settings = json_encode([
                    'additional' => [
                        'custom_css' => $_POST['additional_custom_css'] ?? null,
                        'custom_js' => $_POST['additional_custom_js'] ?? null,
                    ],

                    'biolink' => [
                        'background_type' => $_POST['biolink_background_type'],
                        'background' => $biolink_background,
                        'background_color_one' => $_POST['biolink_background_color_one'],
                        'background_color_two' => $_POST['biolink_background_color_two'],
                        'font' => $_POST['biolink_font'],
                        'font_size' => $_POST['biolink_font_size'],
                        'background_blur' => (int) $_POST['biolink_background_blur'],
                        'background_brightness' => (int) $_POST['biolink_background_brightness'],
                        'width' => $_POST['width'],
                        'block_spacing' => $_POST['block_spacing'],
                        'hover_animation' => $_POST['hover_animation'],
                    ],

                    'biolink_block' => [
                        'text_color' => $_POST['biolink_block_text_color'],
                        'title_color' => $_POST['biolink_block_text_color'],
                        'description_color' => $_POST['biolink_block_description_color'],
                        'background_color' => $_POST['biolink_block_background_color'],
                        'border_width' => $_POST['biolink_block_border_width'],
                        'border_color' => $_POST['biolink_block_border_color'],
                        'border_radius' => $_POST['biolink_block_border_radius'],
                        'border_style' => $_POST['biolink_block_border_style'],
                        'border_shadow_offset_x' => $_POST['biolink_block_border_shadow_offset_x'],
                        'border_shadow_offset_y' => $_POST['biolink_block_border_shadow_offset_y'],
                        'border_shadow_blur' => $_POST['biolink_block_border_shadow_blur'],
                        'border_shadow_spread' => $_POST['biolink_block_border_shadow_spread'],
                        'border_shadow_color' => $_POST['biolink_block_border_shadow_color'],
                    ],

                    'biolink_block_socials' => [
                        'color' => $_POST['biolink_block_socials_color'],
                        'background_color' => $_POST['biolink_block_socials_background_color'],
                        'border_radius' => $_POST['biolink_block_socials_border_radius'],
                    ],

                    'biolink_block_paragraph' => [
                        'text_color' => $_POST['biolink_block_paragraph_text_color'],
                        'background_color' => $_POST['biolink_block_paragraph_background_color'],
                        'border_radius' => $_POST['biolink_block_paragraph_border_radius'],
                    ],

                    'biolink_block_heading' => [
                        'text_color' => $_POST['biolink_block_heading_text_color'],
                    ],
                ]);

                /* Database query */
                db()->where('biolink_theme_id', $biolink_theme_id)->update('biolinks_themes', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('biolinks_themes');

                /* Refresh the page */
                redirect('admin/biolink-theme-update/' . $biolink_theme_id);

            }

        }

        /* Main View */
        $data = [
            'biolink_theme_id' => $biolink_theme_id,
            'biolink_theme' => $biolink_theme,
            'biolink_backgrounds' => $biolink_backgrounds,
            'biolink_fonts' => settings()->links->biolinks_fonts,
            'links_types' => $links_types,
        ];

        $view = new \Altum\View('admin/biolink-theme-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
