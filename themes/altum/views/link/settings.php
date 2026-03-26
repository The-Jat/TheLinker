<?php defined('ALTUMCODE') || die() ?>

<?php

/* Get some variables */
$biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';

/* Get the proper settings depending on the type of link */
$settings = require THEME_PATH . 'views/link/settings/' . mb_strtolower($data->link->type) . '.php';

?>

<?= $settings->html ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';
    
    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    let update_main_url = (new_url) => {
        $('#link_url').text(new_url);

        let new_full_url = null;
        let new_full_url_no_protocol = null;
        if($('select[name="domain_id"]').length) {
            let selected_domain_id_element = $('select[name="domain_id"]').find(':selected');
            new_full_url = `${selected_domain_id_element.data('full-url')}${new_url}`;
            new_full_url_no_protocol = `${selected_domain_id_element.text()}${new_url}`;
        } else {
            new_full_url_no_protocol = new_full_url = `${$('input[name="link_base"]').val()}${new_url}`;
        }

        $('#link_full_url').text(new_full_url_no_protocol).attr('href', new_full_url);
        $('#link_full_url_copy').attr('data-clipboard-text', new_full_url);

        /* Refresh iframe */
        refresh_biolink_preview();
    };

    let refresh_biolink_preview = () => {
        if(!document.querySelector('#biolink_preview_iframe')) {
            return;
        }

        /* Add loader */
        document.querySelector('#biolink_preview_iframe_loading').classList.remove('d-none');

        /* Refresh iframe */
        let biolink_preview_iframe = document.querySelector('#biolink_preview_iframe');

        setTimeout(() => {
            biolink_preview_iframe.setAttribute('src', biolink_preview_iframe.getAttribute('src'));
        }, 750)

        biolink_preview_iframe.onload = () => {
            document.querySelector('#biolink_preview_iframe').dispatchEvent(new Event('refreshed'));
            document.querySelector('#biolink_preview_iframe_loading').classList.add('d-none');
        }
    }

    <?php if (!empty($data->domains)): ?>
    /* Is main link handler */
    let is_main_link_handler = () => {
        if(document.querySelector('#is_main_link').checked) {
            document.querySelector('#url').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#url').removeAttribute('disabled');
        }
    }

    document.querySelector('#is_main_link') && document.querySelector('#is_main_link').addEventListener('change', is_main_link_handler);

    /* Domain Id Handler */
    let domain_id_handler = () => {
        let domain_id = document.querySelector('select[name="domain_id"]').value;

        if(document.querySelector(`select[name="domain_id"] option[value="${domain_id}"]`).getAttribute('data-type') == '0') {
            document.querySelector('#is_main_link_wrapper').classList.remove('d-none');
        } else {
            document.querySelector('#is_main_link_wrapper').classList.add('d-none');
            document.querySelector('#is_main_link').checked = false;
        }

        is_main_link_handler();
    }

    domain_id_handler();

    document.querySelector('select[name="domain_id"]') && document.querySelector('select[name="domain_id"]').addEventListener('change', domain_id_handler);
    <?php endif ?>

    /* Set this to true to enable autosave */
    let enable_autosave = <?= json_encode($this->user->preferences->links_autosave_settings ?? false) ?>;

    /* Autosave feature for update_biolink forms */
    let autosave_timeout = null;
    let previous_form_data_map = {};

    /* Serialize FormData to string for comparison */
    const serialize_form_data = form_element => {
        let form_data = new FormData(form_element);
        let form_data_entries = [];
        for(let [field_name, field_value] of form_data.entries()) {
            form_data_entries.push(`${field_name}=${field_value}`);
        }
        return form_data_entries.sort().join('&');
    };

    if(enable_autosave) {
        $('form[name="update_event"], form[name="update_file"], form[name="update_link"], form[name="update_static"], form[name="update_vcard"], form[name="update_biolink"],form[name="update_biolink_"]').on('input change', event => {
            let form_element = event.currentTarget.form || event.currentTarget.closest('form');
            if(!form_element) return;

            let form_name = form_element.getAttribute('name');
            let current_form_data = serialize_form_data(form_element);

            /* Only proceed if data has changed */
            if(previous_form_data_map[form_name] !== undefined && previous_form_data_map[form_name] === current_form_data) {
                return;
            }

            previous_form_data_map[form_name] = current_form_data;

            /* Debounce autosave */
            if(autosave_timeout) {
                clearTimeout(autosave_timeout);
            }
            autosave_timeout = setTimeout(() => {
                /* Only autosave if form data actually changed */
                $(form_element).trigger('submit', [{ is_autosave: true }]);
            }, 3000);
        });

        /* Update previous_form_data_map on manual submit to sync state */
        $('form[name="update_event"], form[name="update_file"], form[name="update_link"], form[name="update_static"], form[name="update_vcard"], form[name="update_biolink"],form[name="update_biolink_"]').on('submit', event => {
            let form_element = event.currentTarget;
            let form_name = form_element.getAttribute('name');
            previous_form_data_map[form_name] = serialize_form_data(form_element);
        });
    }
</script>

<?= $settings->javascript ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
