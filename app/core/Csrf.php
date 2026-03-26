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

namespace Altum;

defined('ALTUMCODE') || die();

class Csrf {

    public static function set($name = 'token', $regenerate = false) {
        $existing_token = session_get($name, null);
        $new_token = bin2hex(random_bytes(32));

        if (is_null($existing_token) || $regenerate) {
            session_set($name, $new_token);
			return $new_token;
        }

		return $existing_token;
    }

    public static function get($name = 'token') {
        return session_get($name, null) ?? self::set($name);
    }

    public static function get_url_query($name = 'token') {
        return '&' . $name . '=' . self::get($name);
    }

    public static function check($name = 'token') {
        $token = self::get($name);

        return (
            (isset($_GET[$name]) && hash_equals($token, $_GET[$name])) ||
            (isset($_POST[$name]) && hash_equals($token, $_POST[$name]))
        );
    }

}
