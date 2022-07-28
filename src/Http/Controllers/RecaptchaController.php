<?php

namespace Anakadote\StatamicRecaptcha\Http\Controllers;

use Anakadote\StatamicRecaptcha\Services\RecaptchaV3;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
                'error' => __('recaptcha::recaptcha.recaptcha_v3_error_message'),
            ], 401);
        }
        
        return response('Success', 200);
    }
}
