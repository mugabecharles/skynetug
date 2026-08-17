<?php

return [

    'postmark' => ['key' => env('POSTMARK_API_KEY')],
    'resend'   => ['key' => env('RESEND_API_KEY')],
    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ── Payment Gateways ──────────────────────────────────────────────────
    'mtn_momo' => [
        'base_url'         => env('MTN_MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
        'collection_key'   => env('MTN_MOMO_COLLECTION_KEY'),
        'collection_secret'=> env('MTN_MOMO_COLLECTION_SECRET'),
        'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
        'environment'      => env('MTN_MOMO_ENVIRONMENT', 'sandbox'),
    ],

    'airtel_money' => [
        'base_url'      => env('AIRTEL_MONEY_BASE_URL', 'https://openapi.airtel.africa'),
        'client_id'     => env('AIRTEL_MONEY_CLIENT_ID'),
        'client_secret' => env('AIRTEL_MONEY_CLIENT_SECRET'),
        'environment'   => env('AIRTEL_MONEY_ENVIRONMENT', 'sandbox'),
    ],

    'flutterwave' => [
        'public_key'     => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key'     => env('FLUTTERWAVE_SECRET_KEY'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
        'secret_hash'    => env('FLUTTERWAVE_SECRET_HASH'),
    ],

    'pesapal' => [
        'consumer_key'    => env('PESAPAL_CONSUMER_KEY'),
        'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
        'environment'     => env('PESAPAL_ENVIRONMENT', 'sandbox'),
    ],

    // ── cPanel / WHM ──────────────────────────────────────────────────────
    'cpanel' => [
        'host'      => env('CPANEL_HOST'),
        'port'      => env('CPANEL_PORT', 2087),
        'username'  => env('CPANEL_USERNAME', 'root'),
        'api_token' => env('CPANEL_API_TOKEN'),
    ],

    // ── Domain Registrars ────────────────────────────────────────────────
    'resellerclub' => [
        'reseller_id' => env('RESELLERCLUB_RESELLER_ID'),
        'api_key'     => env('RESELLERCLUB_API_KEY'),
        'base_url'    => env('RESELLERCLUB_BASE_URL', 'https://httpapi.com/api'),
    ],
    'namesilo' => [
        'api_key'  => env('NAMESILO_API_KEY'),
        'base_url' => env('NAMESILO_BASE_URL', 'https://www.namesilo.com/api'),
    ],

    // ── Softaculous ──────────────────────────────────────────────────────
    'softaculous' => [
        'host'      => env('CPANEL_HOST'),          // same server as cPanel
        'port'      => env('CPANEL_PORT', 2087),
        'username'  => env('CPANEL_USERNAME', 'root'),
        'api_token' => env('CPANEL_API_TOKEN'),
    ],

    // ── Live Chat (Tawk.to) ──────────────────────────────────────────────
    'tawkto' => [
        'property_id' => env('TAWKTO_PROPERTY_ID'),
        'widget_id'   => env('TAWKTO_WIDGET_ID', 'default'),
    ],

    // ── Cloudflare ───────────────────────────────────────────────────────
    'cloudflare' => [
        'api_token' => env('CLOUDFLARE_API_TOKEN'),
        'zone_id'   => env('CLOUDFLARE_ZONE_ID'),
    ],

    // ── WHMCS ────────────────────────────────────────────────────────────
    'whmcs' => [
        'api_url'    => env('WHMCS_API_URL'),
        'identifier' => env('WHMCS_IDENTIFIER'),
        'secret'     => env('WHMCS_SECRET'),
    ],

    // ── reCAPTCHA ────────────────────────────────────────────────────────
    'recaptcha' => [
        'site_key'   => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
];
