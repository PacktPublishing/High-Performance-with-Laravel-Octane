<?php

namespace App\Providers;

use App\Models\Event;
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

        /**
         * Every 60 seconds, some queries are executed.
         * The results of the queries are stored in the
         * Octane Cache
         */
        Octane::tick('caching-query', function () {
            Log::info('caching-query.', ['timestamp' => now()]);
            $time = hrtime(true);
            $count = Event::count();
            $eventsInfo = Event::ofType('INFO')->get();
            $eventsWarning = Event::ofType('WARNING')->get();
            $eventsAlert = Event::ofType('ALERT')->get();
            $time = (hrtime(true) - $time) / 1_000_000;
            $result = ['count' => $count,
                'eventsInfo'=> $eventsInfo,
                'eventsWarning' => $eventsWarning,
                'eventsAlert'=> $eventsAlert,
            ];

            Cache::store('octane')->put('cached-result-tick', $result);
        })
        ->seconds(60)
        ->immediate();
    }
}
