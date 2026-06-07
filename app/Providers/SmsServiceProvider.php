<?php

namespace App\Providers;

use App\Contracts\SmsServiceInterface;
use App\Services\LogSmsService;
use App\Services\Fast2SmsService;
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
                'fast2sms' => new Fast2SmsService(),
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
