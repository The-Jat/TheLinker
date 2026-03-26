<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_video" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_video.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_video" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="video" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="video_file"><i class="fas fa-fw fa-video fa-sm text-muted mr-1"></i> <?= l('biolink_video.file') ?></label>
                        <input id="video_file" type="file" name="file" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['video']['whitelisted_file_extensions']) ?>" class="form-control-file altum-file-input" required="required" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['video']['whitelisted_file_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->video_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="video_poster_url"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('biolink_video.poster_url') ?></label>
                        <input id="video_poster_url" type="url" name="poster_url" maxlength="2048" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="video_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_link.name') ?></label>
                        <input id="video_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
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
