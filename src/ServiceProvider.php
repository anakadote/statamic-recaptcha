<?php

namespace Anakadote\StatamicRecaptcha;

use Anakadote\StatamicRecaptcha\Http\Controllers\RecaptchaController;
use Anakadote\StatamicRecaptcha\Listeners\VerifyRecaptcha;
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
        __DIR__ . '/../resources/js/recaptcha-v2.js',
    ];

    protected $listen = [
        'Statamic\Events\FormSubmitted' => [
            VerifyRecaptcha::class,
        ],
    ];

    public function bootAddon()
    {
        // Only publish the config file if it doesn't already exist.
        if (! file_exists(config_path('recaptcha.php'))) {
            $this->publishes([
                __DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php'),
            ], 'statamic-recaptcha');
        }

        $this->registerActionRoutes(function () {
            Route::post('verify-recaptcha-v3-token', [RecaptchaController::class, 'verifyV3Token']);
        });
    }
}
