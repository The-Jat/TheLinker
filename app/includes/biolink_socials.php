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

defined('ALTUMCODE') || die();

return [
    'email' => [
        'format' => 'mailto:%s',
        'input_group' => null,
        'max_length' => 320,
        'icon' => 'fas fa-envelope'
    ],
    'tel'=> [
        'format' => 'tel: %s',
        'input_group' => null,
        'max_length' => 32,
        'icon' => 'fas fa-phone-square-alt'
    ],
    'whatsapp'=> [
        'format' => 'https://wa.me/%s',
        'input_group' => null,
        'max_length' => 32,
        'icon' => 'fab fa-whatsapp'
    ],
    'facebook'=> [
        'format' => 'https://facebook.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-facebook'
    ],
    'instagram'=> [
        'format' => 'https://instagram.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-instagram'
    ],
    'instagram_dm'=> [
        'format' => 'https://ig.me/m/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-instagram'
    ],
    'twitter'=> [
        'format' => 'https://x.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-x-twitter'
    ],
    'telegram'=> [
        'format' => 'https://t.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-telegram'
    ],
    'linkedin'=> [
        'format' => 'https://linkedin.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-linkedin'
    ],
    'youtube'=> [
        'format' => 'https://youtube.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-youtube'
    ],
    'tiktok'=> [
        'format' => 'https://tiktok.com/@%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-tiktok'
    ],
    'discord' => [
        'format' => 'https://discord.gg/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-discord'
    ],
    'facebook-messenger'=> [
        'format' => 'https://m.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-facebook-messenger'
    ],
    'snapchat' => [
        'format' => 'https://snapchat.com/add/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-snapchat'
    ],
    'threads' => [
        'format' => 'https://threads.com/@%s',
        'input_group' => true,
        'max_length' => 64,
        'icon' => 'fab fa-threads',
    ],
    'pinterest' => [
        'format' => 'https://pinterest.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-pinterest'
    ],
    'twitch' => [
        'format' => 'https://twitch.tv/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-twitch'
    ],
    'reddit' => [
        'format' => 'https://reddit.com/%s',
        'input_group' => true,
        'max_length' => 64,
        'icon' => 'fab fa-reddit',
    ],
    'soundcloud'=> [
        'format' => 'https://soundcloud.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-soundcloud'
    ],
    'spotify' => [
        'format' => 'https://open.spotify.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-spotify'
    ],
    'skype'=> [
        'format' => 'https://join.skype.com/invite/%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fab fa-skype'
    ],
    'signal'=> [
        'format' => 'https://signal.me/%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fas fa-comment'
    ],
    'onlyfans'=> [
        'format' => 'https://onlyfans.com/%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fas fa-lock'
    ],
    'whatsapp_channel'=> [
        'format' => 'https://www.whatsapp.com/channel/%s',
        'input_group' => null,
        'max_length' => 64,
        'icon' => 'fab fa-whatsapp'
    ],
    'bluesky'=> [
        'format' => 'https://bsky.app/profile/%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fab fa-bluesky'
    ],
    'mastodon'=> [
        'format' => 'https://mastodon.social/@%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fab fa-mastodon'
    ],
    'rumble' => [
        'format' => 'https://rumble.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fas fa-play-circle',
    ],
    'vk'=> [
        'format' => 'https://vk.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-vk'
    ],
    'address' => [
        'format' => 'https://maps.google.com/maps?q=%s',
        'input_group' => false,
        'max_length' => 256,
        'icon' => 'fas fa-map-marker-alt'
    ],
];
