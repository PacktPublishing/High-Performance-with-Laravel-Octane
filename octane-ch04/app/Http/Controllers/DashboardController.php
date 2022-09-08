<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\Cache;
use Laravel\Octane\Exceptions\TaskTimeoutException;
use Laravel\Octane\Facades\Octane;

class DashboardController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $time = hrtime(true);
        $count = Event::count();
        $eventsInfo = Event::ofType('INFO')->get();
        $eventsWarning = Event::ofType('WARNING')->get();
        $eventsAlert = Event::ofType('ALERT')->get();
        $time = (hrtime(true) - $time) / 1_000_000;

        return view('dashboard.index',
            compact('count', 'eventsInfo', 'eventsWarning', 'eventsAlert', 'time')
        );
    }

    public function indexConcurrent()
    {
        $time = hrtime(true);
        try {
            [$count,$eventsInfo,$eventsWarning,$eventsAlert] =
            Octane::concurrently([
                fn () => Event::count(),
                fn () => Event::ofType('INFO')->get(),
                fn () => Event::ofType('WARNING')->get(),
                fn () => Event::ofType('ALERT')->get(),
            ]);
        } catch (TaskTimeoutException $e) {
            return 'Error: '.$e->getMessage();
        }
        $time = (hrtime(true) - $time) / 1_000_000;

        return view('dashboard.index',
            compact('count', 'eventsInfo', 'eventsWarning', 'eventsAlert', 'time')
        );
    }

    public function indexConcurrentCached()
    {
        $time = hrtime(true);
        try {
            [$count,$eventsInfo,$eventsWarning,$eventsAlert] =
        Cache::store('octane')->remember(
            key: 'key-event-cache',
            ttl: 20,
            callback: function () {
                return Octane::concurrently([
                    fn () => Event::count(),
                    fn () => Event::ofType('INFO')->get(),
                    fn () => Event::ofType('WARNING')->get(),
                    fn () => Event::ofType('ALERT')->get(),
                ]);
            }
        );
        } catch (Exception $e) {
            return 'Error: '.$e->getMessage();
        }
        $time = (hrtime(true) - $time) / 1_000_000;

        return view('dashboard.index',
            compact('count', 'eventsInfo', 'eventsWarning', 'eventsAlert', 'time')
        );
    }
}
