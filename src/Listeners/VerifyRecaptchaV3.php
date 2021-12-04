<?php

namespace Anakadote\StatamicRecaptcha\Listeners;

use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use Illuminate\Validation\ValidationException;
use Statamic\Events\FormSubmitted;

class VerifyRecaptchaV3
{
    /**
     * Verify a reCAPTCHA v3 token when a form is submitted.
     *
     * @param  \Statamic\Events\FormSubmitted  $event
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function handle(FormSubmitted $event)
    {   
        $token = request()->captcha_token;
        $action = request()->captcha_action;

        // Verify reCAPTCHA token.
        if (! RecaptchaV3::verify($token, $action, config('recaptcha.recaptcha_v3.threshold'))) {
            throw ValidationException::withMessages([config('recaptcha.recaptcha_v3.error_message')]);
        }
    }
}
