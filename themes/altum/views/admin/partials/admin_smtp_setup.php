<?php defined('ALTUMCODE') || die(); ?>

<?php
/* check if smtp is configured by user */
function is_smtp_configured_by_user($smtp_settings) {
    $fields_to_check = [
        'from_name',
        'from',
        'reply_to_name',
        'reply_to',
        'cc',
        'bcc',
        'host',
        'port',
        'username',
        'password'
    ];

    foreach($fields_to_check as $field) {
        if(!empty($smtp_settings->{$field})) {
            return true; /* user entered something */
        }
    }

    /* if authentication checkbox is enabled, count as configured */
    if(!empty($smtp_settings->auth) && $smtp_settings->auth == 1) {
        return true;
    }

    return false; /* nothing meaningful was entered */
}
?>

<?php //ALTUMCODE:DEMO if(!DEMO) { ?>
<?php if(!isset($_COOKIE['dismiss_smtp_setup']) && !is_smtp_configured_by_user(settings()->smtp)): ?>
    <div class="alert alert-info alert-dismissible" role="alert">
        <i class="fas fa-fw fa-info-circle mr-1"></i>
        <?= sprintf(l('admin_settings.smtp.info_message.no_setup'), url('admin/settings/smtp')) ?>

        <button type="button" class="close" data-dismiss="alert" data-dismiss-smtp-setup>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        /* set cookie when dismissing the alert */
        document.querySelector('[data-dismiss-smtp-setup]').addEventListener('click', event => {
            set_cookie('dismiss_smtp_setup', 1, 30, <?= json_encode(COOKIE_PATH) ?>);
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
<?php //ALTUMCODE:DEMO } ?>
