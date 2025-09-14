<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_modal_text" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_modal_text.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_modal_text" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="modal_text" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="modal_text_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_link.name') ?></label>
                        <input id="modal_text_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="modal_text_text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('biolink_link.text') ?></label>
                        <textarea id="modal_text_text" class="form-control quilljs" name="text" maxlength="10000"></textarea>
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


<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/quill.snow.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head', 'quilljs') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/quill.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';
    
/* find all textareas with the specific class */
    const textarea_elements = document.querySelectorAll('textarea.quilljs');

    textarea_elements.forEach(textarea_element => {
        /* hide the original textarea */
        textarea_element.style.display = 'none';

        /* create a div for quill editor */
        const quill_container = document.createElement('div');

        /* apply default height and resizable style */
        //quill_container.style.minHeight = '250px';
        quill_container.style.resize = 'vertical';
        quill_container.style.overflow = 'auto';

        textarea_element.parentNode.insertBefore(quill_container, textarea_element.nextSibling);

        /* initialize quill editor */
        const quill_editor = new Quill(quill_container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ "size": ["small", false] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ "color": [] }, { "background": [] }],
                    [{ 'align': [] }],
                    ["link"],
                    ["clean"]
                ]
            }
        });

        /* set initial value if textarea has content */
        quill_editor.root.innerHTML = textarea_element.value;

        /* sync quill content to textarea on form submit */
        textarea_element.closest('form').addEventListener('submit', function () {
            textarea_element.value = quill_editor.root.innerHTML;
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'quilljs') ?>
