<?php

declare(strict_types=1);

namespace Modules\Notification\Otp\Gateways;

use Modules\Notification\Otp\OtpInterface;

/**
 * SmsGateway class
 * Bind to SmsGatewayInterface
 */
class OtpGateway
{
    public function __construct(private readonly OtpInterface $gateway) {}

    public function startVerification(mixed $phone_number): mixed
    {
        return $this->gateway->startVerification($phone_number);
    }

    public function checkVerification(mixed $id, mixed $code, mixed $phone_number): mixed
    {
        return $this->gateway->checkVerification($id, $code, $phone_number);
    }

    public function sendSms(mixed $phone_number, mixed $messageBody): mixed
    {
        return $this->gateway->sendSms($phone_number, $messageBody);
    }
}
