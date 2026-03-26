<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_faq" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_faq.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_faq" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="faq" />

                    <div class="notification-container"></div>

                    <div id="<?= 'faq_items_create' ?>" data-biolink-block-id="create"></div>

                    <div class="mb-3">
                        <button data-add="faq_item" data-biolink-block-id="create" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
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

<template id="template_faq_item">
    <div class="mb-4 p-3 bg-gray-50 rounded">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for=""><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_faq.title') ?></label>

                <div>
                    <span class="custom-row-side-controller-grab drag" data-toggle="tooltip" title="<?= l('global.drag_and_drop') ?>">
                        <i class="fas fa-fw fa-sm fa-bars text-muted"></i>
                    </span>
                </div>
            </div>
            <input id="" type="text" name="item_title[]" class="form-control" value="" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('biolink_faq.content') ?></label>
            <textarea id="" name="item_content[]" class="form-control" required="required"></textarea>
        </div>

        <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
    <script>
        'use strict';

        $('#create_biolink_faq').on('shown.bs.modal', event => {
            $(event.currentTarget).find('button[data-add]').click();
        })
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* FAQ Script */
    'use strict';

    /* add new */
    let faq_item_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_faq_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="faq_items_${biolink_block_id}"] .mb-4`).length;

        if(count >= 100) return;

        clone.querySelector(`input[name="item_title[]"`).setAttribute('name', `item_title[${count}]`);
        clone.querySelector(`textarea[name="item_content[]"`).setAttribute('name', `item_content[${count}]`);

        document.querySelector(`[id="faq_items_${biolink_block_id}"]`).appendChild(clone);

        faq_item_remove_initiator();
    };

    document.querySelectorAll('[data-add="faq_item"]').forEach(element => {
        element.addEventListener('click', faq_item_add);
    })

    /* remove */
    let faq_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let faq_item_remove_initiator = () => {
        document.querySelectorAll('[id^="faq_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', faq_item_remove);
            element.addEventListener('click', faq_item_remove)
        })
    };

    faq_item_remove_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'faq_block') ?>
