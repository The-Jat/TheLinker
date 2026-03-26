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

defined('ALTUMCODE') || die();

return [
    'paypal' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fab fa-paypal',
        'color' => '#3b7bbf',
        'dark_color' => '#7fb3ff',
    ],
    'stripe' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fab fa-stripe',
        'color' => '#5433FF',
        'dark_color' => '#9a86ff',
    ],
    'offline_payment' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-university',
        'color' => '#393f4a',
        'dark_color' => '#9aa1ad',
    ],
    'coinbase' => [
        'payment_type' => ['one_time'],
        'icon' => 'fab fa-bitcoin',
        'color' => '#0050FF',
        'dark_color' => '#6fa3ff',
    ],
    'payu' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-underline',
        'color' => '#A6C306',
        'dark_color' => '#d6ea5a',
    ],
    'iyzico' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-teeth',
        'color' => '#1E64FF',
        'dark_color' => '#7fa6ff',
    ],
    'paystack' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-money-check',
        'color' => '#00C3F7',
        'dark_color' => '#6fe2ff',
    ],
    'razorpay' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-heart',
        'color' => '#2b84ea',
        'dark_color' => '#7fb4f5',
    ],
    'mollie' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-shopping-basket',
        'color' => '#465975',
        'dark_color' => '#9fb0cc',
    ],
    'yookassa' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-ruble-sign',
        'color' => '#004CAA',
        'dark_color' => '#6fa3e6',
    ],
    'crypto_com' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-coins',
        'color' => '#4b71d7',
        'dark_color' => '#8fa7ff',
    ],
    'paddle' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-star',
        'color' => '#a6b0b9',
        'dark_color' => '#d6dde3',
    ],
    'paddle_billing' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-star',
        'color' => '#a6b0b9',
        'dark_color' => '#d6dde3',
    ],
    'mercadopago' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-handshake',
        'color' => '#009EE3',
        'dark_color' => '#6fd0ff',
    ],
    'midtrans' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-grip-vertical',
        'color' => '#002855',
        'dark_color' => '#6f9fd1',
    ],
    'flutterwave' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-water',
        'color' => '#FB9129',
        'dark_color' => '#ffbf7a',
    ],
    'lemonsqueezy' => [
        'payment_type' => ['one_time', 'recurring'],
        'icon' => 'fas fa-lemon',
        'color' => '#F5C518',
        'dark_color' => '#ffe07a',
    ],
    'myfatoorah' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-feather',
        'color' => '#0000ff',
        'dark_color' => '#7f7fff',
    ],
    'klarna' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-shopping-basket',
        'color' => '#ff78b3',
        'dark_color' => '#ffb3d6',
    ],
    'plisio' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-coins',
        'color' => '#466eeb',
        'dark_color' => '#8fa7ff',
    ],
    'revolut' => [
        'payment_type' => ['one_time'],
        'icon' => 'fas fa-credit-card',
        'color' => '#193053',
        'dark_color' => '#8fa3c2',
    ],
];
