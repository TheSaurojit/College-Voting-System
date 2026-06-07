<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class LogSmsService implements SmsServiceInterface
{
    /**
     * Log the OTP instead of sending an actual SMS (for development/testing).
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        Log::info("SMS OTP for {$phone}: {$otp} (sent via LogSmsService)");

        return true;
    }
}
