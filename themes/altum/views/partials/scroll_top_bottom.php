<?php defined('ALTUMCODE') || die() ?>

<div style="position: fixed; right: 1rem; bottom: 1rem; z-index: 1;">
    <div class="mb-2">
        <button type="button" class="btn btn-light" onclick="document.querySelector('<?= $data->top_selector ?>').scrollTo({ top: 0, behavior: 'smooth' });" data-toggle="tooltip" data-placement="left" title="<?= l('global.scroll_top') ?>" data-tooltip-hide-on-click>
            <i class="fas fa-fw fa-arrow-up"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-light" onclick="document.querySelector('<?= $data->bottom_selector ?>').scrollIntoView({ behavior: 'smooth', block: 'center' });" data-toggle="tooltip" data-placement="left" title="<?= l('global.scroll_bottom') ?>" data-tooltip-hide-on-click>
            <i class="fas fa-fw fa-arrow-down"></i>
        </button>
    </div>
</div>
