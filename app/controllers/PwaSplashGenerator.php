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

defined('ALTUMCODE') || die();

class PwaSplashGenerator extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('pwa') || !settings()->pwa->is_enabled) {
            throw_404();
        }

        /* Input params */
        $color = !isset($_GET['color']) || !verify_hex_color('#' . $_GET['color']) ? '#ffffff' : $_GET['color'];
        $size = isset($_GET['size']) ? $_GET['size'] : '1170x2532';
        $icon_name = basename($_GET['icon'] ?? '');

        /* Validate and prepare paths */
        if(!preg_match('/^(\d+)x(\d+)$/', $size, $matches)) {
            http_response_code(400);
            die('Invalid size format');
        }

        $width = (int) $matches[1];
        $height = (int) $matches[2];

        /* Safely resolve local icon */
        $icon_path = \Altum\Uploads::get_full_path('app_icon') . $icon_name;
        if(!file_exists($icon_path) || !is_file($icon_path)) {
            http_response_code(404);
            die('Icon not found');
        }

        /* Convert hex to RGB */
        $rgb = hex_to_rgb($color);

        /* Create splash background (solid color, with alpha support) */
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        $background_color = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        imagefill($image, 0, 0, $background_color);

        /* Load PNG icon with transparency */
        $icon = @imagecreatefrompng($icon_path);
        if(!$icon) {
            http_response_code(500);
            die('Invalid PNG icon');
        }

        imagealphablending($icon, true);
        imagesavealpha($icon, true);

        /* Resize icon proportionally with transparency */
        $icon_width = imagesx($icon);
        $icon_height = imagesy($icon);
        $target_icon_height = (int) round($height * 0.3);
        $scale = $target_icon_height / $icon_height;
        $target_icon_width = (int) round($icon_width * $scale);

        $resized_icon = imagecreatetruecolor($target_icon_width, $target_icon_height);
        imagealphablending($resized_icon, false);
        imagesavealpha($resized_icon, true);
        $transparent = imagecolorallocatealpha($resized_icon, 0, 0, 0, 127);
        imagefill($resized_icon, 0, 0, $transparent);

        imagecopyresampled(
            $resized_icon,
            $icon,
            0, 0, 0, 0,
            $target_icon_width,
            $target_icon_height,
            $icon_width,
            $icon_height
        );

        /* Center the icon */
        $x = (int) round(($width - $target_icon_width) / 2);
        $y = (int) round(($height - $target_icon_height) / 2);

        /* Draw the icon onto the splash */
        imagecopy($image, $resized_icon, $x, $y, 0, 0, $target_icon_width, $target_icon_height);

        /* Output */
        header('Content-Type: image/png');
        header('Cache-Control: public, max-age=0');
        imagepng($image);

        /* Cleanup */
        imagedestroy($image);
        imagedestroy($resized_icon);
        imagedestroy($icon);
    }

}
