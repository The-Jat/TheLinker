<?php defined('ALTUMCODE') || die() ?>

<div class="col-12 col-md-6 col-lg-4 p-3 position-relative" data-tool-id="<?= $data->tool_id ?>" data-tool-name="<?= l('tools.' . $data->tool_id . '.name') ?>" data-tool-category="<?= $data->tool_category ?? '' ?>">
    <div class="card d-flex flex-column h-100 overflow-hidden">
        <div class="card-body text-truncate">
            <div class="bg-primary-100 d-flex align-items-center justify-content-center rounded tool-icon mb-2">
                <i class="<?= $data->tool_icon ?> fa-fw text-primary-600"></i>
            </div>

            <a href="<?= url('tools/' . str_replace('_', '-', $data->tool_id)) ?>" class="stretched-link text-decoration-none text-dark">
                <strong><?= $data->name ?? l('tools.' . $data->tool_id . '.name') ?></strong>
            </a>
            <p class="text-truncate text-muted small m-0"><?= $data->description ?? l('tools.' . $data->tool_id . '.description') ?></p>

            <?php if(settings()->tools->views_is_enabled || settings()->tools->submissions_is_enabled): ?>
                <div class="mt-3 row">
                    <?php if(settings()->tools->views_is_enabled): ?>
                    <div class="col px-3">
                        <div class="badge badge-gray-100 w-100" data-toggle="tooltip" title="<?= l('tools.total_views') ?>">
                            <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= nr($data->tools_usage[$data->tool_id]->total_views ?? 0) ?>
                        </div>
                    </div>
                    <?php endif ?>

                    <?php if(settings()->tools->submissions_is_enabled): ?>
                    <div class="col px-3">
                        <div class="badge badge-gray-100 w-100" data-toggle="tooltip" title="<?= l('tools.total_submissions') ?>">
                            <i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= nr($data->tools_usage[$data->tool_id]->total_submissions ?? 0) ?>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
