<?php defined('ALTUMCODE') || die() ?>

<div>
    <ul class="nav nav-pills d-flex flex-fill flex-column flex-lg-row mb-3" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link active" id="pills-light_ltr-tab" data-toggle="pill" href="#pills-light_ltr" role="tab" aria-controls="pills-light_ltr" aria-selected="true">
                <i class="fas fa-fw fa-sm fa-sun mr-1"></i> <?= l('admin_settings.theme.light_ltr') ?>
            </a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-light_rtl-tab" data-toggle="pill" href="#pills-light_rtl" role="tab" aria-controls="pills-light_rtl" aria-selected="false">
                <i class="fas fa-fw fa-sm fa-sun mr-1"></i> <?= l('admin_settings.theme.light_rtl') ?>
            </a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-dark_ltr-tab" data-toggle="pill" href="#pills-dark_ltr" role="tab" aria-controls="pills-dark_ltr" aria-selected="false">
                <i class="fas fa-fw fa-sm fa-moon mr-1"></i> <?= l('admin_settings.theme.dark_ltr') ?>
            </a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-dark_rtl-tab" data-toggle="pill" href="#pills-dark_rtl" role="tab" aria-controls="pills-dark_rtl" aria-selected="false">
                <i class="fas fa-fw fa-sm fa-moon mr-1"></i> <?= l('admin_settings.theme.dark_rtl') ?>
            </a>
        </li>
    </ul>

    <?php function get_theme_mode_html($mode) { ob_start(); ?>
        <div class="form-group custom-control custom-switch">
            <input id="<?= $mode . '_is_enabled' ?>" name="<?= $mode . '_is_enabled' ?>" type="checkbox" class="custom-control-input" <?= settings()->theme->{$mode . '_is_enabled'} ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="<?= $mode . '_is_enabled' ?>"><?= l('global.status') ?></label>
        </div>

        <h2 class="h5"><?= l('admin_settings.theme.primary') ?></h2>
        <?php foreach(['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $key): ?>
            <div class="form-group">
                <label for="<?= $mode . '_primary_' . $key ?>">Primary <?= $key ?></label>
                <input id="<?= $mode . '_primary_' . $key ?>" type="hidden" name="<?= $mode . '_primary_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_primary_' . $key} ?? '#000000' ?>" data-color-picker />
            </div>
        <?php endforeach ?>

        <h2 class="h5"><?= l('admin_settings.theme.gray') ?></h2>
        <?php foreach(['25', '50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $key): ?>
            <div class="form-group">
                <label for="<?= $mode . '_gray_' . $key ?>">Gray <?= $key ?></label>
                <input id="<?= $mode . '_gray_' . $key ?>" type="hidden" name="<?= $mode . '_gray_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_gray_' . $key} ?? '#000000' ?>" data-color-picker />
            </div>
        <?php endforeach ?>

        <h2 class="h5"><?= l('admin_settings.theme.others') ?></h2>
        <div class="form-group" data-range-counter>
            <label for="<?= $mode . '_border_radius' ?>"><?= l('admin_settings.theme.border_radius') ?></label>
            <input id="<?= $mode . '_border_radius' ?>" name="<?= $mode . '_border_radius' ?>" type="range" step=".1" min="0" max="1" class="form-control-range" value="<?= settings()->theme->{$mode . '_border_radius'} ?? null ?>" />
        </div>

        <div class="form-group">
            <label for="<?= $mode . '_font_family' ?>"><?= l('admin_settings.theme.font_family') ?></label>
            <input id="<?= $mode . '_font_family' ?>" name="<?= $mode . '_font_family' ?>" type="text" class="form-control" value="<?= settings()->theme->{$mode . '_font_family'} ?? null ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.theme.font_family_help') ?></small>
        </div>
        <?php return ob_get_clean(); } ?>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-light_ltr" role="tabpanel" aria-labelledby="pills-light_ltr-tab">
            <?= get_theme_mode_html('light_ltr') ?>
        </div>

        <div class="tab-pane fade" id="pills-light_rtl" role="tabpanel" aria-labelledby="pills-light_rtl-tab">
            <?= get_theme_mode_html('light_rtl') ?>
        </div>

        <div class="tab-pane fade" id="pills-dark_ltr" role="tabpanel" aria-labelledby="pills-dark_ltr-tab">
            <?= get_theme_mode_html('dark_ltr') ?>
        </div>

        <div class="tab-pane fade" id="pills-dark_rtl" role="tabpanel" aria-labelledby="pills-dark_rtl-tab">
            <?= get_theme_mode_html('dark_rtl') ?>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
