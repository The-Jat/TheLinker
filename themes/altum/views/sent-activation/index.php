<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

    <h1 class="h5"><?= l('sent_activation.header') ?></h1>
    <p class="text-muted font-size-little-small"><?= sprintf(l('sent_activation.subheader'), '<strong>' . $data->email . '</strong>') ?></p>

    <div class="mt-4 text-center">
        <a href="<?= url('resend-activation?email=' . $data->email) ?>" class="btn btn-primary btn-block my-1" id="resend_activation_button" disabled="disabled">
            <?= l('sent_activation.resend_activation') ?>
        </a>

        <small class="text-muted text-center d-block mt-2" id="resend_timer"></small>
    </div>

<?php ob_start() ?>
    <script>
        'use strict';

        const cooldown_seconds = 30;
        const resend_button = document.getElementById('resend_activation_button');
        const resend_button_text = resend_button.innerText;
        const resend_again_text = <?= json_encode(l('sent_activation.resend_activation_timer')) ?>;

        const now = () => Math.floor(Date.now() / 1000);
        const cooldown_until = now() + cooldown_seconds;

        const update_ui = () => {
            const remaining_seconds = cooldown_until - now();

            if (remaining_seconds <= 0) {
                resend_button.classList.remove('disabled');
                resend_button.innerText = resend_button_text;
                return;
            }

            resend_button.classList.add('disabled');
            resend_button.innerText = resend_again_text.replace('%s', remaining_seconds);
        };

        update_ui();
        setInterval(update_ui, 1000);
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
