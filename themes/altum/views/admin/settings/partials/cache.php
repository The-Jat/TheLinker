<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="alert alert-info mb-3"><?= sprintf(l('admin_settings.documentation'), '<a href="' . PRODUCT_DOCUMENTATION_URL . '#caching" target="_blank">', '</a>') ?></div>

    <div class="form-group">
        <label for="is_enabled"><i class="fas fa-fw fa-sm fa-bullseye text-muted mr-1"></i> <?= l('admin_settings.cache.is_enabled') ?></label>
        <input id="is_enabled" name="is_enabled" type="text" class="form-control" value="<?= CACHE ? l('global.yes') : l('global.no') ?>" readonly="readonly" />
    </div>

    <div class="form-group">
        <label for="driver"><i class="fas fa-fw fa-sm fa-hdd text-muted mr-1"></i> <?= l('admin_settings.cache.driver') ?></label>
        <input id="driver" name="driver" type="text" class="form-control" value="<?= \Altum\Cache::$driver ?>" readonly="readonly" />
    </div>

    <div class="form-group">
        <label for="size"><i class="fas fa-fw fa-sm fa-database text-muted mr-1"></i> <?= l('admin_settings.cache.size') ?></label>
        <div class="input-group">
            <input id="size" name="size" type="text" class="form-control" value="<?= cache()->getStats()->getSize() / 1000 / 1000 ?>" readonly="readonly" />
            <div class="input-group-append">
                <span class="input-group-text">
                    MB
                </span>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('admin_settings.cache.clear') ?></button>
