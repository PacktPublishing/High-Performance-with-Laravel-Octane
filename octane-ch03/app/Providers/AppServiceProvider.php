<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Facades\Octane;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Log::info('SERVICE PROVIDER RELOADED.', ['timestamp' => now()]);

        Octane::tick('simple-ticker', fn () => Log::info('OCTANE TICK.', ['timestamp' => now()]))
        ->seconds(10)
        ->immediate();

        Octane::tick('cache-last-random-number',
            function () {
                $number = rand(1, 1000);
                Cache::store('octane')->put('last-random-number', $number);
                Log::info("New number in cache: ${number}", ['timestamp' => now()]);
            }
        )
        ->seconds(10)
        ->immediate();

        Octane::tick('cache-last-memory-peak',
            function () {
                $number = memory_get_peak_usage();
                Cache::store('octane')->put('last-memory-peak', $number);
                Log::info("Memory peak: ${number}", ['timestamp' => now()]);
            }
        )
        ->seconds(10)
        ->immediate();
    }
}
