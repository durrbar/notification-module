<?php

namespace Modules\Notification\Otp\Gateways;

use Exception;
use MessageBird\Client;
use MessageBird\Objects\Verify;
use Modules\Notification\Otp\OtpInterface;
use Modules\Notification\Otp\Result;

class MessagebirdGateway implements OtpInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $api_key = config('services.messagebird.api_key');
        $this->client = new Client($api_key);
    }

    /**
     * Start a phone verification process
     *
     * @return Result
     */
    public function startVerification($phone_number)
    {
        try {
            $verify = new Verify();
            $verify->originator = config('services.messagebird.originator');
            $verify->recipient = $phone_number;
            $result = $this->client->verify->create($verify);

            return new Result($result->getId());
        } catch (Exception $exception) {
            return new Result(["Verification failed to start: {$exception->getMessage()}"]);
        }
    }

    /**
     * Check verification code
     *
     * @return Result
     */
    public function checkVerification($id, $code, $phone_number)
    {
        try {
            $this->client->verify->verify($id, $code);

            return new Result('success');
        } catch (Exception $exception) {
            return new Result(["Verification check failed: {$exception->getMessage()}"]);
        }
    }

    public function sendSms($phone_number, $messageBody)
    {
        try {
            $message = new \MessageBird\Objects\Message();
            $message->originator = config('services.messagebird.originator');
            $message->recipients = [$phone_number];
            $message->body = $message;
            $this->client->messages->create($messageBody);

            return new Result('success');
        } catch (Exception $exception) {
            return new Result(["Verification check failed: {$exception->getMessage()}"]);
        }
    }
}
