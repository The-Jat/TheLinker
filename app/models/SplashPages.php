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

namespace Altum\Models;

defined('ALTUMCODE') || die();

class SplashPages extends Model {

    public function get_splash_pages_by_user_id($user_id) {
        if(!settings()->links->splash_page_is_enabled) return [];

        /* Get the user splash_pages */
        $splash_pages = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('splash_pages?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $splash_pages_result = database()->query("SELECT * FROM `splash_pages` WHERE `user_id` = {$user_id}");
            while($row = $splash_pages_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $splash_pages[$row->splash_page_id] = $row;
            }

            cache()->save(
                $cache_instance->set($splash_pages)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $splash_pages = $cache_instance->get();

        }

        return $splash_pages;

    }

    public function delete($splash_page_id) {

        if(!$splash_page = db()->where('splash_page_id', $splash_page_id)->getOne('splash_pages', ['user_id', 'splash_page_id', 'settings'])) {
            return;
        }

        $splash_page->settings = json_decode($splash_page->settings ?? '');

        /* Delete file */
        \Altum\Uploads::delete_uploaded_file($splash_page->settings->logo, 'splash_pages');

        /* Delete from database */
        db()->where('splash_page_id', $splash_page_id)->delete('splash_pages');

        /* Clear the cache */
        cache()->deleteItem('splash_pages?user_id=' . $splash_page->user_id);
    }

}
