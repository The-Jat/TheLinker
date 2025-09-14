<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_business_hours" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_business_hours.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_business_hours" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="business_hours" />

                    <div class="notification-container"></div>

                    <div class="form-group custom-control custom-switch">
                        <input
                                id="business_hours_twenty_four_seven"
                                name="twenty_four_seven"
                                type="checkbox"
                                class="custom-control-input"
                        >
                        <label class="custom-control-label" for="business_hours_twenty_four_seven"><?= l('biolink_business_hours.twenty_four_seven') ?></label>
                        <small class="form-text text-muted"><?= l('biolink_business_hours.twenty_four_seven_help') ?></small>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input
                                id="business_hours_temporarily_closed"
                                name="temporarily_closed"
                                type="checkbox"
                                class="custom-control-input"
                        >
                        <label class="custom-control-label" for="business_hours_temporarily_closed"><?= l('biolink_business_hours.temporarily_closed') ?></label>
                        <small class="form-text text-muted"><?= l('biolink_business_hours.temporarily_closed_help') ?></small>
                    </div>

                    <div class="business_hours_twenty_four_seven">
                        <div class="form-group">
                            <label for="business_hours_twenty_four_seven_title"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.title') ?></label>
                            <input id="business_hours_twenty_four_seven_title" type="text" name="twenty_four_seven_title" maxlength="256" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="business_hours_twenty_four_seven_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <input id="business_hours_twenty_four_seven_description" type="text" name="twenty_four_seven_description" maxlength="256" class="form-control" />
                        </div>
                    </div>

                    <div class="business_hours_temporarily_closed">
                        <div class="form-group">
                            <label for="business_hours_temporarily_closed_title"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.title') ?></label>
                            <input id="business_hours_temporarily_closed_title" type="text" name="temporarily_closed_title" maxlength="256" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="business_hours_temporarily_closed_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <input id="business_hours_temporarily_closed_description" type="text" name="temporarily_closed_description" maxlength="256" class="form-control" />
                        </div>
                    </div>

                    <div id="business_hours_days" class="business_hours_days">
                        <?php foreach(range(1, 7) as $day): ?>
                            <div class="form-group">
                                <label for="<?= 'business_hours_' . $day ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('global.date.long_days.' . $day) ?></label>
                                <div class="input-group">
                                    <input id="<?= 'business_hours_' . $day . '_translation' ?>" type="text" name="<?= 'day_' . $day . '_translation' ?>" maxlength="32" class="form-control" value="<?= l('global.date.long_days.' . $day) ?>" placeholder="<?= l('global.date.long_days.' . $day) ?>" />
                                    <input id="<?= 'business_hours_' . $day ?>" type="text" name="<?= 'day_' . $day ?>" maxlength="256" class="form-control" placeholder="<?= l('biolink_business_hours.hours_placeholder') ?>" />
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <div class="form-group">
                        <label for="business_hours_note"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('biolink_business_hours.note') ?></label>
                        <textarea id="business_hours_note" class="form-control" name="note" maxlength="1000"></textarea>
                    </div>

                    <p class="small text-muted"><i class="fas fa-fw fa-sm fa-circle-info mr-1"></i> <?= l('link.create_info') ?></p>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('link.biolink.create_block') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    
let update_business_hours_state = (form) => {
        let is_checked_twenty_four_seven = form.querySelector('input[name="twenty_four_seven"]').checked;
        let is_checked_temporarily_closed = form.querySelector('input[name="temporarily_closed"]').checked;

        /* Reset all visibility and disabled states */
        form.querySelector('.business_hours_days').classList.remove('d-none');
        form.querySelector('.business_hours_twenty_four_seven').classList.add('d-none');
        form.querySelector('.business_hours_temporarily_closed').classList.add('d-none');
        form.querySelector('input[name="twenty_four_seven"]').removeAttribute('disabled');
        form.querySelector('input[name="temporarily_closed"]').removeAttribute('disabled');

        if (is_checked_twenty_four_seven) {
            /* Handle 24/7 checked */
            form.querySelector('.business_hours_days').classList.add('d-none');
            form.querySelector('.business_hours_twenty_four_seven').classList.remove('d-none');
            form.querySelector('input[name="temporarily_closed"]').setAttribute('disabled', 'disabled');
        } else if (is_checked_temporarily_closed) {
            /* Handle temporarily closed checked */
            form.querySelector('.business_hours_days').classList.add('d-none');
            form.querySelector('.business_hours_temporarily_closed').classList.remove('d-none');
            form.querySelector('input[name="twenty_four_seven"]').setAttribute('disabled', 'disabled');
        }
    }

    document.querySelectorAll('form[name="create_biolink_business_hours"], form[name="update_biolink_"][data-type="business_hours"]').forEach(form => {
        form.querySelector('input[name="twenty_four_seven"]').addEventListener('change', event => {
            update_business_hours_state(form);
        })

        form.querySelector('input[name="temporarily_closed"]').addEventListener('change', event => {
            update_business_hours_state(form);
        })

        /* Initialize once */
        update_business_hours_state(form);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'business_hours_block') ?>

