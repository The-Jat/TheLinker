<?php defined('ALTUMCODE') || die() ?>

<div class="col-12 col-lg-6 p-3 position-relative" data-tool-id="<?= $data->tool_id ?>" data-tool-name="<?= l('tools.' . $data->tool_id . '.name') ?>" data-tool-category="<?= $data->tool_category ?? '' ?>">
    <div class="card d-flex flex-row h-100 overflow-hidden">
        <div class="tool-icon-wrapper d-flex flex-column justify-content-center">
            <div class="bg-primary-100 d-flex align-items-center justify-content-center rounded tool-icon">
                <i class="<?= $data->tool_icon ?> fa-fw text-primary-600"></i>
            </div>
        </div>

        <div class="card-body text-truncate">
            <a href="<?= url('tools/' . str_replace('_', '-', $data->tool_id)) ?>" class="stretched-link text-decoration-none text-dark">
                <strong><?= $data->name ?? l('tools.' . $data->tool_id . '.name') ?></strong>
            </a>
            <p class="text-truncate text-muted small m-0"><?= $data->description ?? l('tools.' . $data->tool_id . '.description') ?></p>
        </div>

        <?php if(settings()->tools->views_is_enabled || settings()->tools->last_submissions_is_enabled): ?>
            <div class="p-3 d-flex flex-column">
                <?php if(settings()->tools->views_is_enabled): ?>
                    <div class="badge badge-gray-100 mb-2" data-toggle="tooltip" title="<?= l('tools.total_views') ?>">
                        <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= nr($data->tools_usage[$data->tool_id]->total_views ?? 0) ?>
                    </div>
                <?php endif ?>

                <?php if(settings()->tools->last_submissions_is_enabled): ?>
                    <div class="badge badge-gray-100" data-toggle="tooltip" title="<?= l('tools.total_submissions') ?>">
                        <i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= nr($data->tools_usage[$data->tool_id]->total_submissions ?? 0) ?>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>
</div>
