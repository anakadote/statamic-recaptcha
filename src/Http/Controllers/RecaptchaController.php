<?php

namespace Anakadote\StatamicRecaptcha\Http\Controllers;

use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RecaptchaController extends Controller
{
    /**
     * Verify a reCAPTCHA v3 token and action.
     */
    public function verifyV3Token(Request $request): Response
    {
        if (! RecaptchaV3::verify($request->input('token'), $request->input('action'), config('recaptcha.recaptcha_v3.threshold'))) {
            return response([
                'error' => __('recaptcha::recaptcha.recaptcha_v3_error_message'),
            ], 401);
        }

        return response('Success', 200);
    }
}
