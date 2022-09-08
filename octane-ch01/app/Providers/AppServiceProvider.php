<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        Log::info('NEW     - '.__METHOD__);
        parent::__construct($app);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Log::info('REGISTER     - '.__METHOD__);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Log::info('BOOT     - '.__METHOD__);
    }
}
