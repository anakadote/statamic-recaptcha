# Statamic reCAPTCHA

Statamic reCAPTCHA is a Statamic addon that integrates Google reCAPTCHA **v3** or **v2** with your web forms. 

## Installation

Run the following command from your project root:
``` bash
composer require anakadote/statamic-recaptcha
```

Publish the assets and config file (to `config/recaptcha.php`):
```bash
php artisan vendor:publish --tag=statamic-recaptcha
```

> Note: If upgrading from a previous version, you'll need to first delete the previously published assets at `public/vendor/statamic-recaptcha/` as well as the config file at `config/recaptcha.php`, and then republish.

Set your reCAPTCHA version in the published `config/recaptcha.php` file (default is set to the Enterprise version of reCAPTCHA version 3):
```php
...
'recaptcha_version' => 'enterprise',
```

Add your [reCAPTCHA keys](https://www.google.com/recaptcha/admin/) to your [.env](https://statamic.dev/configuration) file:
```
# reCAPTCHA v3 Enterprise
RECAPTCHA_ENTERPRISE_PROJECT_ID=[YOUR PROJECT ID HERE]
RECAPTCHA_ENTERPRISE_SITE_KEY=[YOUR SITE KEY HERE]
RECAPTCHA_ENTERPRISE_THRESHOLD=.5
GOOGLE_APPLICATION_CREDENTIALS="/path/to/service-account-key.json"

# OR

# reCAPTCHA v3 Classic
RECAPTCHA_V3_SITE_KEY=[YOUR KEY HERE]
RECAPTCHA_V3_SECRET_KEY=[YOUR KEY HERE]
RECAPTCHA_V3_THRESHOLD=.5

# OR

# reCAPTCHA v2
RECAPTCHA_V2_SITE_KEY=[YOUR KEY HERE]
RECAPTCHA_V2_SECRET_KEY=[YOUR KEY HERE]
```

> NOTE: If you’re using reCAPTCHA v3 (Enterprise or classic) then you’ll want to set the threshold value in your .env as well, as seen above. A reasonable default of .5 is already set for you. This value is used to determine if submissions should be treated as spam or not. reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot), so if your threshold value is .5, then any submission that reCAPTCHA scores below .5 will be treated as spam, and will not be saved. So, for example, to be more lenient, you may want to set your threshold value to .3

> NOTE: If you’re using reCAPTCHA v3 **Enterprise**, an additional environment variable is required in .env as seen above: `GOOGLE_APPLICATION_CREDENTIALS`. This variable should point to the location of a JSON file within your file system that contains your service account key, which informs the Google client library where to find your credentials to authenticate your application with Google Cloud services. You can generate a service account key on the [Google Cloud console](https://docs.cloud.google.com/iam/docs/keys-create-delete) or the `gcloud` CLI. Also ensure that the `reCAPTCHA Enterprise API` is enabled, and the `reCAPTCHA Enterprise Agent` (`roles/recaptchaenterprise.agent`) role is granted to the principal.

Add the following tag to your master layout, right before the closing `</body>` tag:

```html
{{ recaptcha }}
```

For example: 
```html
        {{ recaptcha }}
    </body>
</html>
```

## reCAPTCHA Terms of Service
If you use reCAPTCHA v3 or reCAPTCHA v2 Invisible, you agreed on reCAPTCHA's website to explicitly inform visitors to your site that you have implemented reCAPTCHA on your site and that their use of reCAPTCHA is subject to the Google [Privacy Policy](https://policies.google.com/privacy) and [Terms of Use](https://policies.google.com/terms).

You can use the following tag to output some default language on your website. For example, include it next to each form or in the footer of your website (the language can be changed in your published config file):
```php
{{ recaptcha:terms }}
```

**For reCAPTCHA v3, that’s it!** For v2, read on...  


## reCAPTCHA v2 - Additional Steps Required
### Checkbox Captcha (v2)
For the **checkbox** version of reCAPTCHA v2, you’ll need to add the following tag to each of your forms where you want the checkbox captcha to appear:
```HTML
{{ recaptcha:checkbox }}
```

For example:
```html
{{ form:contact_us }}
    <label for="name">Name</label>
    <input type="text" name="name" id="name" required>

    {{ recaptcha:checkbox }}

    <button type="submit">Submit</button>
{{ /form:contact_us }}
```

### Invisible Captcha (v2)
For the **invisible** version of reCAPTCHA v2, you’ll just need to set the recaptcha_v2.size config value in the published config/recaptcha.php file to "invisible":
```php
...
'recaptcha_v2' => [
    'site_key' => env('RECAPTCHA_V2_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_V2_SECRET_KEY'),
    'size' => 'invisible', // Set this to "invisible" to enable the invisible version of reCAPTCHA v2
],
```

## Form Exclusions

To **exclude** a form from reCAPTCHA validation, add the CSS class "nocaptcha" to the form element, and add its handle to the "exclusions" array in the published config/recaptcha.php file. For example:
```html
{{ form:contact_us class="nocaptcha" }}
```
```php
# config/recaptcha.php
'exclusions' => [
    'contact_us',
],
```

## Localization

You can add new language translations, or override this package’s existing translations, by creating a language file at:

`lang/vendor/recaptcha/{locale}/recaptcha.php`

For example, to create new Swedish language translations, create a new file at `lang/vendor/recaptcha/sv/recaptcha.php` with the strings you want to translate. For example:

```
<?php

    return [
        'recaptcha_v3_terms' => 'Denna webbplats har implementerat reCAPTCHA v3 och din användning av reCAPTCHA v3 omfattas av <a href="https://www.google.com/policies/privacy/" target="_blank">Googles sekretesspolicy</a> och <a href="https://www.google.com/policies/terms/" target="_blank">användarvillkoren</a>.',
    ];
```

See https://laravel.com/docs/12.x/localization#overriding-package-language-files for more information.
