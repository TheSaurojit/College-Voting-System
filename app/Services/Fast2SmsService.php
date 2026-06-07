<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fast2SmsService implements SmsServiceInterface
{
    /**
     * The Fast2SMS API authorization token.
     */
    protected string $authorization;

    /**
     * Create a new Fast2SmsService instance.
     */
    public function __construct()
    {
        $this->authorization = config('sms.fast2sms.authorization');
    }

    /**
     * Send an OTP code via Fast2SMS.
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        // Clean phone number (keep only digits)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Fast2SMS expects 10-digit Indian numbers. Strip country code (91) if present.
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        $message = "This message is from  College Voting System , your OTP is: $otp , Valid for 2 minutes.";

        try {
            $response = Http::get('https://www.fast2sms.com/dev/bulkV2', [
                'authorization' => $this->authorization,
                'route' => "q",
                'message' => $message,
                'numbers' => $cleanPhone,
                'schedule_time' => '',
            ]);

            if ($response->successful() && $response->json('return') === true) {
                Log::info("OTP SMS sent successfully via Fast2SMS to {$cleanPhone}");
                return true;
            }

            Log::error("Failed to send OTP SMS via Fast2SMS to {$cleanPhone}. Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Failed to send OTP SMS via Fast2SMS to {$cleanPhone}: {$e->getMessage()}");
            return false;
        }
    }
}
