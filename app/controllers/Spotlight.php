<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Response;

defined('ALTUMCODE') || die();

class Spotlight extends Controller {

    public function index() {

        if(!settings()->main->admin_spotlight_is_enabled && !settings()->main->user_spotlight_is_enabled) {
            redirect('not-found');
        }

        if(!empty($_POST)) {
            redirect();
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        $available_pages = [];

        $available_pages[] = [
            'name' => l('index.title'),
            'url' => ''
        ];

        if(!is_logged_in()) {
            $available_pages[] = [
                'name' => l('login.title'),
                'url' => 'login'
            ];

            if(settings()->users->register_is_enabled) {
                $available_pages[] = [
                    'name' => l('register.title'),
                    'url' => 'register'
                ];
            }

            if(settings()->users->email_confirmation) {
                $available_pages[] = [
                    'name' => l('resend_activation.title'),
                    'url' => 'resend-activation'
                ];
            }

            $available_pages[] = [
                'name' => l('lost_password.title'),
                'url' => 'lost-password'
            ];

            if(settings()->payment->is_enabled) {
                $available_pages[] = [
                    'name' => l('plan.title'),
                    'url' => 'plan'
                ];
            }
        }

        if(is_logged_in()) {
            $available_pages[] = [
                'name' => l('dashboard.title'),
                'url' => 'dashboard'
            ];

            /* Per product */
            if(settings()->links->biolinks_is_enabled) {
                $available_pages[] = [
                    'name' => l('links.menu.biolink'),
                    'url'  => 'links?type=biolink'
                ];
            }

            if(settings()->links->shortener_is_enabled) {
                $available_pages[] = [
                    'name' => l('link_create.menu'),
                    'url'  => 'link-create'
                ];

                $available_pages[] = [
                    'name' => l('links.menu.link'),
                    'url'  => 'links?type=link'
                ];
            }

            if(settings()->links->files_is_enabled) {
                $available_pages[] = [
                    'name' => l('links.menu.file'),
                    'url'  => 'links?type=file'
                ];
            }

            if(settings()->links->vcards_is_enabled) {
                $available_pages[] = [
                    'name' => l('links.menu.vcard'),
                    'url'  => 'links?type=vcard'
                ];
            }

            if(settings()->links->events_is_enabled) {
                $available_pages[] = [
                    'name' => l('links.menu.event'),
                    'url'  => 'links?type=event'
                ];
            }

            if(settings()->links->static_is_enabled) {
                $available_pages[] = [
                    'name' => l('links.menu.static'),
                    'url'  => 'links?type=static'
                ];
            }

            if(settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled) {
                $available_pages[] = [
                    'name' => l('links_statistics.title'),
                    'url'  => 'links-statistics'
                ];
            }

            if(settings()->links->projects_is_enabled) {
                $available_pages[] = [
                    'name' => l('projects.title'),
                    'url' => 'projects'
                ];
                $available_pages[] = [
                    'name' => l('project_create.title'),
                    'url' => 'project-create'
                ];
            }

            if(settings()->links->splash_page_is_enabled) {
                $available_pages[] = [
                    'name' => l('splash_pages.title'),
                    'url' => 'splash-pages'
                ];

                $available_pages[] = [
                    'name' => l('splash_page_create.title'),
                    'url' => 'splash-page-create'
                ];
            }


            if(settings()->links->biolinks_is_enabled) {
                $available_pages[] = [
                    'name' => l('data.title'),
                    'url' => 'data'
                ];
                $available_pages[] = [
                    'name' => l('biolinks_templates.title'),
                    'url' => 'biolinks-templates'
                ];
            }

            if(settings()->links->biolinks_is_enabled && settings()->links->directory_is_enabled && (settings()->links->directory_access == 'everyone' || (settings()->links->directory_access == 'users' && is_logged_in()))) {
                $available_pages[] = [
                    'name' => l('directory.title'),
                    'url' => 'directory'
                ];
            }


            if(\Altum\Plugin::is_active('payment-blocks')) {
                $available_pages[] = [
                    'name' => l('guests_payments_statistics.title'),
                    'url' => 'guests-payments-statistics'
                ];
                $available_pages[] = [
                    'name' => l('guests_payments.title'),
                    'url' => 'guests-payments'
                ];

                $available_pages[] = [
                    'name' => l('payment_processors.title'),
                    'url' => 'payment-processors'
                ];
                $available_pages[] = [
                    'name' => l('payment_processor_create.title'),
                    'url' => 'payment-processor-create'
                ];
            }

            if(settings()->links->pixels_is_enabled) {
                $available_pages[] = [
                    'name' => l('pixels.title'),
                    'url' => 'pixels'
                ];
                $available_pages[] = [
                    'name' => l('pixel_create.title'),
                    'url' => 'pixel-create'
                ];
            }

            if(settings()->codes->qr_codes_is_enabled) {
                $available_pages[] = [
                    'name' => l('qr_codes.title'),
                    'url' => 'qr-codes'
                ];
                $available_pages[] = [
                    'name' => l('qr_code_create.title'),
                    'url' => 'qr-code-create'
                ];
            }

            $available_pages[] = [
                'name' => l('notification_handlers.title'),
                'url'  => 'notification-handlers'
            ];
            $available_pages[] = [
                'name' => l('notification_handler_create.title'),
                'url'  => 'notification-handler-create'
            ];

            if(settings()->links->domains_is_enabled) {
                $available_pages[] = [
                    'name' => l('domains.title'),
                    'url' => 'domains'
                ];
                $available_pages[] = [
                    'name' => l('domain_create.title'),
                    'url' => 'domain-create'
                ];
            }

            if(settings()->tools->is_enabled && (settings()->tools->access == 'everyone' || (settings()->tools->access == 'users' && is_logged_in()))) {
                $available_pages[] = [
                    'name' => l('tools.title'),
                    'url' => 'tools'
                ];
            }

            if(\Altum\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled) {
                $available_pages[] = [
                    'name' => l('signature_create.title'),
                    'url' => 'signature-create'
                ];
                $available_pages[] = [
                    'name' => l('signatures.title'),
                    'url' => 'signatures'
                ];
            }

            if(\Altum\Plugin::is_active('aix') && settings()->aix->documents_is_enabled) {
                $available_pages[] = [
                    'name' => l('document_create.title'),
                    'url' => 'document-create'
                ];
                $available_pages[] = [
                    'name' => l('documents.title'),
                    'url' => 'documents'
                ];
                $available_pages[] = [
                    'name' => l('templates.title'),
                    'url'  => 'templates'
                ];
            }

            if(\Altum\Plugin::is_active('aix') && settings()->aix->images_is_enabled) {
                $available_pages[] = [
                    'name' => l('image_create.title'),
                    'url' => 'image-create'
                ];
                $available_pages[] = [
                    'name' => l('images.title'),
                    'url' => 'images'
                ];
            }

            if(\Altum\Plugin::is_active('aix') && settings()->aix->transcriptions_is_enabled) {
                $available_pages[] = [
                    'name' => l('transcriptions.title'),
                    'url' => 'transcriptions'
                ];
                $available_pages[] = [
                    'name' => l('transcription_create.title'),
                    'url' => 'transcription-create'
                ];
            }

            if(\Altum\Plugin::is_active('aix') && settings()->aix->chats_is_enabled) {
                $available_pages[] = [
                    'name' => l('chats.title'),
                    'url' => 'chats'
                ];
                $available_pages[] = [
                    'name' => l('chat_create.title'),
                    'url' => 'chat-create'
                ];
            }

            if(\Altum\Plugin::is_active('aix') && settings()->aix->syntheses_is_enabled) {
                $available_pages[] = [
                    'name' => l('syntheses.title'),
                    'url' => 'syntheses'
                ];
                $available_pages[] = [
                    'name' => l('synthesis_create.title'),
                    'url' => 'synthesis-create'
                ];
            }







            $available_pages[] = [
                'name' => l('account.title'),
                'url' => 'account'
            ];

            $available_pages[] = [
                'name' => l('account_preferences.title'),
                'url' => 'account-preferences'
            ];

            $available_pages[] = [
                'name' => l('account_plan.title'),
                'url' => 'account-plan'
            ];

            if(\Altum\Plugin::is_active('teams')) {
                $available_pages[] = [
                    'name' => l('teams_system.title'),
                    'url' => 'teams-system'
                ];

                $available_pages[] = [
                    'name' => l('teams.title'),
                    'url' => 'teams'
                ];

                $available_pages[] = [
                    'name' => l('teams_member.title'),
                    'url' => 'teams-member'
                ];
            }

            if(settings()->payment->is_enabled) {

                if(settings()->payment->codes_is_enabled) {
                    $available_pages[] = [
                        'name' => l('account_redeem_code.title'),
                        'url' => 'account-redeem-code'
                    ];
                }

                $available_pages[] = [
                    'name' => l('account_payments.title'),
                    'url' => 'account-payments'
                ];

                if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled) {
                    $available_pages[] = [
                        'name' => l('referrals.title'),
                        'url' => 'referrals'
                    ];
                }
            }

            if(settings()->main->api_is_enabled) {
                $available_pages[] = [
                    'name' => l('account_api.title'),
                    'url' => 'account-api'
                ];
            }

            $available_pages[] = [
                'name' => l('account_logs.title'),
                'url' => 'account-logs'
            ];

            $available_pages[] = [
                'name' => l('account_delete.title'),
                'url' => 'account-delete'
            ];

            if(user()->type == 1) {
                if(file_exists(APP_PATH . 'languages/admin/' . \Altum\Language::$name . '#' . \Altum\Language::$code . '.php')) {
                    $admin_language = require APP_PATH . 'languages/admin/' . \Altum\Language::$name . '#' . \Altum\Language::$code . '.php';
                    \Altum\Language::$languages[\Altum\Language::$name]['content'] = \Altum\Language::$languages[\Altum\Language::$name]['content'] + $admin_language;
                }

                $available_pages[] = [
                    'name' => l('admin_index.title'),
                    'url' => 'admin'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_users.title'),
                    'url' => 'admin/users'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_user_create.title'),
                    'url' => 'admin/user-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_users_logs.title'),
                    'url' => 'admin/users-logs'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_redeemed_codes.title'),
                    'url' => 'admin/redeemed-codes'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_blog_posts.title'),
                    'url' => 'admin/blog-posts'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_blog_post_create.title'),
                    'url' => 'admin/blog-post-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_blog_posts_categories.title'),
                    'url' => 'admin/blog-posts-categories'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_blog_posts_category_create.title'),
                    'url' => 'admin/blog-posts-category-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_pages.title'),
                    'url' => 'admin/pages'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_page_create.title'),
                    'url' => 'admin/page-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_pages_categories.title'),
                    'url' => 'admin/pages-categories'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_pages_category_create.title'),
                    'url' => 'admin/pages-category-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_plans.title'),
                    'url' => 'admin/plans'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_plan_create.title'),
                    'url' => 'admin/plan-create'
                ];

                if(in_array(settings()->license->type, ['SPECIAL','Extended License', 'extended'])) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_codes.title'),
                        'url' => 'admin/codes'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_code_create.title'),
                        'url' => 'admin/code-create'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_taxes.title'),
                        'url' => 'admin/taxes'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_tax_create.title'),
                        'url' => 'admin/tax-create'
                    ];

                    if(\Altum\Plugin::is_active('affiliate')) {
                        $available_pages[] = [
                            'name' => l('global.menu.admin') . ' - ' . l('admin_affiliates_withdrawals.title'),
                            'url' => 'admin/affiliates-withdrawals'
                        ];
                    }

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_payments.title'),
                        'url' => 'admin/payments'
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_statistics.menu'),
                    'url' => 'admin/statistics'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_plugins.title'),
                    'url' => 'admin/plugins'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_languages.title'),
                    'url' => 'admin/languages'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_language_create.title'),
                    'url' => 'admin/language-create'
                ];

                $pages = [
                    'main',
                    'users',
                    'content'
                ];

                foreach ($pages as $page) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.' . $page . '.tab')),
                        'url'  => 'admin/settings/' . $page
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.links.tab')),
                    'url'  => 'admin/settings/links'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.tools.tab')),
                    'url'  => 'admin/settings/tools'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.codes.tab')),
                    'url'  => 'admin/settings/codes'
                ];

                if(\Altum\Plugin::is_active('email-signatures')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.signatures.tab')),
                        'url'  => 'admin/settings/signatures'
                    ];
                }

                if(\Altum\Plugin::is_active('aix')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.aix.tab')),
                        'url'  => 'admin/settings/aix'
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.payment.tab')),
                    'url'  => 'admin/settings/payment'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.business.tab')),
                    'url'  => 'admin/settings/business'
                ];

                foreach(require APP_PATH . 'includes/payment_processors.php' as $key => $value) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.' . $key . '.tab')),
                        'url'  => 'admin/settings/' . $key
                    ];
                }

                $pages = [
                    'affiliate',
                    'captcha',
                    'facebook',
                    'google',
                    'twitter',
                    'discord',
                    'linkedin',
                    'microsoft',
                    'ads',
                    'cookie_consent',
                    'socials',
                    'smtp',
                    'theme',
                    'custom',
                    'custom_images',
                    'announcements',
                    'internal_notifications',
                    'email_notifications',
                    'push_notifications',
                    'webhooks',
                    'offload',
                    'pwa',
                    'image_optimizer',
                    'dynamic_og_images',
                    'sso',
                    'cron',
                    'health',
                    'cache',
                    'license',
                    'support'
                ];

                foreach ($pages as $page) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . sprintf(l('admin_settings.title'), l('admin_settings.' . $page . '.tab')),
                        'url'  => 'admin/settings/' . $page
                    ];
                }


                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_api_documentation.title'),
                    'url' => 'admin/api-documentation'
                ];

                if(\Altum\Plugin::is_active('teams')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_teams.title'),
                        'url' => 'admin/teams'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_team_members.title'),
                        'url' => 'admin/team-members'
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_broadcasts.title'),
                    'url' => 'admin/broadcasts'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_broadcast_create.title'),
                    'url' => 'admin/broadcast-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_internal_notifications.title'),
                    'url' => 'admin/internal-notifications'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_internal_notification_create.title'),
                    'url' => 'admin/internal-notification-create'
                ];

                if(\Altum\Plugin::is_active('push-notifications')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_push_subscribers.title'),
                        'url' => 'admin/push-subscribers'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_push_notifications.title'),
                        'url' => 'admin/push-notifications'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_push_notification_create.title'),
                        'url' => 'admin/push-notification-create'
                    ];
                }

                if(\Altum\Plugin::is_active('image-optimizer')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_image_optimizer.title'),
                        'url' => 'admin/image-optimizer'
                    ];
                }

                if(\Altum\Plugin::is_active('dynamic-og-images')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_dynamic_og_images.title'),
                        'url' => 'admin/dynamic-og-images'
                    ];
                }

                /* Per product */
                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_links.title'),
                    'url' => 'admin/links'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_biolinks_blocks.title'),
                    'url' => 'admin/biolinks-blocks'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_biolinks_themes.title'),
                    'url' => 'admin/biolinks-themes'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_biolink_theme_create.title'),
                    'url' => 'admin/biolink-theme-create'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_biolinks_templates.title'),
                    'url' => 'admin/biolinks-templates'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_biolink_template_create.title'),
                    'url' => 'admin/biolink-template-create'
                ];

                if(\Altum\Plugin::is_active('email-signatures')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_signatures.title'),
                        'url' => 'admin/signatures'
                    ];
                }

                if(\Altum\Plugin::is_active('aix')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_templates_categories.title'),
                        'url' => 'admin/templates-categories'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_template_category_create.title'),
                        'url' => 'admin/template-category-create'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_templates.title'),
                        'url' => 'admin/templates'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_template_create.title'),
                        'url' => 'admin/template-create'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_documents.title'),
                        'url' => 'admin/documents'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_images.title'),
                        'url' => 'admin/images'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_transcriptions.title'),
                        'url' => 'admin/transcriptions'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_chats_assistants.title'),
                        'url' => 'admin/chats-assistants'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_chat_assistant_create.title'),
                        'url' => 'admin/chat-assistant-create'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_chats.title'),
                        'url' => 'admin/chats'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_syntheses.title'),
                        'url' => 'admin/syntheses'
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_projects.title'),
                    'url' => 'admin/projects'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_splash_pages.title'),
                    'url' => 'admin/splash-pages'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_data.title'),
                    'url' => 'admin/data'
                ];

                if(\Altum\Plugin::is_active('payment-blocks')) {
                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_payment_processors.title'),
                        'url' => 'admin/payment-processors'
                    ];

                    $available_pages[] = [
                        'name' => l('global.menu.admin') . ' - ' . l('admin_guests_payments.title'),
                        'url' => 'admin/guests-payments'
                    ];
                }

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_pixels.title'),
                    'url' => 'admin/pixels'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_qr_codes.title'),
                    'url' => 'admin/qr-codes'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_domains.title'),
                    'url' => 'admin/domains'
                ];

                $available_pages[] = [
                    'name' => l('global.menu.admin') . ' - ' . l('admin_domain_create.title'),
                    'url' => 'admin/domain-create'
                ];





            }

            $available_pages[] = [
                'name' => l('global.menu.logout'),
                'url' => 'logout'
            ];
        }

        if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)) {
            $available_pages[] = [
                'name' => l('contact.title'),
                'url' => 'contact'
            ];
        }

        if(settings()->main->api_is_enabled) {
            $available_pages[] = [
                'name' => l('api_documentation.title'),
                'url' => 'api-documentation'
            ];
        }

        if(settings()->payment->is_enabled) {
            if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled) {
                $available_pages[] = [
                    'name' => l('affiliate.title'),
                    'url' => 'affiliate'
                ];
            }
        }

        if(settings()->content->blog_is_enabled) {
            $available_pages[] = [
                'name' => l('blog.title'),
                'url' => 'blog'
            ];
        }

        if(settings()->content->pages_is_enabled) {
            $available_pages[] = [
                'name' => l('pages.title'),
                'url' => 'pages'
            ];
        }

        Response::json('', 'success', $available_pages);

    }

}
