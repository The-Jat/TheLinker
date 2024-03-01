<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Link extends Model {

    public function delete($link_id) {

        if(!$link = db()->where('link_id', $link_id)->getOne('links', ['user_id', 'link_id', 'type', 'settings'])) {
            return;
        }

        /* Process to delete the stored files of the vcard avatar */
        if($link->type == 'vcard') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->vcard_avatar, 'avatars');
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'file') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->file, 'files');
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'biolink') {
            $link->settings = json_decode($link->settings ?? '');

            \Altum\Uploads::delete_uploaded_file($link->settings->image, 'favicons');
            \Altum\Uploads::delete_uploaded_file($link->settings->seo->image, 'block_images');
            if($link->settings->background_type == 'image') {
                \Altum\Uploads::delete_uploaded_file($link->settings->background, 'backgrounds');
            }

            /* Get all the available biolink blocks and iterate over them to delete the stored images */
            $result = database()->query("SELECT `biolink_block_id` FROM `biolinks_blocks` WHERE `link_id` = {$link->link_id}");
            while($row = $result->fetch_object()) {

                (new \Altum\Models\BiolinkBlock())->delete($row->biolink_block_id);

            }
        }

        /* Process to delete the stored files of the link */
        if($link->type == 'static') {
            $link->settings = json_decode($link->settings ?? '');

            /* Clear the already existing folder and contents */
            remove_directory_and_contents(\Altum\Uploads::get_full_path('static') . $link->settings->static_folder);
        }

        /* Delete from database */
        db()->where('link_id', $link_id)->delete('links');

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItem($link->type . '_links_total?user_id=' . $link->user_id);
        \Altum\Cache::$adapter->deleteItem('links_total?user_id=' . $link->user_id);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItem('link?link_id=' . $link->link_id);
        \Altum\Cache::$adapter->deleteItemsByTag('link_id=' . $link->link_id);

    }
}
