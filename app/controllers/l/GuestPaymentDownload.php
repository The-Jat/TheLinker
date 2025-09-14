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

defined('ALTUMCODE') || die();

class GuestPaymentDownload extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            redirect();
        }

        $_GET['guest_payment_id'] = (int) $_GET['guest_payment_id'];
        $_GET['key'] = input_clean($_GET['key']);

        /* Make sure the transaction exists */
        if(!$guest_payment = db()->where('guest_payment_id', $_GET['guest_payment_id'])->getOne('guests_payments')) {
            redirect();
        }

        /* Make sure the key is correct */
        if(md5($guest_payment->payment_id) != $_GET['key']) {
            redirect();
        }

        /* Get the biolink block */
        if(!$biolink_block = db()->where('biolink_block_id', $guest_payment->biolink_block_id)->getOne('biolinks_blocks')) {
            redirect();
        }
        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        if(!$biolink_block->settings->file) {
            redirect();
        }

        /* Download the file */
        $file_url = \Altum\Uploads::get_full_url('products_files') . $biolink_block->settings->file;
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($file_url) . '"');
        readfile($file_url);
    }

}
