<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_weather" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('biolink_weather.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_weather" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="weather" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="weather_source"><i class="fas fa-fw fa-grip fa-sm text-muted mr-1"></i> <?= l('biolink_weather.source') ?></label>
                        <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                            <div class="p-2 col-12 h-100">
                                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate active">
                                    <input type="radio" name="source" value="latitude_longitude" class="custom-control-input" checked="checked" required="required" />
                                    <?= l('biolink_weather.source.latitude_longitude') ?>
                                </label>
                            </div>

                            <?php if(settings()->links->google_geocoding_is_enabled): ?>
                                <div class="p-2 col-12 h-100">
                                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                        <input type="radio" name="source" value="address" class="custom-control-input" required="required" />
                                        <?= l('biolink_weather.source.address') ?>
                                    </label>
                                </div>
                            <?php endif ?>

                            <div class="p-2 col-12 h-100">
                                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                    <input type="radio" name="source" value="user_location" class="custom-control-input" required="required" />
                                    <?= l('biolink_weather.source.user_location') ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6" data-source="latitude_longitude">
                            <div class="form-group">
                                <label for="weather_latitude"><i class="fas fa-fw fa-location-crosshairs fa-sm text-muted mr-1"></i> <?= l('biolink_weather.latitude') ?></label>
                                <input id="weather_latitude" type="number" name="latitude" min="-90" max="90" step="0.000001" class="form-control" required="required" />
                            </div>
                        </div>

                        <div class="col-6" data-source="latitude_longitude">
                            <div class="form-group">
                                <label for="weather_longitude"><i class="fas fa-fw fa-location-crosshairs fa-sm text-muted mr-1"></i> <?= l('biolink_weather.longitude') ?></label>
                                <input id="weather_longitude" type="number" name="longitude" min="-180" max="180" step="0.000001" class="form-control" required="required" />
                            </div>
                        </div>
                    </div>

                    <?php if(settings()->links->google_geocoding_is_enabled): ?>
                    <div data-source="address">
                        <div class="form-group">
                            <label for="weather_address"><i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i> <?= l('biolink_weather.address') ?></label>
                            <input id="weather_address" type="text" name="address" maxlength="256" class="form-control" />
                        </div>
                    </div>
                    <?php endif ?>

                    <div data-source="user_location"></div>

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

    type_handler('#create_biolink_weather input[name="source"]', 'data-source');
    document.querySelector('#create_biolink_weather input[name="source"]') && document.querySelectorAll('#create_biolink_weather input[name="source"]').forEach(element => element.addEventListener('change', () => { type_handler('#create_biolink_weather input[name="source"]', 'data-source'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'weather_block') ?>

