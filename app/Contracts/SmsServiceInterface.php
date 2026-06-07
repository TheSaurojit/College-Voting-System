<?php

namespace App\Contracts;

interface SmsServiceInterface
{
    /**
     * Send an OTP code via SMS to the given phone number.
     */
    public function sendOtp(string $phone, string $otp): bool;
}
