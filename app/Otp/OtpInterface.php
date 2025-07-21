<?php

namespace Modules\Notification\Otp;

interface OtpInterface
{
    /**
     * Start a phone verification process using an external service
     *
     * @return Result
     */
    public function startVerification($phone_number);

    /**
     * Check verification code using an external service
     *
     * @return Result
     */
    public function checkVerification($id, $code, $phone_number);

    /**
     * The `public function sendSms(, )` is a method declared in the
     * `OtpInterface` interface. It is responsible for sending an SMS message to the specified phone number with the given message.
     *
     * @param  $message
     * @return mixed
     */
    public function sendSms($phone_number, $messageBody);
}
