<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-robot fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.documents.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['documents'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['documents'] > 0 ? '+' : null) . nr($data->total['documents']) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="documents"></canvas>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let documents_chart = document.getElementById('documents').getContext('2d');
    color_gradient = documents_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(documents_chart, {
        type: 'line',
        data: {
            labels: <?= $data->documents_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.documents.chart')) ?>,
                    data: <?= $data->documents_chart['documents'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
