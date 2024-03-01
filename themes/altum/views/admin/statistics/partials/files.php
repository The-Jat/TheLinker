<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-file fa-xs text-primary-900 mr-2"></i> <?= l('links.menu.file') ?></h2>

            <div>
                <span class="badge <?= $data->total['files'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['files'] > 0 ? '+' : null) . nr($data->total['files']) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="files"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Prepare chart */
    let files_chart = document.getElementById('files').getContext('2d');
    color_gradient = files_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    /* Display chart */
    new Chart(files_chart, {
        type: 'line',
        data: {
            labels: <?= $data->files_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('links.menu.file')) ?>,
                data: <?= $data->files_chart['files'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
