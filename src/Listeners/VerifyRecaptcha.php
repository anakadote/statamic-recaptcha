<?php

namespace Anakadote\StatamicRecaptcha\Listeners;

use Anakadote\StatamicRecaptcha\Services\RecaptchaV2;
use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use Illuminate\Validation\ValidationException;
use Statamic\Events\FormSubmitted;

class VerifyRecaptcha
{
    /**
     * Verify a reCAPTCHA token when a form is submitted.
     *
     * @param  \Statamic\Events\FormSubmitted  $event
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function handle(FormSubmitted $event)
    {   
        switch (config('recaptcha.recaptcha_version')) {

            // v3
            case 3:
                $token = request()->captcha_token;
                $action = request()->captcha_action;

                if (! RecaptchaV3::verify($token, $action, config('recaptcha.recaptcha_v3.threshold'))) {
                    throw ValidationException::withMessages([config('recaptcha.recaptcha_v3.error_message')]);
                }
                break;

            // v2 Checkbox
            case '2-checkbox':
                $response = request()->input('g-recaptcha-response');

                if (! RecaptchaV2::verify($response)) {
                    throw ValidationException::withMessages([config('recaptcha.recaptcha_v2.error_message')]);
                }
                break;

            // v2 Invisible
            case '2-invisible':
                $response = request()->input('g-recaptcha-response');

                if (! RecaptchaV2::verify($response)) {
                    throw ValidationException::withMessages([config('recaptcha.recaptcha_v2.error_message')]);
                }
                break;
            
            default:
                throw ValidationException::withMessages(['reCAPTCHA version not set correctly in config/recaptcha.php']);
        }
    }
}
