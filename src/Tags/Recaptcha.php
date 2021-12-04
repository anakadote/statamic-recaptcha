<?php

namespace Anakadote\StatamicRecaptcha\Tags;

use Statamic\Tags\Tags;

class Recaptcha extends Tags
{
    public function v3()
    {
        $siteKey = config('recaptcha.recaptcha_v3.site_key');
        $action = str_replace('-', '_', request()->path());

        return <<<SCRIPT
            <script>
              window.recaptchaV3 = {}
              window.recaptchaV3.siteKey = '{$siteKey}'
              window.recaptchaV3.action = '{$action}'
            </script>
            <script src="/vendor/statamic-recaptcha/js/recaptcha-v3.js"></script>
        SCRIPT;
    }
}
