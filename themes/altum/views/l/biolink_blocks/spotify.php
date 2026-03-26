<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" data-biolink-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">

<?php if(in_array($data->embed_type, ['show', 'episode'])): ?>
        <iframe src="https://open.spotify.com/embed/<?= $data->embed_type ?>/<?= $data->embed_value ?>?theme=0" class="<?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') . ' large' ?>" width="100%" height="232" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
<?php elseif(in_array($data->embed_type, ['track', 'album', 'playlist'])): ?>
    <div class="<?= 'link-btn-' . ($data->link->settings->border_radius ?? 'rounded') . ' large' ?>" <?= $data->embed_type == 'track' ? 'style="height: 80px;"' : 'style="height: 380px;"' ?>>
        <iframe  scrolling="no" frameborder="no" src="https://open.spotify.com/embed/<?= $data->embed_type ?>/<?= $data->embed_value ?>" width="100%" <?= $data->embed_type == 'track' ? 'height="80"' : 'height="380"' ?> allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
    </div>
<?php endif ?>

</div>


