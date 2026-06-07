<?php

namespace App\Providers;

use App\Contracts\SmsServiceInterface;
use App\Services\LogSmsService;
use App\Services\TwilioSmsService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register the SMS service binding.
     */
    public function register(): void
    {
        $this->app->singleton(SmsServiceInterface::class, function ($app) {
            return match (config('sms.driver')) {
                'twilio' => new TwilioSmsService(),
                default => new LogSmsService(),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
