<?php

namespace Anakadote\StatamicRecaptcha\Services;
    
class RecaptchaV2
{
    /**
     * Verify reCAPTCHA v2.
     *
     * @param  string  $response  reCAPTCHA response
     * @return bool
     */
    public static function verify($response)
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

        return false;
    }
}
