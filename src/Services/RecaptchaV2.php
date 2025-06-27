<?php

namespace Anakadote\StatamicRecaptcha\Services;

use Illuminate\Support\Facades\Log;

class RecaptchaV2
{
    /**
     * Verify reCAPTCHA v2.
     */
    public static function verify(?string $response): bool
    {
        $args = [
            'secret'   => config('recaptcha.recaptcha_v2.secret_key'),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        $url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($args);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output);

        if ($result->success) {
            return true;
        }

        if (config('recaptcha.log_failures', true)) {
            Log::info('reCAPTCHA v2 verification failure.', ['response' => json_decode($output, true)]);
        }

        return false;
    }
}
