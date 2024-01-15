<?php

namespace Anakadote\StatamicRecaptcha\Http\Controllers;

use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RecaptchaController extends Controller
{
    /**
     * Verify a reCAPTCHA v3 token and action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyV3Token(Request $request)
    {
        if (! RecaptchaV3::verify($request->input('token'), $request->input('action'), config('recaptcha.recaptcha_v3.threshold'))) {
            return response([
                'error' => config('recaptcha.recaptcha_v3.error_message'),
            ], 401);
        }

        return response('Success', 200);
    }
}
