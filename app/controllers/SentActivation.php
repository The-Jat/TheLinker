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
use Altum\Captcha;
use Altum\Logger;
use Altum\Meta;

defined('ALTUMCODE') || die();

class SentActivation extends Controller {

    public function index() {

        \Altum\Authentication::guard('guest');

        if(!settings()->users->email_confirmation) {
            throw_404();
        }

        $email = session_get('sent_activation_email');

        if(!$email) {
            redirect('resend-activation');
        }

        /* Clear email session */
        session_unset_key('sent_activation_email');

        /* Meta */
        Meta::set_robots('noindex');

        /* Disable OG Image */
        if(\Altum\Plugin::is_active('dynamic-og-images') && settings()->dynamic_og_images->is_enabled) {
            \Altum\Plugin\DynamicOgImages::$should_process = false;
        }

        /* Prepare the view */
        $data = [
            'email' => $email,
        ];

        $view = new \Altum\View('sent-activation/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
