<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-coins fa-xs text-primary-900 mr-2"></i> <?= l('admin_guests_payments.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['total_payments'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.payments.chart_total_payments') ?>">
                    <?= ($data->total['total_payments'] > 0 ? '+' : null) . nr($data->total['total_payments']) ?>
                </span>
                <span class="badge <?= $data->total['total_amount'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.payments.chart_total_amount') ?>">
                    <?= ($data->total['total_amount'] > 0 ? '+' : null) . nr($data->total['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['total_payments'] ? null : 'd-none' ?>">
            <canvas id="total_payments"></canvas>
        </div>
        <?= $data->total['total_payments'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';
    
let total_payments_color = css.getPropertyValue('--gray-500');
    let total_amount_color = css.getPropertyValue('--primary');

    /* Display chart */
    let payments_chart = document.getElementById('total_payments').getContext('2d');

    let total_amount_color_gradient = payments_chart.createLinearGradient(0, 0, 0, 250);
    total_amount_color_gradient.addColorStop(0, set_hex_opacity(total_amount_color, 0.1));
    total_amount_color_gradient.addColorStop(1, set_hex_opacity(total_amount_color, 0.025));

    let total_payments_color_gradient = payments_chart.createLinearGradient(0, 0, 0, 250);
    total_payments_color_gradient.addColorStop(0, set_hex_opacity(total_payments_color, 0.1));
    total_payments_color_gradient.addColorStop(1, set_hex_opacity(total_payments_color, 0.025));

    new Chart(payments_chart, {
        type: 'line',
        data: {
            labels: <?= $data->payments_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.payments.chart_total_payments')) ?>,
                    data: <?= $data->payments_chart['total_payments'] ?? '[]' ?>,
                    backgroundColor: total_payments_color_gradient,
                    borderColor: total_payments_color,
                    fill: true
                },
                {
                    label: <?= json_encode(l('admin_statistics.payments.chart_total_amount')) ?>,
                    data: <?= $data->payments_chart['total_amount'] ?? '[]' ?>,
                    backgroundColor: total_amount_color_gradient,
                    borderColor: total_amount_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
