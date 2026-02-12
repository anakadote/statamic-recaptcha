<?php

namespace Anakadote\StatamicRecaptcha\Services;

use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\Client\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\CreateAssessmentRequest;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;
use Illuminate\Support\Facades\Log;

class RecaptchaEnterprise
{
    /**
     * Verify reCAPTCHA v3 Enterprise.
     */
    public static function verify(string $token, string $action, float $threshold = .5): bool
    {
        // Set the explicit path to the service account key file, if set in our config...
        $credentialsPath = config('recaptcha.recaptcha_enterprise.credentials');
        if ($credentialsPath) {
            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/cloud-platform'], 
                config('recaptcha.recaptcha_enterprise.credentials')
            );

            $client = new RecaptchaEnterpriseServiceClient([
                'credentials' => $credentials,
            ]);

        // ...otherwise, let Google auto-find it using the `GOOGLE_APPLICATION_CREDENTIALS` .env value.
        } else {
            $client = new RecaptchaEnterpriseServiceClient;
        }

        $event = (new Event)
            ->setSiteKey(config('recaptcha.recaptcha_enterprise.site_key'))
            ->setToken($token)
            ->setExpectedAction($action);

        $assessment = (new Assessment)
            ->setEvent($event);

        $projectId = config('recaptcha.recaptcha_enterprise.project_id');

        $request = (new CreateAssessmentRequest)
            ->setParent("projects/{$projectId}")
            ->setAssessment($assessment);

        try {
            $response = $client->createAssessment($request);

            // Check token validity.
            if (! $response->getTokenProperties()->getValid()) {
                $invalidReason = $response->getTokenProperties()->getInvalidReason();

                if (config('recaptcha.log_failures', true)) {
                    Log::info('reCAPTCHA Enterprise verification failure.', [
                        'errorType' => 'token',
                        'errorCode' => $invalidReason,
                        'errorReason' => InvalidReason::name($invalidReason),
                    ]);
                }

                return false;
            }

            // Check the risk score against the threshold.
            $score = $response->getRiskAnalysis()->getScore();
            if ($score < $threshold) {
                if (config('recaptcha.log_failures', true)) {
                    Log::info('reCAPTCHA Enterprise verification failure.', [
                        'errorType' => 'score',
                        'errorReason' => "Score ({$score}) was less than the threshold ({$threshold}).",
                    ]);
                }

                return false;
            }

        } catch (Exception $e) {
            if (config('recaptcha.log_failures', true)) {
                Log::info('reCAPTCHA Enterprise verification failure.', ['error' => $e->getMessage()]);
            }

            return false;
        }

        return true;
    }
}
