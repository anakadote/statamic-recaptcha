<?php

namespace Anakadote\StatamicRecaptcha\Tags;

use Exception;
use Statamic\Tags\Tags;

class Recaptcha extends Tags
{
    /**
     * Script tag for footer.
     */
    public function index()
    {
        $version = config('recaptcha.recaptcha_version');

        if ($version == 3) {
            return $this->v3();
        }

        if ($version == 2) {
            return $this->v2();
        }

        throw new Exception('reCAPTCHA version not set correct in config/recaptha.php');
    }

    /**
     * v2 captcha checkbox to place in forms.
     */
    public function checkbox()
    {
        $siteKey = config('recaptcha.recaptcha_v2.site_key');

        return <<<HTML
            <div class="g-recaptcha" data-sitekey="{$siteKey}"></div>
        HTML;
    }

    /**
     * Google reCAPTCHA Terms of Service text.
     */
    public function terms()
    {
        $version = config('recaptcha.recaptcha_version');

        return config('recaptcha.recaptcha_v' . $version . '.terms');
    }

    /**
     * v3 script tag for footer.
     */
    protected function v3()
    {
        $siteKey = config('recaptcha.recaptcha_v3.site_key');
        $action = str_replace('-', '_', request()->path());

        return <<<SCRIPT
            <script type="text/javascript">
              window.recaptchaV3 = {}
              window.recaptchaV3.siteKey = '{$siteKey}'
              window.recaptchaV3.action = '{$action}'
            </script>
            <script src="/vendor/statamic-recaptcha/js/recaptcha-v3.js"></script>
        SCRIPT;
    }

    /**
     * v2 script tag for footer.
     */
    protected function v2()
    {
        $siteKey = config('recaptcha.recaptcha_v2.site_key');
        $theme = config('recaptcha.recaptcha_v2.theme');
        $size = config('recaptcha.recaptcha_v2.size');
        $tabindex = config('recaptcha.recaptcha_v2.tabindex');

        // Invisible
        if ($size == 'invisible') {
            return <<<SCRIPT
                <script type="text/javascript">
                  window.recaptchaV2 = {}
                  window.recaptchaV2.siteKey = '{$siteKey}'
                  window.recaptchaV2.size = 'invisible'
                </script>
                <script src="/vendor/statamic-recaptcha/js/recaptcha-v2.js"></script>
            SCRIPT;

        // Checkbox
        } else {
            return <<<SCRIPT
                <script type="text/javascript">
                  window.recaptchaV2 = {}
                  window.recaptchaV2.siteKey = '{$siteKey}'
                  window.recaptchaV2.theme = '{$theme}'
                  window.recaptchaV2.size = '{$size}'
                  window.recaptchaV2.tabindex = {$tabindex}
                </script>
                <script src="/vendor/statamic-recaptcha/js/recaptcha-v2.js"></script>
            SCRIPT;
        }
    }
}
