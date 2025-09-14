<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->popular_widget_is_enabled): ?>
    <?php $count = settings()->tools->style == 'frankfurt' ? 4 : 6; ?>

    <div class="mt-5">
        <h2 class="small font-weight-bold text-uppercase text-muted mb-3"><i class="fas fa-fw fa-sm fa-star text-primary mr-1"></i> <?= l('tools.popular_tools') ?></h2>

        <div class="row m-n3" id="popular_tools">
            <?php $i = 0; ?>
            <?php foreach($data->tools_usage as $key => $value): ?>
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
