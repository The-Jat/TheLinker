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

namespace Altum;

defined('ALTUMCODE') || die();

class Router {
    public static $params = [];
    public static $original_request = '';
    public static $original_request_query = '';
    public static $language_code = '';
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'wrapper' => 'wrapper',
        'no_authentication_check' => false,

        /* Enable / disable browser language detection & redirection */
        'no_browser_language_detection' => false,

        /* Enable / disable browser language detection & redirection */
        'allow_indexing' => true,

        /* Should we see a view for the controller? */
        'has_view' => true,

        /* Footer currency display */
        'currency_switcher' => false,

        /* If set on yes, ads won't show on these pages at all */
        'ads' => false,

        /* Authentication guard check (potential values: null, 'guest', 'user', 'admin') */
        'authentication' => null,

        /* Teams */
        'allow_team_access' => null,
    ];
    public static $method = 'index';
    public static $data = [];

    public static $routes = [
        'l' => [
            'link' => [
                'controller' => 'Link',
                'settings' => [
                    'no_authentication_check' => true,
                    'no_browser_language_detection' => true,
                    'ads' => true,
                ]
            ],

            'guest-payment-webhook' => [
                'controller' => 'GuestPaymentWebhook',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'guest-payment-download' => [
                'controller' => 'GuestPaymentDownload',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],
        ],

        '' => [
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'directory' => [
                'controller' => 'Directory',
                'settings' => [
                    'ads' => true,
                ]
            ],

            'biolinks-templates' => [
                'controller' => 'BiolinksTemplates',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'links' => [
                'controller' => 'Links',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'link-create' => [
                'controller' => 'LinkCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'projects' => [
                'controller' => 'Projects',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'project-create' => [
                'controller' => 'ProjectCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'project-update' => [
                'controller' => 'ProjectUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'splash-pages' => [
                'controller' => 'SplashPages',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'splash-page-create' => [
                'controller' => 'SplashPageCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'splash-page-update' => [
                'controller' => 'SplashPageUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'data' => [
                'controller' => 'Data',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'guests-payments-statistics' => [
                'controller' => 'GuestsPaymentsStatistics',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'guests-payments' => [
                'controller' => 'GuestsPayments',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'payment-processors' => [
                'controller' => 'PaymentProcessors',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'payment-processor-create' => [
                'controller' => 'PaymentProcessorCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'payment-processor-update' => [
                'controller' => 'PaymentProcessorUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'pixels' => [
                'controller' => 'Pixels',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'pixel-create' => [
                'controller' => 'PixelCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'pixel-update' => [
                'controller' => 'PixelUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'qr-codes' => [
                'controller' => 'QrCodes',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'qr-code-create' => [
                'controller' => 'QrCodeCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'qr-code-update' => [
                'controller' => 'QrCodeUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'qr-code-generator' => [
                'controller' => 'QrCodeGenerator',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'links-statistics' => [
                'controller' => 'LinksStatistics',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'link' => [
                'controller' => 'Link',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'link-redirect' => [
                'controller' => 'LinkRedirect',
                'settings' => [
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'biolink-block' => [
                'controller' => 'BiolinkBlock',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'domains' => [
                'controller' => 'Domains',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'domain-create' => [
                'controller' => 'DomainCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'domain-update' => [
                'controller' => 'DomainUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'tools' => [
                'controller' => 'Tools',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'tools-rating' => [
                'controller' => 'ToolsRating',
                'settings' => [
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'biolink-block-ajax' => [
                'controller' => 'BiolinkBlockAjax'
            ],

            'link-ajax' => [
                'controller' => 'LinkAjax'
            ],

            /* Email signatures */
            'signature-create' => [
                'controller' => 'SignatureCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'signature-update' => [
                'controller' => 'SignatureUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'signatures' => [
                'controller' => 'Signatures',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            /* AIX Plugin */
            'templates' => [
                'controller' => 'Templates',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'document-create' => [
                'controller' => 'DocumentCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'document-update' => [
                'controller' => 'DocumentUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'documents' => [
                'controller' => 'Documents',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'image-create' => [
                'controller' => 'ImageCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'image-update' => [
                'controller' => 'ImageUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'images' => [
                'controller' => 'Images',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'transcriptions' => [
                'controller' => 'Transcriptions',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'transcription-update' => [
                'controller' => 'TranscriptionUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'transcription-create' => [
                'controller' => 'TranscriptionCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                ]
            ],

            'chats' => [
                'controller' => 'Chats',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'chat' => [
                'controller' => 'Chat',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'chat-create' => [
                'controller' => 'ChatCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'syntheses' => [
                'controller' => 'Syntheses',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'synthesis-update' => [
                'controller' => 'SynthesisUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'synthesis-create' => [
                'controller' => 'SynthesisCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'notification-handlers' => [
                'controller' => 'NotificationHandlers',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'notification-handler-create' => [
                'controller' => 'NotificationHandlerCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'notification-handler-update' => [
                'controller' => 'NotificationHandlerUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'twiml' => [
                'controller' => 'Twiml',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            /* Common routes */
            'index' => [
               'controller' => 'Index',
                'settings' => [
                    'currency_switcher' => true,
                ],
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_browser_language_detection' => true,
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_browser_language_detection' => true,
                ]
            ],

            'affiliate' => [
                'controller' => 'Affiliate'
            ],

            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'blog' => [
                'controller' => 'Blog'
            ],

            'api-documentation' => [
                'controller' => 'ApiDocumentation',
            ],

            'contact' => [
                'controller' => 'Contact',
                'settings' => [
                    'allow_team_access' => false,
                ]
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'not-found' => [
                'controller' => 'NotFound',
            ],

            'maintenance' => [
                'controller' => 'Maintenance',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-preferences' => [
                'controller' => 'AccountPreferences',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-plan' => [
                'controller' => 'AccountPlan',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-redeem-code' => [
                'controller' => 'AccountRedeemCode',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-api' => [
                'controller' => 'AccountApi',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'account-delete' => [
                'controller' => 'AccountDelete',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'referrals' => [
                'controller' => 'Referrals',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                    'allow_team_access' => false,
                ]
            ],

            'plan' => [
               'controller' => 'Plan',
                'settings' => [
                    'currency_switcher' => true,
                ],
            ],

            'pay' => [
                'controller' => 'Pay',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                    'currency_switcher' => true,
                ]
            ],

            'pay-billing' => [
                'controller' => 'PayBilling',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                    'currency_switcher' => true,
                ]
            ],

            'pay-thank-you' => [
                'controller' => 'PayThankYou',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'allow_team_access' => false,
                    'currency_switcher' => true,
                ]
            ],

            'teams-system' => [
                'controller' => 'TeamsSystem',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'teams' => [
                'controller' => 'Teams',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'team-create' => [
                'controller' => 'TeamCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'team-update' => [
                'controller' => 'TeamUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'team' => [
                'controller' => 'Team',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'teams-members' => [
                'controller' => 'TeamsMembers',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'team-member-create' => [
                'controller' => 'TeamMemberCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'team-member-update' => [
                'controller' => 'TeamMemberUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'teams-member' => [
                'controller' => 'TeamsMember',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'ads' => true,
                    'allow_team_access' => false,
                ]
            ],

            'internal-notifications' => [
                'controller' => 'InternalNotifications',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'spotlight' => [
                'controller' => 'Spotlight',
                'settings' => [
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'push-subscribers' => [
                'controller' => 'PushSubscribers',
                'settings' => [
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'sso' => [
                'controller' => 'SSO',
                'settings' => [
                    'allow_team_access' => false,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            /* Webhooks */
            'webhook-paypal' => [
                'controller' => 'WebhookPaypal',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-coinbase' => [
                'controller' => 'WebhookCoinbase',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-payu' => [
                'controller' => 'WebhookPayu',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-iyzico' => [
                'controller' => 'WebhookIyzico',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-paystack' => [
                'controller' => 'WebhookPaystack',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-razorpay' => [
                'controller' => 'WebhookRazorpay',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-mollie' => [
                'controller' => 'WebhookMollie',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-yookassa' => [
                'controller' => 'WebhookYookassa',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-crypto-com' => [
                'controller' => 'WebhookCryptoCom',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-paddle' => [
                'controller' => 'WebhookPaddle',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-mercadopago' => [
                'controller' => 'WebhookMercadopago',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-midtrans' => [
                'controller' => 'WebhookMidtrans',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-flutterwave' => [
                'controller' => 'WebhookFlutterwave',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-lemonsqueezy' => [
                'controller' => 'WebhookLemonsqueezy',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'webhook-myfatoorah' => [
                'controller' => 'WebhookMyfatoorah',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            /* Others */
            'cookie-consent' => [
                'controller' => 'CookieConsent',
                'settings' => [
                    'no_authentication_check' => true,
                    'no_browser_language_detection' => true,
                ]
            ],

            'sitemap' => [
                'controller' => 'Sitemap',
                'settings' => [
                    'no_authentication_check' => true,
                    'no_browser_language_detection' => true,
                    'has_view' => false,
                ]
            ],

            'cron' => [
                'controller' => 'Cron',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                    'allow_indexing' => false,
                ]
            ],

            'broadcast' => [
                'controller' => 'Broadcast',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],
        ],

        'api' => [
            'notification-handlers' => [
                'controller' => 'ApiNotificationHandlers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'links' => [
                'controller' => 'ApiLinks',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'statistics' => [
                'controller' => 'ApiStatistics',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'projects' => [
                'controller' => 'ApiProjects',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'pixels' => [
                'controller' => 'ApiPixels',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'splash-pages' => [
                'controller' => 'ApiSplashPages',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'qr-codes' => [
                'controller' => 'ApiQrCodes',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'data' => [
                'controller' => 'ApiData',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'domains' => [
                'controller' => 'ApiDomains',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'signatures' => [
                'controller' => 'ApiSignatures',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            /* Common routes */
            'teams' => [
                'controller' => 'ApiTeams',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'teams-member' => [
                'controller' => 'ApiTeamsMember',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'team-members' => [
                'controller' => 'ApiTeamMembers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'user' => [
                'controller' => 'ApiUser',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'payments' => [
                'controller' => 'ApiPayments',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
            'logs' => [
                'controller' => 'ApiLogs',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
        ],

        /* Admin Panel */
        'admin' => [
            'links' => [
                'controller' => 'AdminLinks'
            ],

            'biolinks-blocks' => [
                'controller' => 'AdminBiolinksBlocks'
            ],

            'biolinks-themes' => [
                'controller' => 'AdminBiolinksThemes'
            ],

            'biolink-theme-create' => [
                'controller' => 'AdminBiolinkThemeCreate'
            ],

            'biolink-theme-update' => [
                'controller' => 'AdminBiolinkThemeUpdate'
            ],

            'biolinks-templates' => [
                'controller' => 'AdminBiolinksTemplates'
            ],

            'biolink-template-create' => [
                'controller' => 'AdminBiolinkTemplateCreate'
            ],

            'biolink-template-update' => [
                'controller' => 'AdminBiolinkTemplateUpdate'
            ],

            'signatures' => [
                'controller' => 'AdminSignatures',
            ],

            'templates-categories' => [
                'controller' => 'AdminTemplatesCategories',
            ],

            'template-category-create' => [
                'controller' => 'AdminTemplateCategoryCreate',
            ],

            'template-category-update' => [
                'controller' => 'AdminTemplateCategoryUpdate',
            ],

            'templates' => [
                'controller' => 'AdminTemplates',
            ],

            'template-create' => [
                'controller' => 'AdminTemplateCreate',
            ],

            'template-update' => [
                'controller' => 'AdminTemplateUpdate',
            ],

            'documents' => [
                'controller' => 'AdminDocuments',
            ],

            'images' => [
                'controller' => 'AdminImages',
            ],

            'transcriptions' => [
                'controller' => 'AdminTranscriptions',
            ],

            'chats-assistants' => [
                'controller' => 'AdminChatsAssistants',
            ],

            'chat-assistant-create' => [
                'controller' => 'AdminChatAssistantCreate',
            ],

            'chat-assistant-update' => [
                'controller' => 'AdminChatAssistantUpdate',
            ],

            'chats' => [
                'controller' => 'AdminChats',
            ],

            'syntheses' => [
                'controller' => 'AdminSyntheses',
            ],

            'projects' => [
                'controller' => 'AdminProjects'
            ],

            'splash-pages' => [
                'controller' => 'AdminSplashPages'
            ],

            'data' => [
                'controller' => 'AdminData'
            ],

            'payment-processors' => [
                'controller' => 'AdminPaymentProcessors'
            ],

            'guests-payments' => [
                'controller' => 'AdminGuestsPayments'
            ],

            'pixels' => [
                'controller' => 'AdminPixels'
            ],

            'qr-codes' => [
                'controller' => 'AdminQrCodes'
            ],

            'notification-handlers' => [
                'controller' => 'AdminNotificationHandlers',
            ],

            'domains' => [
                'controller' => 'AdminDomains'
            ],

            'domain-create' => [
                'controller' => 'AdminDomainCreate'
            ],

            'domain-update' => [
                'controller' => 'AdminDomainUpdate'
            ],

            /* Common routes */
            'index' => [
                'controller' => 'AdminIndex'
            ],

            'users' => [
                'controller' => 'AdminUsers'
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate'
            ],

            'user-view' => [
                'controller' => 'AdminUserView'
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate'
            ],

            'users-logs' => [
                'controller' => 'AdminUsersLogs',
            ],

            'redeemed-codes' => [
                'controller' => 'AdminRedeemedCodes',
            ],

            'blog-posts' => [
                'controller' => 'AdminBlogPosts'
            ],

            'blog-post-create' => [
                'controller' => 'AdminBlogPostCreate'
            ],

            'blog-post-update' => [
                'controller' => 'AdminBlogPostUpdate'
            ],

            'blog-posts-categories' => [
                'controller' => 'AdminBlogPostsCategories'
            ],

            'blog-posts-category-create' => [
                'controller' => 'AdminBlogPostsCategoryCreate'
            ],

            'blog-posts-category-update' => [
                'controller' => 'AdminBlogPostsCategoryUpdate'
            ],

            'pages' => [
                'controller' => 'AdminPages'
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate'
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate'
            ],

            'pages-categories' => [
                'controller' => 'AdminPagesCategories'
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate'
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate'
            ],

            'plans' => [
                'controller' => 'AdminPlans'
            ],

            'plan-create' => [
                'controller' => 'AdminPlanCreate'
            ],

            'plan-update' => [
                'controller' => 'AdminPlanUpdate'
            ],

            'codes' => [
                'controller' => 'AdminCodes'
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate'
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate'
            ],

            'taxes' => [
                'controller' => 'AdminTaxes'
            ],

            'tax-create' => [
                'controller' => 'AdminTaxCreate'
            ],

            'tax-update' => [
                'controller' => 'AdminTaxUpdate'
            ],

            'affiliates-withdrawals' => [
                'controller' => 'AdminAffiliatesWithdrawals',
            ],

            'payments' => [
                'controller' => 'AdminPayments'
            ],

            'statistics' => [
                'controller' => 'AdminStatistics'
            ],

            'plugins' => [
                'controller' => 'AdminPlugins',
            ],

            'languages' => [
                'controller' => 'AdminLanguages'
            ],

            'language-create' => [
                'controller' => 'AdminLanguageCreate'
            ],

            'language-update' => [
                'controller' => 'AdminLanguageUpdate'
            ],

            'settings' => [
                'controller' => 'AdminSettings'
            ],

            'api-documentation' => [
                'controller' => 'AdminApiDocumentation',
            ],

            'teams' => [
                'controller' => 'AdminTeams',
            ],

            'team-members' => [
                'controller' => 'AdminTeamMembers',
            ],

            'logs' => [
                'controller' => 'AdminLogs',
            ],

            'log' => [
                'controller' => 'AdminLog',
            ],

            'log-download' => [
                'controller' => 'AdminLogDownload',
                'settings' => [
                    'has_view' => false,
                ]
            ],

            'broadcasts' => [
                'controller' => 'AdminBroadcasts',
            ],

            'broadcast-view' => [
                'controller' => 'AdminBroadcastView',
            ],

            'broadcast-create' => [
                'controller' => 'AdminBroadcastCreate',
            ],

            'broadcast-update' => [
                'controller' => 'AdminBroadcastUpdate',
            ],

            'internal-notifications' => [
                'controller' => 'AdminInternalNotifications',
            ],

            'internal-notification-create' => [
                'controller' => 'AdminInternalNotificationCreate',
            ],

            'push-subscribers' => [
                'controller' => 'AdminPushSubscribers',
            ],

            'push-notifications' => [
                'controller' => 'AdminPushNotifications',
            ],

            'push-notification-create' => [
                'controller' => 'AdminPushNotificationCreate',
            ],

            'push-notification-update' => [
                'controller' => 'AdminPushNotificationUpdate',
            ],

            'invoice' => [
                'controller' => 'AdminInvoice',
            ],

            'dynamic-og-images' => [
                'controller' => 'AdminDynamicOgImages',
            ],

            'image-optimizer' => [
                'controller' => 'AdminImageOptimizer',
            ],
        ],

        'admin-api' => [
            'users' => [
                'controller' => 'AdminApiUsers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'payments' => [
                'controller' => 'AdminApiPayments',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'plans' => [
                'controller' => 'AdminApiPlans',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'sso' => [
                'controller' => 'AdminApiSSO',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'dynamic-og-images' => [
                'controller' => 'AdminApiDynamicOgImages',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],

            'domains' => [
                'controller' => 'AdminApiDomains',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'allow_indexing' => false,
                ]
            ],
        ],
    ];


    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', input_clean(rtrim($_GET['altum'], '/')));
        }

        if(php_sapi_name() == 'cli' && isset($_SERVER['argv'])) {
            $params = explode('/', input_clean(rtrim($_SERVER['argv'][1] ?? '', '/')));
            parse_str(implode('&', array_slice($_SERVER['argv'], 2)), $_GET);
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_language() {

        /* Check for potential language set in the first parameter */
        if(!empty(self::$params[0]) && in_array(self::$params[0], Language::$active_languages)) {

            /* Set the language */
            $language_code = input_clean(self::$params[0]);
            Language::set_by_code($language_code);
            self::$language_code = $language_code;

            /* Unset the parameter so that it wont be used further */
            unset(self::$params[0]);
            self::$params = array_values(self::$params);

        }

    }

    public static function parse_controller() {

        self::$original_request = input_clean(implode('/', self::$params));
        self::$original_request_query = http_build_query(array_diff_key($_GET, array_flip(['altum'])));

        /* Check if the current link accessed is actually the original url or not (multi domain use) */
        $original_url_host = parse_url(url(), PHP_URL_HOST);
        $request_url_host = input_clean($_SERVER['HTTP_HOST']);

        if(!empty($request_url_host) && $original_url_host != $request_url_host) {
            if(function_exists('idn_to_utf8')) {
                $request_url_host = idn_to_utf8($request_url_host);
            }

            /* Make sure the custom domain is attached */
            $domain = (new \Altum\Models\Domain())->get_domain_by_host($request_url_host);;

            if($domain && $domain->is_enabled) {
                self::$controller_key = 'link';
                self::$controller = 'Link';
                self::$path = 'l';

                /* Set some route data */
                self::$data['domain'] = $domain;
            }

        }

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if(in_array(self::$params[0], ['admin', 'admin-api', 'l', 'api']) && $original_url_host == $request_url_host) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Try to check if the link exists via the cache */
                $cache_instance = cache()->getItem('link?url=' . md5(self::$params[0]) . (isset(self::$data['domain']) ? '&domain_id=' . self::$data['domain']->domain_id : null));

                /* Set cache if not existing */
                if(!$cache_instance->get()) {

                    /* Get data from the database */
                    if(isset(self::$data['domain'])) {
                        if(self::$data['domain']->link_id) {
                            $link = db()->where('link_id', self::$data['domain']->link_id)->getOne('links');
                        } else {
                            $link = db()->where('url', self::$params[0])->where('domain_id', self::$data['domain']->domain_id)->getOne('links');
                        }
                    } else {
                        $link = db()->where('url', self::$params[0])->where('domain_id', 0)->getOne('links');
                    }

                    if($link) {
                        cache()->save($cache_instance->set($link)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('link_id=' . $link->link_id));

                        /* Set some route data */
                        self::$data['link'] = $link;

                        /* Check for 66pusher service worker integration */
                        // To do add support for custom domains with exclusive links
                        if(settings()->links->sixsixpusher_is_enabled && isset(self::$params[1]) && self::$params[1] == settings()->links->sixsixpusher_service_worker_file_name . '.js') {
                            $link->settings = json_decode($link->settings);

                            header('Content-Type: application/javascript');
                            header('Service-Worker-Allowed: /');

                            include \Altum\Uploads::get_full_path('service_workers') . $link->settings->service_worker;
                            die();
                        }
                    }

                } else {

                    /* Get cache */
                    $link = $cache_instance->get();

                    /* Set some route data */
                    self::$data['link'] = $link;

                }

                /* Check if there is any link available in the database */
                if($link) {

                    self::$controller_key = 'link';
                    self::$controller = 'Link';
                    self::$path = 'l';

                } else {

                    /* Check for a custom domain 404 redirect */
                    if(isset(self::$data['domain']) && self::$data['domain']->custom_not_found_url) {
                        header('Location: ' . self::$data['domain']->custom_not_found_url);
                        die();
                    }

                    else {
                        /* Not found controller */
                        self::$path = '';
                        self::$controller_key = 'not-found';
                    }

                }

            }

        }

        /* Check for a potential exclusive domain name link */
        if(empty(self::$params[0]) && isset(self::$data['domain']) && self::$data['domain']->link_id) {

            /* Try to check if the link exists via the cache */
            $cache_instance = cache()->getItem('link?link_id=' . self::$data['domain']->link_id);

            /* Set cache if not existing */
            if(!$cache_instance->get()) {

                /* Get data from the database */
                $link = db()->where('link_id', self::$data['domain']->link_id)->getOne('links');

                if($link) {
                    cache()->save($cache_instance->set($link)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('link_id=' . $link->link_id));

                    /* Set some route data */
                    self::$data['link'] = $link;
                }

            } else {

                /* Get cache */
                $link = $cache_instance->get();

                /* Set some route data */
                self::$data['link'] = $link;

            }

            /* Check if there is any link available in the database */
            if($link) {

                self::$controller_key = 'link';
                self::$controller = 'Link';
                self::$path = 'l';

            }

        }

        /* Check for a custom index url redirect in case there is no link requested  */
        if(isset(self::$data['domain']) && !self::$data['domain']->custom_index_url) {
            self::$data['domain']->custom_index_url = url();
        }

        if(!isset(self::$params[0]) && !isset(self::$params[1]) && self::$path == 'l' && $original_url_host != $request_url_host && isset(self::$data['domain']) && !self::$data['domain']->link_id) {
            header('Location: ' . self::$data['domain']->custom_index_url);
            die();
        }

        /* Save the current controller */
        if(!isset(self::$routes[self::$path][self::$controller_key])) {
            /* Not found controller */
            self::$path = '';
            self::$controller_key = 'not-found';
        }
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Admin path */
        if(self::$path == 'admin' && !isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$routes[self::$path][self::$controller_key]['settings'] = [
                'authentication' => 'admin',
                'allow_team_access' => false,
            ];
        }

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_name, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_name . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_name;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Start the checks for existing potential methods */
        if(isset(self::get_params()[0])) {

            $original_first_param = self::$params[0];

            /* Try to check the methods with prettier URLs */
            self::$params[0] = str_replace('-', '_', self::$params[0]);

            /* Make sure to check the class method if set in the url */
            if(method_exists($controller, self::get_params()[0])) {

                /* Make sure the method is not private */
                $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
                if($reflection->isPublic()) {
                    $method = self::get_params()[0];
                    unset(self::$params[0]);
                }

            }

            /* Restore pretty URL if not used */
            else {
                self::$params[0] = $original_first_param;
            }
        }

        return self::$method = $method;

    }

}
