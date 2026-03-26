<?php defined('ALTUMCODE') || die() ?>

<div id="top_left_floating" style="position: fixed; left: 1rem; top: 1rem; z-index: 1;">
	<?php if($this->link->settings->branded_button_is_enabled): ?>
        <div id="branded_button" data-toggle="modal" data-target="#branded_button_modal">
            <div class="mb-2">
                <button type="button" class="btn share-button zoom-animation-subtle d-flex justify-content-center align-items-center" onclick="" data-toggle="tooltip" data-placement="right" title="<?= $this->link->settings->branded_button_title ?>" data-tooltip-hide-on-click>
                    <?php if(!empty($this->link->settings->branded_button_icon)): ?>
                        <img src="<?= \Altum\Uploads::get_full_url('branded_button_icon') . $this->link->settings->branded_button_icon ?>" class="branded-button-icon" />
                    <?php else: ?>
                        <i class="fas fa-fw fa-circle"></i>
                    <?php endif ?>
                </button>
            </div>
        </div>

		<?php ob_start() ?>
        <div class="modal fade" id="branded_button_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="modal-title">
								<?= $this->link->settings->branded_button_title ?>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div>
                            <?= $this->link->settings->branded_button_content ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
		<?php \Altum\Event::add_content(ob_get_clean(), 'modals') ?>
	<?php endif ?>

	<?php if($this->link->settings->scroll_buttons_is_enabled): ?>
        <div id="scroll_buttons">
            <div class="mb-2">
                <button type="button" class="btn share-button zoom-animation-subtle d-flex justify-content-center align-items-center" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" data-toggle="tooltip" data-placement="right" title="<?= l('global.scroll_top') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-arrow-up"></i>
                </button>
            </div>
            <div>
                <button type="button" class="btn share-button zoom-animation-subtle d-flex justify-content-center align-items-center" onclick="window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });" data-toggle="tooltip" data-placement="right" title="<?= l('global.scroll_bottom') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-arrow-down"></i>
                </button>
            </div>
        </div>

	<?php ob_start() ?>
        <script>
            'use strict';

            const toggle_scroll_buttons = () => {
                const scroll_buttons = document.getElementById('scroll_buttons');
                scroll_buttons.style.display = document.body.scrollHeight > window.innerHeight ? 'block' : 'none';
            };

            window.addEventListener('load', toggle_scroll_buttons);
            window.addEventListener('resize', toggle_scroll_buttons);
        </script>

		<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
	<?php endif ?>
</div>

