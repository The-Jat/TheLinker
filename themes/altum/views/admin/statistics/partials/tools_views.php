<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 mb-4"><i class="fas fa-fw fa-eye fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.tools.total_views') ?></h2>

            <div>
                <span class="badge <?= $data->total['views'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.tools.total_views') ?>">
                    <?= nr($data->total['views']) ?>
                </span>
            </div>
        </div>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.tools.tool') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.tools.total_views') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if($data->total['views']): ?>
                    <?php foreach ($data->tools_total_views as $tool => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('tools.' . $tool . '.name', null, true) ?? $tool ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['views'] * 100, 2) . '%'; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
