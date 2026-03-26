<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_image_slider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_image_slider.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_image_slider" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="image_slider" />

                    <div class="notification-container"></div>

                    <div id="<?= 'image_slider_items_create' ?>" data-biolink-block-id="create"></div>

                    <div class="mb-3">
                        <button data-add="image_slider_item" data-biolink-block-id="create" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>

                    <p class="small text-muted"><i class="fas fa-fw fa-sm fa-circle-info mr-1"></i> <?= l('link.create_info') ?></p>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('link.biolink.create_block') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="template_image_slider_item">
    <div class="mb-4 p-3 bg-gray-50 rounded">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="<?= 'item_image_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>

                <div>
                    <span class="custom-row-side-controller-grab drag" data-toggle="tooltip" title="<?= l('global.drag_and_drop') ?>">
                        <i class="fas fa-fw fa-sm fa-bars text-muted"></i>
                    </span>
                </div>
            </div>
            <input id="" type="file" name="item_image_" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_slider']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" data-crop data-aspect-ratio="1" />
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_slider']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('biolink_link.image_alt') ?></label>
            <input id="" type="text" class="form-control" name="item_image_alt[]" maxlength="100" />
            <small class="form-text text-muted"><?= l('biolink_link.image_alt_help') ?></small>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('biolink_link.location_url') ?></label>
            <input id="" type="url" class="form-control" name="item_location_url[]" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <button type="button" data-remove="item" class="btn btn-block btn-sm btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

$('#create_biolink_image_slider').on('shown.bs.modal', event => {
        $(event.currentTarget).find('button[data-add]').click();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Image slider Script */
    'use strict';

    /* add new */
    let image_slider_item_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_image_slider_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="image_slider_items_${biolink_block_id}"] .mb-4`).length;

        if(count >= 25) return;

        clone.querySelector(`input[name="item_image_"`).setAttribute('name', `item_image_${count}`);
        clone.querySelector(`input[name="item_image_alt[]"`).setAttribute('name', `item_image_alt[${count}]`);
        clone.querySelector(`input[name="item_location_url[]"`).setAttribute('name', `item_location_url[${count}]`);

        document.querySelector(`[id="image_slider_items_${biolink_block_id}"]`).appendChild(clone);

        image_slider_item_remove_initiator();
        initialize_image_cropper();
    };

    document.querySelectorAll('[data-add="image_slider_item"]').forEach(element => {
        element.addEventListener('click', image_slider_item_add);
    })

    /* remove */
    let image_slider_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
        initialize_image_cropper();
    };

    let image_slider_item_remove_initiator = () => {
        document.querySelectorAll('[id^="image_slider_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', image_slider_item_remove);
            element.addEventListener('click', image_slider_item_remove)
        })
    };

    image_slider_item_remove_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'image_slider_block') ?>
