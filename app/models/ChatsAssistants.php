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

namespace Altum\models;

defined('ALTUMCODE') || die();

class ChatsAssistants extends Model {

    public function get_chats_assistants() {

        /* Get the user projects */
        $chats_assistants = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('chats_assistants');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $chats_assistants_result = database()->query("SELECT * FROM `chats_assistants` WHERE `is_enabled` = 1 ORDER BY `order`");
            while($row = $chats_assistants_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $chats_assistants[$row->chat_assistant_id] = $row;
            }

            cache()->save(
                $cache_instance->set($chats_assistants)->expiresAfter(CACHE_DEFAULT_SECONDS)
            );

        } else {

            /* Get cache */
            $chats_assistants = $cache_instance->get();

        }

        return $chats_assistants;

    }

    public function delete($chat_assistant_id) {

        $chat_assistant = db()->where('chat_assistant_id', $chat_assistant_id)->getOne('chats_assistants', ['chat_assistant_id', 'image']);

        if(!$chat_assistant) return;

        \Altum\Uploads::delete_uploaded_file($chat_assistant->image, 'chats_assistants');

        /* Delete the resource */
        db()->where('chat_assistant_id', $chat_assistant_id)->delete('chats_assistants');

        /* Clear the cache */
        cache()->deleteItemsByTag('chats_assistants');

    }

}
