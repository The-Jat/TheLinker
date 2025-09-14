<?php defined('ALTUMCODE') || die() ?>

<section class="container">

    <?= \Altum\Alerts::output_alerts() ?>

    <div id="links_auto_copy_link"></div>

    <?= $this->views['links_content'] ?>

</section>

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


