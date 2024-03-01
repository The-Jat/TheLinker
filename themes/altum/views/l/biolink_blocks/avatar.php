<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="d-flex flex-column align-items-center">
        <img src="<?= $data->link->settings->image ? \Altum\Uploads::get_full_url('avatars') . $data->link->settings->image : null ?>" alt="<?= l('link.biolink.image_alt') ?>" class="link-image <?= 'link-avatar-' . $data->link->settings->border_radius ?>" style="width: <?= $data->link->settings->size ?>px; height: <?= $data->link->settings->size ?>px; border-width: <?= $data->link->settings->border_width ?>px; border-color: <?= $data->link->settings->border_color ?>; border-style: <?= $data->link->settings->border_style ?>; object-fit: <?= $data->link->settings->object_fit ?>;" data-border-width data-border-avatar-radius data-border-style data-border-color data-avatar />
    </div>
</div>
