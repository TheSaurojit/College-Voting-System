<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSmsService implements SmsServiceInterface
{
    /**
     * The Twilio client instance.
     */
    protected Client $client;

    /**
     * The Twilio phone number to send from.
     */
    protected string $from;

    /**
     * Create a new TwilioSmsService instance.
     */
    public function __construct()
    {
        $this->client = new Client(
            config('sms.twilio.sid'),
            config('sms.twilio.token')
        );

        $this->from = config('sms.twilio.from');
    }

    /**
     * Send an OTP code via Twilio SMS.
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        try {
            $this->client->messages->create($phone, [
                'from' => $this->from,
                'body' => "Your OTP for College Voting System is: {$otp}. Valid for 5 minutes.",
            ]);

            Log::info("OTP SMS sent successfully to {$phone}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send OTP SMS to {$phone}: {$e->getMessage()}");

            return false;
        }
    }
}
