<?php

namespace Anakadote\StatamicRecaptcha;

use Anakadote\StatamicRecaptcha\Tags\Recaptcha;
use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Recaptcha::class,
    ];

    protected $scripts = [
        __DIR__ . '/../resources/js/recaptcha-v3.js',
    ];

    protected $listen = [
        'Statamic\Events\FormSubmitted' => [
            'Anakadote\StatamicRecaptcha\Listeners\VerifyRecaptchaV3',
        ],
    ];

    public function bootAddon()
    {
        $this->publishes([
            __DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php'),
        ]);

        $this->registerActionRoutes(function () {
            Route::post('verify-recaptcha-v3-token', 'RecaptchaController@verifyV3Token');
        });
    }
}
