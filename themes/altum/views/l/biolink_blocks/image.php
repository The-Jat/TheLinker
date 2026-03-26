<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" data-biolink-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">

    <?php if($data->link->location_url): ?>
    <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-biolink-block-id="<?= $data->link->biolink_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="<?= ($data->biolink->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->biolink->settings->hover_animation ?? 'smooth') : null ?>">
    <?php endif ?>

        <img
                src="<?= \Altum\Uploads::get_full_url('block_images') . $data->link->settings->image ?>"
                class="w-100 h-auto <?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') . ' large' ?> <?= ($data->biolink->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->biolink->settings->hover_animation ?? 'smooth') : null ?>"
                alt="<?= $data->link->settings->image_alt ?>"
                loading="lazy"
                style="border-width: <?= $data->link->settings->border_width ?>px; border-color: <?= $data->link->settings->border_color ?>; border-style: <?= $data->link->settings->border_style ?>; <?= \Altum\Link::get_processed_box_shadow_style($data->link->settings) ?>"
                data-border-width data-border-radius data-border-style data-border-color data-border-shadow
        />

    <?php if($data->link->location_url): ?>
    </a>
    <?php endif ?>

</div>

