<?php defined('ALTUMCODE') || die() ?>

<div class="d-lg-none mb-4">
    <select name="account_header_menu" class="custom-select">
        <option value="<?= url('account') ?>" <?= \Altum\Router::$controller_key == 'account' ? 'selected="selected"' : null ?>><?= l('account.menu') ?></option>
        <option value="<?= url('account-preferences') ?>" <?= \Altum\Router::$controller_key == 'account-preferences' ? 'selected="selected"' : null ?>><?= l('account_preferences.menu') ?></option>
        <option value="<?= url('account-plan') ?>" <?= \Altum\Router::$controller_key == 'account-plan' ? 'selected="selected"' : null ?>><?= l('account_plan.menu') ?></option>
        <?php if(settings()->payment->is_enabled): ?>
            <?php if(settings()->payment->codes_is_enabled): ?>
                <option value="<?= url('account-redeem-code') ?>" <?= \Altum\Router::$controller_key == 'account-redeem-code' ? 'selected="selected"' : null ?>><?= l('account_redeem_code.menu') ?></option>
            <?php endif ?>

            <option value="<?= url('account-payments') ?>" <?= \Altum\Router::$controller_key == 'account-payments' ? 'selected="selected"' : null ?>><?= l('account_payments.menu') ?></option>

            <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                <option value="<?= url('referrals') ?>" <?= \Altum\Router::$controller_key == 'referrals' ? 'selected="selected"' : null ?>><?= l('referrals.menu') ?></option>
            <?php endif ?>
        <?php endif ?>
        <option value="<?= url('account-logs') ?>" <?= \Altum\Router::$controller_key == 'account-logs' ? 'selected="selected"' : null ?>><?= l('account_logs.menu') ?></option>

        <?php if(settings()->main->api_is_enabled): ?>
            <option value="<?= url('account-api') ?>" <?= \Altum\Router::$controller_key == 'account-api' ? 'selected="selected"' : null ?>><?= l('account_api.menu') ?></option>
        <?php endif ?>

        <option value="<?= url('account-delete') ?>" <?= \Altum\Router::$controller_key == 'account-delete' ? 'selected="selected"' : null ?>><?= l('account_delete.menu') ?></option>
    </select>
</div>

<?php ob_start() ?>
<script>
    document.querySelector('select[name="account_header_menu"]').addEventListener('change', event => {
        window.location = document.querySelector('select[name="account_header_menu"]').value;
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<ul class="account-header-navbar d-none d-lg-flex">
    <li class="nav-item">
        <a class="nav-link <?= \Altum\Router::$controller_key == 'account' ? 'active' : null ?>" href="<?= url('account') ?>">
            <i class="fas fa-fw fa-sm fa-user-cog mr-2"></i> <?= l('account.menu') ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Router::$controller_key == 'account-preferences' ? 'active' : null ?>" href="<?= url('account-preferences') ?>">
            <i class="fas fa-fw fa-sm fa-sliders-h mr-2"></i> <?= l('account_preferences.menu') ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Router::$controller_key == 'account-plan' ? 'active' : null ?>" href="<?= url('account-plan') ?>">
            <i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?>
        </a>
    </li>

    <?php if(settings()->payment->is_enabled): ?>
        <?php if(settings()->payment->codes_is_enabled): ?>
            <li class="nav-item">
                <a href="<?= url('account-redeem-code') ?>" class="nav-link <?= \Altum\Router::$controller_key == 'account-redeem-code' ? 'active' : null ?>">
                    <i class="fas fa-fw fa-sm fa-tags mr-2"></i> <?= l('account_redeem_code.menu') ?>
                </a>
            </li>
        <?php endif ?>

        <li class="nav-item">
            <a class="nav-link <?= \Altum\Router::$controller_key == 'account-payments' ? 'active' : null ?>" href="<?= url('account-payments') ?>">
                <i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('account_payments.menu') ?>
            </a>
        </li>

        <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
            <li class="nav-item">
                <a class="nav-link <?= \Altum\Router::$controller_key == 'referrals' ? 'active' : null ?>" href="<?= url('referrals') ?>">
                    <i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?>
                </a>
            </li>
        <?php endif ?>
    <?php endif ?>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Router::$controller_key == 'account-logs' ? 'active' : null ?>" href="<?= url('account-logs') ?>">
            <i class="fas fa-fw fa-sm fa-scroll mr-2"></i> <?= l('account_logs.menu') ?>
        </a>
    </li>

    <?php if(settings()->main->api_is_enabled): ?>
        <li class="nav-item">
            <a class="nav-link <?= \Altum\Router::$controller_key == 'account-api' ? 'active' : null ?>" href="<?= url('account-api') ?>">
                <i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?>
            </a>
        </li>
    <?php endif ?>

    <li class="nav-item">
        <a class="nav-link <?= \Altum\Router::$controller_key == 'account-delete' ? 'active' : null ?>" href="<?= url('account-delete') ?>">
            <i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('account_delete.menu') ?>
        </a>
    </li>
</ul>

