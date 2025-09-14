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

use Altum\Models\Domain;

defined('ALTUMCODE') || die();

class LinkRedirect extends Controller {

    public function index() {

        $link_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$link = db()->where('link_id', $link_id)->getOne('links', ['link_id', 'domain_id', 'user_id', 'url'])) {
            redirect();
        }

        /* Get the current domain if needed */
        $link->domain = $link->domain_id ? (new Domain())->get_domain_by_domain_id($link->domain_id) : null;

        /* Determine the actual full url */
        $link->full_url = $link->domain_id && isset($link->domain) ? $link->domain->url . '/' . ($link->domain->link_id == $link->link_id ? null : $link->url) : SITE_URL . $link->url;

        header('Location: ' . $link->full_url);

        die();

    }
}
