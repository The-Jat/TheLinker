<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('tools.text_to_speech.name') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.text_to_speech.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.text_to_speech.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <?= $this->views['ratings'] ?>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group" data-character-counter="textarea">
                    <label for="text" class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('tools.text') ?></span>
                        <small class="text-muted" data-character-counter-wrapper></small>
                    </label>
                    <textarea id="text" name="text" maxlength="200" class="form-control <?= \Altum\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" required="required"><?= $data->values['text'] ?></textarea>
                    <?= \Altum\Alerts::output_field_error('text') ?>
                </div>

                <div class="form-group">
                    <label for="language_code"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('tools.text_to_speech.language_code') ?></label>
                    <select id="language_code" name="language_code" class="form-control <?= \Altum\Alerts::has_field_errors('language_code') ? 'is-invalid' : null ?>">
                        <option value="af" <?= $data->values['language_code'] == 'af' ? 'selected="selected"' : null ?>>af – Afrikaans</option>
                        <option value="sq" <?= $data->values['language_code'] == 'sq' ? 'selected="selected"' : null ?>>sq – Albanian</option>
                        <option value="am" <?= $data->values['language_code'] == 'am' ? 'selected="selected"' : null ?>>am – Amharic</option>
                        <option value="ar" <?= $data->values['language_code'] == 'ar' ? 'selected="selected"' : null ?>>ar – Arabic</option>
                        <option value="eu" <?= $data->values['language_code'] == 'eu' ? 'selected="selected"' : null ?>>eu – Basque</option>
                        <option value="bn" <?= $data->values['language_code'] == 'bn' ? 'selected="selected"' : null ?>>bn – Bengali</option>
                        <option value="bs" <?= $data->values['language_code'] == 'bs' ? 'selected="selected"' : null ?>>bs – Bosnian</option>
                        <option value="bg" <?= $data->values['language_code'] == 'bg' ? 'selected="selected"' : null ?>>bg – Bulgarian</option>
                        <option value="yue" <?= $data->values['language_code'] == 'yue' ? 'selected="selected"' : null ?>>yue – Cantonese</option>
                        <option value="ca" <?= $data->values['language_code'] == 'ca' ? 'selected="selected"' : null ?>>ca – Catalan</option>
                        <option value="zh- <?= $data->values['language_code'] == 'zh' ? 'selected="selected"' : null ?>CN">zh-CN – Chinese (Simplified)</option>
                        <option value="zh- <?= $data->values['language_code'] == 'zh' ? 'selected="selected"' : null ?>TW">zh-TW – Chinese (Traditional)</option>
                        <option value="hr" <?= $data->values['language_code'] == 'hr' ? 'selected="selected"' : null ?>>hr – Croatian</option>
                        <option value="cs" <?= $data->values['language_code'] == 'cs' ? 'selected="selected"' : null ?>>cs – Czech</option>
                        <option value="da" <?= $data->values['language_code'] == 'da' ? 'selected="selected"' : null ?>>da – Danish</option>
                        <option value="nl" <?= $data->values['language_code'] == 'nl' ? 'selected="selected"' : null ?>>nl – Dutch</option>
                        <option value="en" <?= $data->values['language_code'] == 'en' ? 'selected="selected"' : null ?>>en – English</option>
                        <option value="et" <?= $data->values['language_code'] == 'et' ? 'selected="selected"' : null ?>>et – Estonian</option>
                        <option value="tl" <?= $data->values['language_code'] == 'tl' ? 'selected="selected"' : null ?>>tl – Filipino</option>
                        <option value="fi" <?= $data->values['language_code'] == 'fi' ? 'selected="selected"' : null ?>>fi – Finnish</option>
                        <option value="fr" <?= $data->values['language_code'] == 'fr' ? 'selected="selected"' : null ?>>fr – French</option>
                        <option value="fr- <?= $data->values['language_code'] == 'fr' ? 'selected="selected"' : null ?>CA">fr-CA – French (Canada)</option>
                        <option value="gl" <?= $data->values['language_code'] == 'gl' ? 'selected="selected"' : null ?>>gl – Galician</option>
                        <option value="de" <?= $data->values['language_code'] == 'de' ? 'selected="selected"' : null ?>>de – German</option>
                        <option value="el" <?= $data->values['language_code'] == 'el' ? 'selected="selected"' : null ?>>el – Greek</option>
                        <option value="gu" <?= $data->values['language_code'] == 'gu' ? 'selected="selected"' : null ?>>gu – Gujarati</option>
                        <option value="ha" <?= $data->values['language_code'] == 'ha' ? 'selected="selected"' : null ?>>ha – Hausa</option>
                        <option value="he" <?= $data->values['language_code'] == 'he' ? 'selected="selected"' : null ?>>he – Hebrew</option>
                        <option value="hi" <?= $data->values['language_code'] == 'hi' ? 'selected="selected"' : null ?>>hi – Hindi</option>
                        <option value="hu" <?= $data->values['language_code'] == 'hu' ? 'selected="selected"' : null ?>>hu – Hungarian</option>
                        <option value="is" <?= $data->values['language_code'] == 'is' ? 'selected="selected"' : null ?>>is – Icelandic</option>
                        <option value="id" <?= $data->values['language_code'] == 'id' ? 'selected="selected"' : null ?>>id – Indonesian</option>
                        <option value="it" <?= $data->values['language_code'] == 'it' ? 'selected="selected"' : null ?>>it – Italian</option>
                        <option value="ja" <?= $data->values['language_code'] == 'ja' ? 'selected="selected"' : null ?>>ja – Japanese</option>
                        <option value="jv" <?= $data->values['language_code'] == 'jv' ? 'selected="selected"' : null ?>>jv – Javanese</option>
                        <option value="kn" <?= $data->values['language_code'] == 'kn' ? 'selected="selected"' : null ?>>kn – Kannada</option>
                        <option value="km" <?= $data->values['language_code'] == 'km' ? 'selected="selected"' : null ?>>km – Khmer</option>
                        <option value="ko" <?= $data->values['language_code'] == 'ko' ? 'selected="selected"' : null ?>>ko – Korean</option>
                        <option value="la" <?= $data->values['language_code'] == 'la' ? 'selected="selected"' : null ?>>la – Latin</option>
                        <option value="lv" <?= $data->values['language_code'] == 'lv' ? 'selected="selected"' : null ?>>lv – Latvian</option>
                        <option value="ml" <?= $data->values['language_code'] == 'ml' ? 'selected="selected"' : null ?>>ml – Malayalam</option>
                        <option value="mr" <?= $data->values['language_code'] == 'mr' ? 'selected="selected"' : null ?>>mr – Marathi</option>
                        <option value="my" <?= $data->values['language_code'] == 'my' ? 'selected="selected"' : null ?>>my – Myanmar (Burmese)</option>
                        <option value="ne" <?= $data->values['language_code'] == 'ne' ? 'selected="selected"' : null ?>>ne – Nepali</option>
                        <option value="nb" <?= $data->values['language_code'] == 'nb' ? 'selected="selected"' : null ?>>nb – Norwegian (Bokmål)</option>
                        <option value="pl" <?= $data->values['language_code'] == 'pl' ? 'selected="selected"' : null ?>>pl – Polish</option>
                        <option value="pt- <?= $data->values['language_code'] == 'pt' ? 'selected="selected"' : null ?>BR">pt-BR – Portuguese (Brazil)</option>
                        <option value="pt- <?= $data->values['language_code'] == 'pt' ? 'selected="selected"' : null ?>PT">pt-PT – Portuguese (Portugal)</option>
                        <option value="pa" <?= $data->values['language_code'] == 'pa' ? 'selected="selected"' : null ?>>pa – Punjabi (Gurmukhi)</option>
                        <option value="ro" <?= $data->values['language_code'] == 'ro' ? 'selected="selected"' : null ?>>ro – Romanian</option>
                        <option value="ru" <?= $data->values['language_code'] == 'ru' ? 'selected="selected"' : null ?>>ru – Russian</option>
                        <option value="sr" <?= $data->values['language_code'] == 'sr' ? 'selected="selected"' : null ?>>sr – Serbian</option>
                        <option value="si" <?= $data->values['language_code'] == 'si' ? 'selected="selected"' : null ?>>si – Sinhala</option>
                        <option value="sk" <?= $data->values['language_code'] == 'sk' ? 'selected="selected"' : null ?>>sk – Slovak</option>
                        <option value="es" <?= $data->values['language_code'] == 'es' ? 'selected="selected"' : null ?>>es – Spanish</option>
                        <option value="su" <?= $data->values['language_code'] == 'su' ? 'selected="selected"' : null ?>>su – Sundanese</option>
                        <option value="sw" <?= $data->values['language_code'] == 'sw' ? 'selected="selected"' : null ?>>sw – Swahili</option>
                        <option value="sv" <?= $data->values['language_code'] == 'sv' ? 'selected="selected"' : null ?>>sv – Swedish</option>
                        <option value="ta" <?= $data->values['language_code'] == 'ta' ? 'selected="selected"' : null ?>>ta – Tamil</option>
                        <option value="te" <?= $data->values['language_code'] == 'te' ? 'selected="selected"' : null ?>>te – Telugu</option>
                        <option value="th" <?= $data->values['language_code'] == 'th' ? 'selected="selected"' : null ?>>th – Thai</option>
                        <option value="tr" <?= $data->values['language_code'] == 'tr' ? 'selected="selected"' : null ?>>tr – Turkish</option>
                        <option value="uk" <?= $data->values['language_code'] == 'uk' ? 'selected="selected"' : null ?>>uk – Ukrainian</option>
                        <option value="ur" <?= $data->values['language_code'] == 'ur' ? 'selected="selected"' : null ?>>ur – Urdu</option>
                        <option value="vi" <?= $data->values['language_code'] == 'vi' ? 'selected="selected"' : null ?>>vi – Vietnamese</option>
                        <option value="cy" <?= $data->values['language_code'] == 'cy' ? 'selected="selected"' : null ?>>cy – Welsh</option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('language_code') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <audio class="w-100" controls>
                        <source src="<?= url('tools/text-to-speech?text=' . $data->values['text'] . '&language_code=' . $data->values['language_code']) ?>" type="audio/mp3">
                    </audio>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

