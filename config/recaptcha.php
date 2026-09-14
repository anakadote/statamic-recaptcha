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
    | Enterprise / Fraud Defense configuration
    |
    | You must also have a "GOOGLE_APPLICATION_CREDENTIALS" variable in .env
    | that points to the service account JSON key file within your file system.
    |
    | Prerequisite: create your SITE KEY at Security > Fraud Defense > Create key.
    | That is the public key for your frontend JavaScript — a different thing
    | from the service account JSON key created below.
    |
    | Steps to create the JSON key file from your Google Cloud console at
    | https://console.cloud.google.com:
    |
    | 1. Select the project that owns the site key. Copy its project ID
    |    (lowercase string, not the display name or numeric project number).
    | 2. APIs & Services > Library > "reCAPTCHA Enterprise API" > Enable.
    | 3. IAM & Admin > Service Accounts > Create service account. This opens a
    |    three-pane wizard.
    | 4. On the wizard's second pane, grant the role "reCAPTCHA Enterprise
    |    Agent" (roles/recaptchaenterprise.agent). Nothing broader. Leave the
    |    third pane blank and click Done.
    | 5. Open the account > Keys tab > Add key > Create new key > JSON.
    |    Downloads once; Google keeps no copy. Check its project_id matches.
    | 6. Store outside the webroot, chmod 600, gitignore.
    */
    'recaptcha_enterprise' => [
        'project_id' => env('RECAPTCHA_ENTERPRISE_PROJECT_ID'),
        'site_key' => env('RECAPTCHA_ENTERPRISE_SITE_KEY'),
        'threshold' => env('RECAPTCHA_ENTERPRISE_THRESHOLD', .5),
        'credentials' => env('GOOGLE_APPLICATION_CREDENTIALS'),
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
