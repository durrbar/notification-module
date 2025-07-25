<?php

namespace Modules\Notification\Otp\Gateways;

use Modules\Notification\Otp\OtpInterface;

/**
 * SmsGateway class
 * Bind to SmsGatewayInterface
 */
class OtpGateway
{
    private $gateway;

    public function __construct(OtpInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function startVerification($phone_number)
    {
        return $this->gateway->startVerification($phone_number);
    }

    public function checkVerification($id, $code, $phone_number)
    {
        return $this->gateway->checkVerification($id, $code, $phone_number);
    }

    public function sendSms($phone_number, $messageBody)
    {
        return $this->gateway->sendSms($phone_number, $messageBody);
    }
}
