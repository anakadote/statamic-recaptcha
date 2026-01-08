<?php

namespace Anakadote\StatamicRecaptcha\Listeners;

use Anakadote\StatamicRecaptcha\Services\RecaptchaEnterprise;
use Anakadote\StatamicRecaptcha\Services\RecaptchaV2;
use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use Illuminate\Validation\ValidationException;
use Statamic\Events\FormSubmitted;

class VerifyRecaptcha
{
    /**
     * Verify a reCAPTCHA token when a form is submitted.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(FormSubmitted $event): void
    {
        // Is the form excluded from validation?
        if (in_array($event->submission->form->handle(), config('recaptcha.exclusions', []))) {
            return;
        }

        switch (config('recaptcha.recaptcha_version')) {

            // Enterprise
            case 'enterprise':
                $token = request()->input('captcha_token');
                $action = request()->input('captcha_action');

                if (! RecaptchaEnterprise::verify($token, $action, config('recaptcha.recaptcha_enterprise.threshold'))) {
                    throw ValidationException::withMessages([__('recaptcha::recaptcha.recaptcha_error_message')]);
                }
                break;

            // v3
            case 'v3':
                $token = request()->input('captcha_token');
                $action = request()->input('captcha_action');

                if (! RecaptchaV3::verify($token, $action, config('recaptcha.recaptcha_v3.threshold'))) {
                    throw ValidationException::withMessages([__('recaptcha::recaptcha.recaptcha_error_message')]);
                }
                break;

            // v2
            case 'v2':
                $response = request()->input('g-recaptcha-response');

                if (! RecaptchaV2::verify($response)) {
                    throw ValidationException::withMessages([__('recaptcha::recaptcha.recaptcha_error_message')]);
                }
                break;

            default:
                throw ValidationException::withMessages(['reCAPTCHA version not set correctly in config/recaptcha.php']);
        }
    }
}
