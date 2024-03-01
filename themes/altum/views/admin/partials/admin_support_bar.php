<?php defined('ALTUMCODE') || die(); ?>

<?php if(isset(settings()->support->expiry_datetime)): ?>
    <?php
    $expiry_datetime = (new \DateTime(settings()->support->expiry_datetime ?? null));
    $is_active = (new \DateTime()) <= $expiry_datetime;
    ?>

    <?php if(!$is_active && !isset($_COOKIE['dismiss_inactive_support'])): ?>
        <div class="alert alert-warning">
            <i class="fas fa-fw fa-exclamation-triangle text-warning mr-1"></i>
            <?= sprintf(l('admin_index.support.inactive'), '<a href="https://altumco.de/' . PRODUCT_KEY . '-buy" target="_blank" class="font-weight-bold">', '</a>') ?>
            <button type="button" class="close" data-dismiss="alert" data-dismiss-inactive-support><i class="fas fa-fw fa-sm fa-times text-warning"></i></button>
            <?php ob_start() ?>
            <script>
                'use strict';
                document.querySelector('[data-dismiss-inactive-support]').addEventListener('click', event => {
                    set_cookie('dismiss_inactive_support', 1, 5, <?= json_encode(COOKIE_PATH) ?>);
                });
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        </div>
    <?php endif ?>
<?php endif ?>
