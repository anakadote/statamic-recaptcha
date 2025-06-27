# Statamic reCAPTCHA

Statamic reCAPTCHA is a Statamic addon that integrates Google reCAPTCHA **v3** or **v2** with your web forms. 

> NOTE: Statamic reCAPTCHA for v3 works in two ways: 1) On page load reCAPTCHA will determine if the user is likely a bot, and if so, all web forms will be removed from the page and replaced with an alert message (which can be changed). 2) reCAPTCHA also works when a form is submitted, and returns an error message (which can be changed) if the user is determined to likely be a bot.

## Installation

Run the following command from your project root:
``` bash
composer require anakadote/statamic-recaptcha
```

Publish the assets and config file (to `config/recaptcha.php`):
```bash
php artisan vendor:publish --tag=statamic-recaptcha
```

Set your reCAPTCHA version in the published `config/recaptcha.php` file (default is set to version 3):
```php
...
'recaptcha_version' => 3,
```

Add your [reCAPTCHA keys](https://www.google.com/recaptcha/admin/) to your [.env](https://statamic.dev/configuration) file:
```
# reCAPTCHA v3
RECAPTCHA_V3_SITE_KEY=[YOUR KEY HERE]
RECAPTCHA_V3_SECRET_KEY=[YOUR KEY HERE]
RECAPTCHA_V3_THRESHOLD=.5

# OR

# reCAPTCHA v2
RECAPTCHA_V2_SITE_KEY=[YOUR KEY HERE]
RECAPTCHA_V2_SECRET_KEY=[YOUR KEY HERE]
```

> NOTE: If you’re using reCAPTCHA v3 then you’ll want to set the RECAPTCHA_V3_THRESHOLD value in your .env as well, as seen above. A reasonable default of .5 is already set for you. This value is used to determine if submissions should be treated as spam or not. reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot), so if your RECAPTCHA_V3_THRESHOLD value is .5, then any submission that reCAPTCHA scores below .5 will be treated as spam, and will not be saved. So, for example, to be more lenient, you may want to set your RECAPTCHA_V3_THRESHOLD value to .3

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
If you use reCAPTCHA v3 or reCAPTCHA v2 Invisible, you agreed on reCAPTCHA's website to explicitly inform visitors to your site that you have implemented reCAPTCHA on your site and that their use of reCAPTCHA is subject to the Google [Privacy Policy](https://www.google.com/policies/privacy/) and [Terms of Use](https://www.google.com/policies/terms/).

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
