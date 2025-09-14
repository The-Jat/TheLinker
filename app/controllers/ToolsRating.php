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

use Altum\Response;

defined('ALTUMCODE') || die();

class ToolsRating extends Controller {

    public function index() {

        if(empty($_POST)) {
            redirect();
        }

        if(!settings()->tools->is_enabled) {
            redirect('not-found');
        }

        if(settings()->tools->access == 'users') {
            \Altum\Authentication::guard();
        }

        $tool_id = input_clean($_POST['tool_id'], 64);
        $_POST['rating'] = isset($_POST['rating']) && in_array($_POST['rating'], range(1,5)) ? (int) $_POST['rating'] : 5;

        /* Check for any errors */
        $required_fields = ['tool_id', 'rating'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        $ip = get_ip();
        $ip_binary = $ip ? inet_pton($ip) : null;

        /* Check if rating exists for this tool & IP */
        $existing_rating = db()->where('tool_id', $tool_id)->where('ip_binary', $ip_binary)->getOne('tools_ratings', ['rating']);

        /* Tool usage */
        $tool_usage = db()->where('tool_id', $tool_id)->getOne('tools_usage');

        /* Current stats */
        if($tool_usage) {
            $current_total_score = $tool_usage->total_ratings * $tool_usage->average_rating;

            /* Update rating */
            if($existing_rating) {
                $old_rating = $existing_rating->rating;
                $difference = $_POST['rating'] - $old_rating;
                $new_total_ratings = $tool_usage->total_ratings;
            } else {
                $difference = $_POST['rating'];
                $new_total_ratings = $tool_usage->total_ratings + 1;
            }

            $new_total_score = $current_total_score + $difference;
            $new_average_rating = number_format($new_total_score / $new_total_ratings, 2, '.', '');

            /* Update tool usage stats */
            db()->where('tool_id', $tool_id)->update('tools_usage', [
                'total_ratings' => $new_total_ratings,
                'average_rating' => $new_average_rating
            ]);
        }

        else {
            $new_total_ratings = 1;
            $new_average_rating = number_format($_POST['rating'], 2, '.', '');

            /* Insert new tool usage */
            db()->insert('tools_usage', [
                'tool_id' => $tool_id,
                'total_views' => 0,
                'total_submissions' => 0,
                'total_ratings' => $new_total_ratings,
                'average_ratings' => $new_average_rating,
                'data' => json_encode([]),
            ]);
        }


        /* Insert or update rating */
        if($existing_rating) {
            db()->where('tool_id', $tool_id)->where('ip_binary', $ip_binary)->update('tools_ratings', [
                'user_id' => is_logged_in() ? user()->user_id : null,
                'rating' => $_POST['rating'],
                'datetime' => get_date()
            ]);
        } else {
            db()->insert('tools_ratings', [
                'user_id' => is_logged_in() ? user()->user_id : null,
                'tool_id' => $tool_id,
                'ip_binary' => $ip_binary,
                'rating' => $_POST['rating'],
                'datetime' => get_date()
            ]);
        }

        /* Clear the cache */
        cache()->deleteItem('tools_usage');

        /* Set a nice success message */
        Response::json('', 'success', ['new_total_ratings' => $new_total_ratings, 'new_average_rating' => nr($new_average_rating, 2, false)]);

    }

}
