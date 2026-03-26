<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_header" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_header.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_header" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="header" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="header_background_type"><i class="fas fa-fw fa-sm fa-images text-muted mr-1"></i> <?= l('biolink_header.background_type') ?></label>
                        <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                            <div class="p-2 col-12 col-lg-4">
                                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate active">
                                    <input type="radio" name="background_type" value="image" class="custom-control-input" checked="checked" required="required" />
                                    <i class="fas fa-fill fa-fw fa-sm mr-1"></i> <?= l('global.image') ?>
                                </label>
                            </div>

                            <div class="p-2 col-12 col-lg-4">
                                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                    <input type="radio" name="background_type" value="video" class="custom-control-input" required="required" />
                                    <i class="fab fa-youtube fa-fw fa-sm mr-1"></i> <?= l('biolink_header.video') ?>
                                </label>
                            </div>

                            <div class="p-2 col-12 col-lg-4">
                                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                    <input type="radio" name="background_type" value="video_file" class="custom-control-input" required="required" />
                                    <i class="fas fa-video fa-fw fa-sm mr-1"></i> <?= l('biolink_video.file') ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div data-header-create-background-type="image" class="form-group">
                        <label for="header_background"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('biolink_header.background') ?></label>
                        <input id="header_background" type="file" name="background" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" data-crop />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
                    </div>

                    <div data-header-create-background-type="video" class="form-group">
                        <label for="header_video_url"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('biolink_header.video_url') ?></label>
                        <input id="header_video_url" type="text" class="form-control" name="video_url" value="" maxlength="2048" placeholder="<?= l('biolink_header.video_url_placeholder') ?>" required="required" />
                    </div>

                    <div data-header-create-background-type="video_file" class="form-group">
                        <label for="header_video_file"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('biolink_video.file') ?></label>
                        <input id="header_video_file" type="file" name="video_file" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_video_extensions']) ?>" class="form-control-file altum-file-input" required="required" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_video_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->video_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="header_avatar"><i class="fas fa-fw fa-portrait fa-sm text-muted mr-1"></i> <?= l('biolink_header.avatar') ?></label>
                        <input id="header_avatar" type="file" name="avatar" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" data-crop data-aspect-ratio="1" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['header']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
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
<script>
    'use strict';

type_handler('form[name="create_biolink_header"] input[name="background_type"]', 'data-header-create-background-type');
    document.querySelector('form[name="create_biolink_header"] input[name="background_type"]') && document.querySelectorAll('form[name="create_biolink_header"] input[name="background_type"]').forEach(element => element.addEventListener('change', () => { type_handler('form[name="create_biolink_header"] input[name="background_type"]', 'data-header-create-background-type');}));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
