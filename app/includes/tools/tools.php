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

return array_merge(
    require APP_PATH . 'includes/tools/checker_tools.php',
    require APP_PATH . 'includes/tools/text_tools.php',
    require APP_PATH . 'includes/tools/converter_tools.php',
    require APP_PATH . 'includes/tools/generator_tools.php',
    require APP_PATH . 'includes/tools/developer_tools.php',
    require APP_PATH . 'includes/tools/image_manipulation_tools.php',
    require APP_PATH . 'includes/tools/misc_tools.php',
    require APP_PATH . 'includes/tools/time_converter_tools.php',
);

