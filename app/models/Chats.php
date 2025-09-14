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

class Chats extends Model {

    public function delete($chat_id) {

        if(!$chat = db()->where('chat_id', $chat_id)->getOne('chats', ['user_id', 'chat_id',])) {
            return;
        }

        $result = database()->query("SELECT `image` FROM `chats_messages` WHERE `chat_id` = {$chat->chat_id}");
        while($row = $result->fetch_object()) {
            \Altum\Uploads::delete_uploaded_file($row->image, 'chats_images');
        }

        /* Delete from database */
        db()->where('chat_id', $chat_id)->delete('chats');
    }

}
