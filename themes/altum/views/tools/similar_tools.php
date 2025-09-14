<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->similar_widget_is_enabled && isset($data->tools[$data->tool]['similar'])): ?>
    <?php $count = settings()->tools->style == 'frankfurt' ? 4 : 3; ?>

    <div class="mt-5">
        <h2 class="small font-weight-bold text-uppercase text-muted mb-3"><i class="fas fa-fw fa-sm fa-tools text-primary mr-1"></i> <?= l('tools.similar_tools') ?></h2>

        <div class="row m-n3" id="similar_tools">
            <?php $i = 0; ?>
            <?php foreach($data->tools[$data->tool]['similar'] as $key): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <?php if($i++ >= $count) break ?>

                    <?= include_view(THEME_PATH . 'views/tools/tool_widget_' . (settings()->tools->style ?? 'frankfurt') . '.php', [
                        'tool_id' => $key,
                        'tool_icon' => $data->tools[$key]['icon'],
                        'tools_usage' => $data->tools_usage
                    ]); ?>

                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

