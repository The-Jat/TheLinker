<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-screwdriver-wrench mr-1"></i> <?= l('tools.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <form id="search" action="" method="get" role="form">
        <div class="form-group">
            <input id="search" type="search" name="search" class="form-control form-control-lg" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
        </div>
    </form>

    <div id="tools_no_data" class="mt-5 d-none">
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'tools',
            'has_secondary_text' => false,
            'has_wrapper' => true,
        ]); ?>
    </div>

    <div id="tools">
        <?php function get_tools_section_output($file_name, $user, $data, $category_properties) { ?>
            <?php $enabled_tools_html = $disabled_tools_html = ''; ?>
            <?php foreach(require APP_PATH . 'includes/tools/' . $file_name . '.php' as $key => $value): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <?php ob_start() ?>
                    <?php
                    /* Determine the tool name / description */
                    if(isset($value['category']) && $value['category'] == 'data_converter') {
                        /* Process the tool */
                        $exploded = explode('_to_', $key);
                        $from = $exploded[0];
                        $to = $exploded[1];

                        $name = sprintf(l('tools.data_converter.name'), l('tools.' . $from), l('tools.' . $to));
                        $description = sprintf(l('tools.data_converter.description'), l('tools.' . $from), l('tools.' . $to));
                    } else {
                        $name = l('tools.' . $key . '.name');
                        $description = l('tools.' . $key . '.description');
                    }
                    ?>

                    <?= include_view(THEME_PATH . 'views/tools/tool_widget_' . (settings()->tools->style ?? 'frankfurt') . '.php', [
                        'tool_id' => $key,
                        'tool_icon' => $value['icon'],
                        'tools_usage' => $data->tools_usage,
                        'name' => $name,
                        'description' => $description,
                        'tool_category' => $file_name,
                    ]); ?>

                    <?php $enabled_tools_html .= ob_get_clean(); ?>
                <?php endif ?>
            <?php endforeach ?>

            <?php return ['enabled_tools_html' => $enabled_tools_html] ?>
        <?php } ?>

        <?php foreach(require APP_PATH . 'includes/tools/categories.php' as $tool => $tool_properties): ?>
            <?php ${$tool} = get_tools_section_output($tool, $this->user, $data, $tool_properties); ?>
            <?php if(empty(${$tool}['enabled_tools_html'])) continue; ?>

            <div class="card mt-5 mb-4 position-relative" data-category="<?= $tool ?>" style="background: <?= $tool_properties['color'] ?>; border-color: <?= $tool_properties['color'] ?>; color: white;" data-aos="fade-up">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex text-truncate">
                            <div class="d-flex align-items-center justify-content-center rounded mr-3 tool-icon" style="background: <?= $tool_properties['faded_color'] ?>;">
                                <i class="<?= $tool_properties['icon'] ?> fa-fw" style="color: <?= $tool_properties['color'] ?>"></i>
                            </div>

                            <div class="text-truncate ml-3">
                                <strong><?= l('tools.' . $tool) ?></strong>
                                <p class="text-truncate small m-0"><?= l('tools.' . $tool . '_help') ?></p>
                            </div>
                        </div>


                        <div class="ml-3">
                            <a href="#" class="stretched-link" data-toggle="collapse" data-target="<?= '#' . $tool . '_tools' ?>" style="color: white !important;" role="button" aria-expanded="false" aria-controls="<?=  $tool . '_tools' ?>" data-category-collapse-button>
                                <i class="fas fa-fw fa-lg fa-circle-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="<?= $tool . '_tools' ?>" class="row collapse" data-category-tools>
                <?php echo ${$tool}['enabled_tools_html']; ?>
            </div>
        <?php endforeach ?>

        <?php ob_start() ?>
        <script>
            'use strict';

            /* Prevent default form submission */
            document.querySelector('#search').addEventListener('submit', event => {
                event.preventDefault();
            });

            /* Cache references to repeated DOM elements */
            const tools_element = document.querySelector('#tools');
            const search_input_element = document.querySelector('#search input[name="search"]');

            /* Cache all data-category-tools elements */
            const data_category_tools_elements = [
                ...tools_element.querySelectorAll('[data-category-tools]')
            ];

            /* Cache all data-category-collapse-button elements */
            const data_category_collapse_button_elements = [
                ...tools_element.querySelectorAll('[data-category-collapse-button]')
            ];

            /* Cache all data-category headers */
            const data_category_header_elements = [
                ...tools_element.querySelectorAll('[data-category]')
            ];

            /* Build an array of tool info + a map of tool ID => DOM element */
            let tools = [];
            let tool_elements_map = {};

            tools_element.querySelectorAll('[data-tool-id]').forEach(element => {
                const tool_id = element.getAttribute('data-tool-id');
                const tool_name = element.getAttribute('data-tool-name').toLowerCase();
                const tool_category = element.getAttribute('data-tool-category').toLowerCase();

                tools.push({
                    id: tool_id,
                    name: tool_name,
                    category: tool_category
                });

                /* Map the tool ID to the actual DOM element for quick reference */
                tool_elements_map[tool_id] = element;
            });

            /* Keep the state of the current search value */
            let search_value = search_input_element.value.toLowerCase();

            /* Debounce on search */
            let timer = null;

            /* Attach the same events as in your original code */
            ['change', 'paste', 'keyup', 'search'].forEach(event_type => {
                search_input_element.addEventListener(event_type, () => {
                    clearTimeout(timer);

                    const string = search_input_element.value.toLowerCase();

                    /* Do not search if the value did not change */
                    if(string === search_value) {
                        return true;
                    }

                    /* Add loading state */
                    tools_element.classList.add('position-relative');

                    if(!document.querySelector('#tools-loading-overlay')) {
                        const overlay = document.createElement('div');
                        overlay.id = 'tools-loading-overlay';
                        overlay.classList.add('loading-overlay');
                        overlay.innerHTML = '<div class="spinner-border spinner-border-lg" role="status"></div>';
                        tools_element.prepend(overlay);
                    }

                    timer = setTimeout(() => {

                        /* Do not use collapse when searching */
                        data_category_tools_elements.forEach(element => {
                            if(string.length) {
                                element.classList.remove('collapse');
                            } else {
                                element.classList.add('collapse');
                            }
                        });

                        data_category_collapse_button_elements.forEach(element => {
                            if(string.length) {
                                element.classList.add('d-none');
                                element.classList.remove('stretched-link');
                            } else {
                                element.classList.remove('d-none');
                                element.classList.add('stretched-link');
                            }
                        });

                        /* Hide header sections if searching */
                        data_category_header_elements.forEach(element => {
                            element.removeAttribute('data-aos');

                            if(string.length) {
                                element.classList.add('d-none');
                            } else {
                                element.classList.remove('d-none');
                            }
                        });

                        /* Show/hide tools based on the search value */
                        for (let tool of tools) {
                            const tool_element = tool_elements_map[tool.id];

                            /* Remove data-aos if present */
                            if(tool_element.hasAttribute('data-aos')) {
                                tool_element.removeAttribute('data-aos');
                            }

                            if(tool.name.includes(string)) {
                                tool_element.classList.remove('d-none');

                                /* Also show the matching category header */
                                const matching_header = tools_element.querySelector(
                                    `[data-category="${tool.category}"]`
                                );
                                if(matching_header) {
                                    matching_header.classList.remove('d-none');
                                }
                            } else {
                                tool_element.classList.add('d-none');
                            }
                        }

                        /* Update the new search value */
                        search_value = string;

                        /* Remove loading state */
                        tools_element.classList.remove('position-relative');
                        const loading_overlay = document.querySelector('#tools-loading-overlay');
                        loading_overlay && loading_overlay.remove();

                        /* Check if any tool is visible */
                        const any_tool_visible = tools.some(tool =>
                            !tool_elements_map[tool.id].classList.contains('d-none')
                        );

                        /* Show or hide the #tools_not_found div */
                        const tools_not_found_element = document.querySelector('#tools_no_data');
                        if(any_tool_visible) {
                            tools_not_found_element.classList.add('d-none');
                        } else {
                            tools_not_found_element.classList.remove('d-none');
                        }

                    }, 300);
                });
            });
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
    </div>
</div>

<?php ob_start() ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "<?= l('index.title') ?>",
                    "item": "<?= url() ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?= l('tools.title') ?>",
                    "item": "<?= url('tools') ?>"
                }
            ]
        }
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
