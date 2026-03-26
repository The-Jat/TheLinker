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

namespace Altum\PaymentGateways;

/* Helper class for Plisio */
defined('ALTUMCODE') || die();

class Plisio {
    static public $api_url = 'https://api.plisio.net/';

    public static function get_api_url() {
        return self::$api_url;
    }

    public static function validate_hash($secret_key) {
        if (!isset($_POST['verify_hash'])) {
            return false;
        }

        $post = $_POST;
        $verifyHash = $post['verify_hash'];
        unset($post['verify_hash']);
        ksort($post);

        if (isset($post['expire_utc'])){
            $post['expire_utc'] = (string)$post['expire_utc'];
        }
        if (isset($post['tx_urls'])){
            $post['tx_urls'] = html_entity_decode($post['tx_urls']);
        }

        $postString = serialize($post);
        $checkKey = hash_hmac('sha1', $postString, $secret_key);

        if ($checkKey != $verifyHash) {
            return false;
        }

        return true;
    }
}
