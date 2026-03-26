<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="row mb-3">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('account_payments.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('account_payments.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex flex-wrap gap-3 d-print-none">
            <div>
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= !empty($data->payments) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('account-payments?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->csv ? null : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('account-payments?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->json ? null : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->pdf ? 'onclick="event.preventDefault(); window.print();"' : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div>
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= !empty($data->payments) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip data-html="true" title="<?= l('global.filters.tooltip') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="processor" class="small"><?= l('account_payments.processor') ?></label>
                                <select name="processor" id="processor" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach($data->payment_processors as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= isset($data->filters->filters['processor']) && $data->filters->filters['processor'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_status" class="small"><?= l('global.status') ?></label>
                                <select name="status" id="filters_status" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="paid" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'paid' ? 'selected="selected"' : null ?>><?= l('account_payments.status.paid') ?></option>
                                    <option value="pending" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'pending' ? 'selected="selected"' : null ?>><?= l('account_payments.status.pending') ?></option>
                                    <option value="cancelled" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'cancelled' ? 'selected="selected"' : null ?>><?= l('account_payments.status.cancelled') ?></option>
                                    <option value="refunded" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'refunded' ? 'selected="selected"' : null ?>><?= l('account_payments.status.refunded') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="type" class="small"><?= l('global.type') ?></label>
                                <select name="type" id="type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="one_time" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'one_time' ? 'selected="selected"' : null ?>><?= l('account_payments.type_one_time') ?></option>
                                    <option value="recurring" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'recurring' ? 'selected="selected"' : null ?>><?= l('account_payments.type_recurring') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="frequency" class="small"><?= l('account_payments.frequency') ?></label>
                                <select name="frequency" id="frequency" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach(['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'] as $payment_frequency): ?>
                                        <option value="<?= $payment_frequency ?>" <?= isset($data->filters->filters['frequency']) && $data->filters->filters['frequency'] == $payment_frequency ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.' . $payment_frequency) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="id" <?= $data->filters->order_by == 'id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="total_amount" <?= $data->filters->order_by == 'total_amount' ? 'selected="selected"' : null ?>><?= l('account_payments.order_by_total_amount') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($data->payments)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('account_payments.plan_id') ?></th>
                    <th><?= l('account_payments.total_amount') ?></th>
                    <th><?= l('global.type') ?></th>
                    <th><?= l('account_payments.payment_processor') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->payments as $row): ?>

                    <tr>
                        <td class="text-nowrap">
                            <?= $row->translations->{\Altum\Language::$name}->name ?? $row->plan_name ?? l('global.unknown') ?>
                        </td>

                        <td class="text-nowrap">
                            <?php if($row->status == 'paid'): ?>
                                <span class="badge badge-success mr-1" data-toggle="tooltip" title="<?= l('account_payments.status.' . $row->status) ?>"><i class="fas fa-fw fa-sm fa-check"></i></span>
                            <?php elseif($row->status == 'pending'): ?>
                                <span class="badge badge-warning mr-1" data-toggle="tooltip" title="<?= l('account_payments.status.' . $row->status) ?>"><i class="fas fa-fw fa-sm fa-spinner fa-spin"></i></span>
                            <?php elseif($row->status == 'cancelled'): ?>
                                <span class="badge badge-danger mr-1" data-toggle="tooltip" title="<?= l('account_payments.status.' . $row->status) ?>"><i class="fas fa-fw fa-sm fa-times"></i></span>
                            <?php elseif($row->status == 'refunded'): ?>
                                <span class="badge badge-light mr-1" data-toggle="tooltip" title="<?= $row->refunded_total == $row->total_amount ? l('account_payments.status.fully_refunded') : l('admin_payments.status.partially_refunded') ?>"><i class="fas fa-fw fa-sm fa-redo"></i></span>
                            <?php endif ?>

                            <span class="badge badge-success ml-1"><?= $row->total_amount ?> <?= $row->currency ?></span>
                        </td>

                        <td class="text-nowrap">
                            <?php if($row->type == 'one_time'): ?>
                                <a href="<?= url('admin/payments?type=' . $row->type) ?>" class="badge badge-info mr-1" data-toggle="tooltip" title="<?= l('pay.custom_plan.' . $row->type . '_type') ?>"><i class="fas fa-fw fa-sm fa-bolt"></i></a>
                            <?php elseif($row->type == 'recurring'): ?>
                                <a href="<?= url('admin/payments?type=' . $row->type) ?>" class="badge badge-primary mr-1" data-toggle="tooltip" title="<?= l('pay.custom_plan.' . $row->type . '_type') ?>"><i class="fas fa-fw fa-sm fa-sync fa-spin"></i></a>
                            <?php endif ?>

                            <span class="small text-muted"><?= l('pay.custom_plan.' . $row->frequency) ?></span>
                        </td>



                        <td class="text-nowrap">
                            <span class="badge badge-light">
                                <i class="<?= $data->payment_processors[$row->processor]['icon'] ?> fa-fw mr-1" style="--brand-color: <?= $data->payment_processors[$row->processor]['color'] ?>;--brand-color-dark: <?= $data->payment_processors[$row->processor]['dark_color'] ?>; color: var(--brand-color)" data-custom-colors></i>
                                <span><?= l('pay.custom_plan.' . $row->processor) ?></span>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <span class="" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-muted"></i>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex justify-content-end">
                                <?php if($row->processor == 'offline_payment'): ?>
                                    <a href="<?= \Altum\Uploads::get_full_url('offline_payment_proofs') . $row->payment_proof ?>" class="btn btn-sm btn-outline-primary mr-1" target="_blank" data-toggle="tooltip" title="<?= l('account_payments.action.view_proof') ?>">
                                        <i class="fas fa-fw fa-sm fa-eye"></i>
                                    </a>
                                <?php endif ?>

                                <?php if(settings()->payment->invoice_is_enabled): ?>

                                    <?php if($row->status == 'refunded'): ?>
                                        <a href="<?= url('credit-notes/' . $row->id) ?>" class="btn btn-sm btn-outline-primary mr-1" target="_blank" data-toggle="tooltip" title="<?= l('credit_notes.credit_notes') ?>">
                                            <i class="fas fa-fw fa-sm fa-clipboard"></i>
                                        </a>
                                    <?php endif ?>

                                    <?php if(in_array($row->status, ['paid', 'refunded', 'cancelled'])): ?>
                                        <a href="<?= url('invoice/' . $row->id) ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-fw fa-sm fa-file-invoice"></i> <?= l('account_payments.invoice') ?>
                                        </a>
                                    <?php endif ?>

                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                'filters_get' => $data->filters->get ?? [],
                'name' => 'account_payments',
                'has_secondary_text' => false,
        ]); ?>
    <?php endif ?>

</div>
