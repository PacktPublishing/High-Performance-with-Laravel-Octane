<?php

use App\Jobs\ProcessSomething;
use Illuminate\Support\Facades\Route;
use Laravel\Octane\Facades\Octane;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $links = [
        [
            'url' => '/time-consuming-request-waiting',
            'text' => 'Time consuming task, sync request',
        ],
        [
            'url' => '/time-consuming-request-async',
            'text' => 'Time consuming task, Async request',
        ],
        [
            'url' => '/time-consuming-request-sync',
            'text' => 'Time consuming task, sync request with dispatchSync()',
        ],
        [
            'url' => '/time-consuming-request-async-octane-route',
            'text' => 'Time consuming task, sync request with dispatchSync(), Octane Route',
        ],

    ];

    return view('welcome', ['links' => $links]);
});
Route::get('/time-consuming-request-waiting', function () {
    $start = hrtime(true);
    $p = app()->make(ProcessSomething::class);
    $p->handle();
    $time = hrtime(true) - $start;

    return view('result', [
        'title' => url()->current(),
        'description' => 'the task is executed',
        'time' => $time,
    ]);
});

Route::get('/time-consuming-request-async', function () {
    $start = hrtime(true);
    ProcessSomething::dispatch()->onQueue('first');
    //dispatch(new ProcessSomething());
    $time = hrtime(true) - $start;

    return view('result', [
        'title' => url()->current(),
        'description' => 'the task has been queued',
        'time' => $time,
    ]);
});

Octane::route('GET', '/time-consuming-request-async-octane-route', function () {
    $start = hrtime(true);
    dispatch(new ProcessSomething());
    $time = hrtime(true) - $start;

    return new Response((string) view('result', [
        'title' => url()->current(),
        'description' => 'the task has been queued',
        'time' => $time,
    ]));
});

Route::get('/time-consuming-request-sync', function () {
    $start = hrtime(true);
    ProcessSomething::dispatchSync();
    $time = hrtime(true) - $start;

    return view('result_nostyle', [
        'title' => url()->current(),
        'description' => 'the task has been complete with dispatchSync()',
        'time' => $time,
    ]);
});
