<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS driver that will be used to send
    | OTP messages. Supported: "log", "twilio"
    |
    */

    'driver' => env('SMS_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Fast2SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Fast2SMS SMS service bulkV2 endpoint.
    |
    */

    'fast2sms' => [
        'authorization' => env('FAST2SMS_AUTHORIZATION'),
    ],

];
