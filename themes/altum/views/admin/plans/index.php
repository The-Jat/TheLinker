<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0 text-truncate"><i class="fas fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= l('admin_plans.header') ?></h1>

    <div class="d-flex position-relative">
        <div>
            <a href="<?= url('admin/plan-create') ?>" class="btn btn-primary text-nowrap"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_plans.create') ?></a>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-gray-300 dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/plans?export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->json ? null : get_plan_feature_disabled_info() ?>>
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->pdf ? 'onclick="event.preventDefault(); window.print();"' : get_plan_feature_disabled_info() ?>>
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= l('admin_plans.table.name') ?></th>
            <th><?= l('admin_plans.table.price') ?></th>
            <th><?= l('admin_plans.table.users') ?></th>
            <th><?= l('global.status') ?></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset(settings()->plan_guest)): ?>
            <tr>
                <td class="text-nowrap">
                    <a href="<?= url('admin/plan-update/guest') ?>"><?= settings()->plan_guest->name ?></a>
                    <a href="<?= url('pay/guest') ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                </td>
                <td class="text-nowrap"><?= settings()->plan_guest->price ?></td>
                <td class="text-nowrap">-</td>
                <td class="text-nowrap">
                    <?php if(settings()->plan_guest->status == 0): ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                    <?php elseif(settings()->plan_guest->status == 1): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                    <?php else: ?>
                        <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap">
                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />' . l('global.na') ?>">
                        <i class="fas fa-fw fa-sort text-muted"></i>
                    </span>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />' . l('global.na'))) ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex justify-content-end">
                        <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'guest']) ?>
                    </div>
                </td>
            </tr>
        <?php endif ?>

        <tr>
            <td class="text-nowrap">
                <a href="<?= url('admin/plan-update/free') ?>"><?= settings()->plan_free->name ?></a>
                <a href="<?= url('pay/free') ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
            </td>
            <td class="text-nowrap"><?= settings()->plan_free->price ?></td>
            <td class="text-nowrap">
                <a href="<?= url('admin/users?plan_id=free') ?>" class="badge badge-light">
                    <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                    <?= nr($data->users_plans['free'] ?? 0) ?>
                    &#x2022;
                    <?= nr(get_percentage_between_two_numbers($data->users_plans['free'] ?? 0, $data->total_users)) . '%' ?>
                </a>
            </td>
            <td class="text-nowrap">
                <?php if(settings()->plan_free->status == 0): ?>
                    <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                <?php elseif(settings()->plan_free->status == 1): ?>
                    <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                <?php else: ?>
                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                <?php endif ?>
            </td>

            <td class="text-nowrap">
                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />' . l('global.na') ?>">
                    <i class="fas fa-fw fa-sort text-muted"></i>
                </span>

                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />' . l('global.na'))) ?>">
                    <i class="fas fa-fw fa-calendar text-muted"></i>
                </span>
            </td>

            <td class="text-nowrap">
                <div class="d-flex justify-content-end">
                    <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'free']) ?>
                </div>
            </td>
        </tr>

        <tr>
            <td class="text-nowrap">
                <a href="<?= url('admin/plan-update/custom') ?>"><?= settings()->plan_custom->name ?></a>
                <span data-toggle="tooltip" title="<?= l('admin_plans.table.custom_help') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-muted ml-1"></i></span>
            </td>
            <td class="text-nowrap"><?= settings()->plan_custom->price ?></td>
            <td class="text-nowrap">
                <a href="<?= url('admin/users?plan_id=custom') ?>" class="badge badge-light">
                    <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                    <?= nr($data->users_plans['custom'] ?? 0) ?>
                    &#x2022;
                    <?= nr(get_percentage_between_two_numbers($data->users_plans['custom'] ?? 0, $data->total_users)) . '%' ?>
                </a>
            </td>
            <td class="text-nowrap">
                <?php if(settings()->plan_custom->status == 0): ?>
                    <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                <?php elseif(settings()->plan_custom->status == 1): ?>
                    <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                <?php else: ?>
                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                <?php endif ?>
            </td>

            <td class="text-nowrap">
                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />' . l('global.na') ?>">
                    <i class="fas fa-fw fa-sort text-muted"></i>
                </span>

                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />' . l('global.na'))) ?>">
                    <i class="fas fa-fw fa-calendar text-muted"></i>
                </span>
            </td>

            <td class="text-nowrap">
                <div class="d-flex justify-content-end">
                    <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'custom']) ?>
                </div>
            </td>
        </tr>

        <?php foreach($data->plans as $row): ?>
            <?php
            $tooltips = [];

            foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                foreach(['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'] as $payment_frequency) {
                    if(isset($tooltips[$payment_frequency])) {
                        $tooltips[$payment_frequency] .= $row->prices->{$payment_frequency}->{$currency} . ' ' . $currency . '<br />';
                    } else {
                        $tooltips[$payment_frequency] = $row->prices->{$payment_frequency}->{$currency} . ' ' . $currency . '<br />';
                    }
                }
            }
            ?>

            <tr>
                <td class="text-nowrap">
                    <a href="<?= url('admin/plan-update/' . $row->plan_id) ?>"><?= $row->name ?></a>
                    <?php if($row->status != 0): ?>
                        <a href="<?= url('pay/' . $row->plan_id) ?>" target="_blank" rel="noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                    <?php endif ?>
                </td>
                <td class="text-nowrap">
                    <div class="d-flex flex-column text-muted small">
                        <?php foreach(['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'] as $payment_frequency): ?>
                            <div>
                                <span data-toggle="tooltip" data-html="true" title="<?= $tooltips[$payment_frequency] ?>">
                                    <?= $row->prices->{$payment_frequency}->{settings()->payment->default_currency} . ' ' . settings()->payment->default_currency ?> &#x2022; <?= l('plan.custom_plan.' . $payment_frequency) ?>
                                </span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </td>
                <td class="text-nowrap">
                    <a href="<?= url('admin/users?plan_id=' . $row->plan_id) ?>" class="badge badge-light">
                        <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                        <?= nr($data->users_plans[$row->plan_id] ?? 0) ?>
                        &#x2022;
                        <?= nr(get_percentage_between_two_numbers($data->users_plans[$row->plan_id] ?? 0, $data->total_users)) . '%' ?>
                    </a>
                </td>
                <td class="text-nowrap">
                    <?php if($row->status == 0): ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                    <?php elseif($row->status == 1): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                    <?php else: ?>
                        <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-muted">
                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />' . $row->order ?>">
                        <i class="fas fa-fw fa-sort text-muted"></i>
                    </span>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex justify-content-end">
                        <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => $row->plan_id]) ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
