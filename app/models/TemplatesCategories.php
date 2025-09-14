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

class TemplatesCategories extends Model {

    public function get_templates_categories() {

        /* Get the user projects */
        $templates_categories = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('templates_categories');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $templates_categories_result = database()->query("SELECT * FROM `templates_categories`  WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $templates_categories_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $templates_categories[$row->template_category_id] = $row;
            }

            cache()->save(
                $cache_instance->set($templates_categories)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $templates_categories = $cache_instance->get();

        }

        return $templates_categories;

    }

}
