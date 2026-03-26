<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/taxes') ?>"><?= l('admin_taxes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_taxes_import.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-paperclip text-primary-900 mr-2"></i> <?= l('admin_taxes_import.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form id="form" action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group" data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="file"><i class="fas fa-fw fa-sm fa-file-csv text-muted mr-1"></i> <?= l('admin_taxes_import.file') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_input.php', ['uploads_file_key' => 'taxes_csv', 'file_key' => 'file', 'already_existing_file' => null, 'is_required' => true]) ?>
                <?= \Altum\Alerts::output_field_error('file') ?>
                <small class="form-text text-muted"><?= l('admin_taxes_import.file_help') ?> <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('taxes_csv')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                <small class="form-text text-muted"><a href="<?= ASSETS_FULL_URL . 'csv/taxes_example.csv' ?>" download="taxes_example.csv" target="_blank"><?= l('admin_taxes_import.file_help2') ?></a></small>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4" data-is-ajax><?= l('global.submit') ?></button>

        </form>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    document.querySelector('#form').addEventListener('submit', event => {
        if(document.querySelector('#form').checkValidity()) {
            pause_submit_button(event.currentTarget);
        }
    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

