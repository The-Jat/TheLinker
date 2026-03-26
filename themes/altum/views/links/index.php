<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php foreach($data->total_links as $type => $total): ?>
    <?php
        $plan_limit = match($type) {
            'biolink' => 'biolinks_limit',
            'link' => 'links_limit',
            'file' => 'files_limit',
            'vcard' => 'vcards_limit',
            'event' => 'events_limit',
            'static' => 'static_limit',
        }
    ?>
        <?php if($this->user->plan_settings->{$plan_limit} != -1 && $total > $this->user->plan_settings->{$plan_limit}): ?>
            <div class="alert alert-danger">
                <i class="fas fa-fw fa-times-circle text-danger mr-2"></i> <?= sprintf(settings()->payment->is_enabled ? l('global.info_message.plan_feature_limit_removal_with_upgrade') : l('global.info_message.plan_feature_limit_removal'), '<strong>' . $total - $this->user->plan_settings->{$plan_limit}, mb_strtolower(l('links.title')) . ' (' . l('links.menu.' . $type) . ')</strong>', '<a href="' . url('plan') . '" class="font-weight-bold text-reset">' . l('global.info_message.plan_upgrade') . '</a>') ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>

    <div id="links_auto_copy_link"></div>

    <?= $this->views['links_content'] ?>

</div>

<?php ob_start() ?>
<script>
    'use strict';

    const query_parameters = new URLSearchParams(window.location.search);

    if (query_parameters.has('auto_copy_link')) {
        let text = document.querySelector('#link_full_url_copy').getAttribute('data-clipboard-text');
        let notification_container = document.querySelector('#links_auto_copy_link');

        navigator.clipboard.writeText(text).then(() => {
            display_notifications(<?= json_encode(l('links.auto_copy_link.success')) ?>, 'success', notification_container);
        }).catch((error) => {
            display_notifications(<?= json_encode(l('links.auto_copy_link.error')) ?>, 'error', notification_container);
        });
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


