<?php

return [

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Version
    |--------------------------------------------------------------------------
    |
    | Set your version of reCAPTCHA here, either `enterprise`, `v3`, or `v2`.
    |
    */
    'recaptcha_version' => 'enterprise',

    /*
    |--------------------------------------------------------------------------
    | Enterprise configuration
    |
    | You must also have a "GOOGLE_APPLICATION_CREDENTIALS" variable in .env 
    | that points to the service account JSON key file within your file system.
    |--------------------------------------------------------------------------
    */
    'recaptcha_enterprise' => [
        'project_id' => env('RECAPTCHA_ENTERPRISE_PROJECT_ID'), // The Google Cloud project ID
        'site_key' => env('RECAPTCHA_ENTERPRISE_SITE_KEY'),     // The key ID for the reCAPTCHA key (See https://cloud.google.com/recaptcha/docs/create-key)
        'threshold' => env('RECAPTCHA_ENTERPRISE_THRESHOLD', .5),
    ],

    /*
    |--------------------------------------------------------------------------
    | v3 configuration
    |--------------------------------------------------------------------------
    */
    'recaptcha_v3' => [
        'site_key' => env('RECAPTCHA_V3_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_V3_SECRET_KEY'),
        'threshold' => env('RECAPTCHA_V3_THRESHOLD', .5),

        // In addition to performing the captcha verification when a form is submitted,
        // Statamic reCAPTCHA for v3 can also run on page load, and if it is determined
        // that the user is likely a bot, all forms on the page will be removed.
        'verify_on_page_load' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | v2 configuration
    |--------------------------------------------------------------------------
    |
    | The default is the checkbox captcha, for which you can set the size to 
    | either `normal` or `compact`. To enable the invisible reCAPTCHA, set the 
    | size to `invisible`.
    |
    */
    'recaptcha_v2' => [
        'site_key' => env('RECAPTCHA_V2_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_V2_SECRET_KEY'),
        'size' => 'normal', // "normal", "compact", or "invisible"
        'theme' => 'light', // "light" or "dark"
        'tabindex' => 0,
        'lang' => env('APP_LOCALE', 'en'), // Optional language code from https://developers.google.com/recaptcha/docs/language
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Set this option to `true` to have all reCAPTCHA verification failures
    | saved to the logs.
    |
    */
    'log_failures' => true,

    /*
    |--------------------------------------------------------------------------
    | Excluded Forms
    |--------------------------------------------------------------------------
    |
    | You can exclude certain forms from reCAPTCHA validation by adding its
    | handle below. For reCAPTCHA v3 you'll also need to add the CSS class 
    | "nocaptcha" to the <form> element.
    |
    */
    'exclusions' => [
        // 'contact_us',
    ],

];
