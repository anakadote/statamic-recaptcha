<?php

return [

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Statmic Addon Configuration
    |--------------------------------------------------------------------------
    */

    // v3
    'recaptcha_v3' => [
        'site_key' => env('RECAPTCHA_V3_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_V3_SECRET_KEY'),
        'threshold' => env('RECAPTCHA_V3_THRESHOLD', .5),
        'error_message' => 'Sorry, but you look like a robot.',
    ],

];
