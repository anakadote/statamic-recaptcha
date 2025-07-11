<?php

namespace Anakadote\StatamicRecaptcha\Tags;

use Exception;
use Statamic\Tags\Tags;

class Recaptcha extends Tags
{
    /**
     * Script tag for footer.
     */
    public function index(): string
    {
        $version = config('recaptcha.recaptcha_version');

        if ($version == 3) {
            return $this->v3();
        }

        if ($version == 2) {
            return $this->v2();
        }

        throw new Exception('reCAPTCHA version not set correctly in config/recaptcha.php');
    }

    /**
     * v2 captcha checkbox to place in forms.
     */
    public function checkbox(): string
    {
        $siteKey = config('recaptcha.recaptcha_v2.site_key');

        return <<<HTML
            <div class="g-recaptcha" data-sitekey="{$siteKey}"></div>
        HTML;
    }

    /**
     * Google reCAPTCHA Terms of Service text.
     */
    public function terms(): string
    {
        $version = config('recaptcha.recaptcha_version');

        return __('recaptcha::recaptcha.recaptcha_v' . $version . '_terms');
    }

    /**
     * v3 script tag for footer.
     */
    protected function v3(): string
    {
        $siteKey = config('recaptcha.recaptcha_v3.site_key');
        $action = e(substr(str_replace('-', '_', request()->path()), 0, 85));
        $verifyOnPageLoad = config('recaptcha.recaptcha_v3.verify_on_page_load', true) ? 'true' : 'false';

        return <<<SCRIPT
            <script type="text/javascript">
              window.recaptchaV3 = {};
              window.recaptchaV3.siteKey = '{$siteKey}';
              window.recaptchaV3.action = '{$action}';
              window.recaptchaV3.verifyOnPageLoad = {$verifyOnPageLoad};
            </script>
            <script src="/vendor/statamic-recaptcha/js/recaptcha-v3.js"></script>
        SCRIPT;
    }

    /**
     * v2 script tag for footer.
     */
    protected function v2(): string
    {
        $siteKey = config('recaptcha.recaptcha_v2.site_key');
        $theme = config('recaptcha.recaptcha_v2.theme');
        $size = config('recaptcha.recaptcha_v2.size');
        $tabindex = config('recaptcha.recaptcha_v2.tabindex');
        $lang = config('recaptcha.recaptcha_v2.lang') ? "'" . config('recaptcha.recaptcha_v2.lang') . "'" : 'null';

        // Invisible
        if ($size == 'invisible') {
            return <<<SCRIPT
                <script type="text/javascript">
                  window.recaptchaV2 = {};
                  window.recaptchaV2.siteKey = '{$siteKey}';
                  window.recaptchaV2.size = 'invisible';
                </script>
                <script src="/vendor/statamic-recaptcha/js/recaptcha-v2.js"></script>
            SCRIPT;

        // Checkbox
        } else {
            return <<<SCRIPT
                <script type="text/javascript">
                  window.recaptchaV2 = {};
                  window.recaptchaV2.siteKey = '{$siteKey}';
                  window.recaptchaV2.theme = '{$theme}';
                  window.recaptchaV2.size = '{$size}';
                  window.recaptchaV2.tabindex = {$tabindex};
                  window.recaptchaV2.lang = {$lang};
                </script>
                <script src="/vendor/statamic-recaptcha/js/recaptcha-v2.js"></script>
            SCRIPT;
        }
    }
}
