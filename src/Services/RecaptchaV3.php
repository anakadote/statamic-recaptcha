<?php

namespace Anakadote\StatamicRecaptcha\Services;

use Illuminate\Support\Facades\Log;

class RecaptchaV3
{
    /**
     * Verify reCAPTCHA v3.
     */
    public static function verify(string $token, string $action, float $threshold = .5): bool
    {
        $threshold = $threshold ?? .5; // In case null is provided for the threshold.

        $args = [
            'secret'   => config('recaptcha.recaptcha_v3.secret_key'),
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        $url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($args);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output);

        if (
            ! $result || 
            ! $result->success || 
            $result->score < $threshold || 
            $result->action !== $action
        ) {
            if (config('recaptcha.log_failures', true)) {
                Log::info('reCAPTCHA v3 verification failure.', ['response' => json_decode($output, true)]);
            }

            return false;
        }

        return true;
    }
}
