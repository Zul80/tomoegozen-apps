<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        env('APP_URL') ? parse_url(env('APP_URL'), PHP_URL_HOST) : 'localhost',
        env('SANCTUM_STATEFUL_PORT') ? ':' . env('SANCTUM_STATEFUL_PORT') : ''
    ))),

    'expiration' => env('SANCTUM_EXPIRATION'),

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'verify_csrf_token' => \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => \Illuminate\Cookie\Middleware\EncryptCookies::class,
    ],
];
