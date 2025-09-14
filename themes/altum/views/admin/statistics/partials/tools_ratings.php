<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 mb-4"><i class="fas fa-fw fa-star fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.tools.ratings') ?></h2>

            <div>
                <span class="badge <?= $data->total['ratings'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.tools.ratings') ?>">
                    <?= nr($data->total['ratings']) ?>
                </span>
                <span class="badge <?= $data->total['average_rating'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.tools.average_rating') ?>">
                    <?= nr($data->total['average_rating'], 2, false) ?>
                </span>
            </div>
        </div>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.tools.tool') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.tools.ratings') ?></th>
                    <th><?= l('admin_statistics.tools.average_rating') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if($data->total['ratings']): ?>
                    <?php foreach ($data->tools_total_ratings as $tool => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('tools.' . $tool . '.name') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['ratings'] * 100, 2) . '%'; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($data->tools_average_rating[$tool], 2, false) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript, 'has_datepicker' => false] ?>
