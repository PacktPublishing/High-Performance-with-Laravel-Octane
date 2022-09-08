<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Octane\Facades\Octane;

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
    return view('welcome');
});

Octane::route('GET', '/dashboard', function () {
    return new Response((new DashboardController)->index());
});
Octane::route('GET', '/dashboard-concurrent', function () {
    return new Response((new DashboardController)->indexConcurrent());
});
Octane::route('GET', '/dashboard-concurrent-cached', function () {
    return new Response((new DashboardController)->indexConcurrentCached());
});

Octane::route('GET', '/a', function () {
    return new Response(view('welcome'));
});
Route::get('/b', function () {
    return new Response(view('welcome'));
});

Octane::route('GET', '/api/sentence', function () {
    //sleep(1);

    return response()->json([
        'text' => fake()->sentence(),
    ]);
});
Octane::route('GET', '/api/name', function () {
    //sleep(1);

    return response()->json([
        'name' => fake()->name(),
    ]);
});
Octane::route('GET', '/api/error', function () {
    return response(
        status: 500
    );
});

Route::get('/httpcall/sequence', function () {
    $time = hrtime(true);
    $sentenceJson = Http::get('http://127.0.0.1:8000/api/sentence')->json() ?? [];
    $nameJson = Http::get('http://127.0.0.1:8000/api/name')->json() ?? [];
    $time = hrtime(true) - $time;

    return response()->json(
        array_merge(
            $sentenceJson,
            $nameJson,
            ['time_ms' => $time / 1_000_000]
        )
    );
});

Route::get('/httpcall/parallel', function () {
    $time = hrtime(true);
    [$sentenceJson, $nameJson] = Octane::concurrently([
        fn () => Http::get('http://127.0.0.1:8000/api/sentence')->json(),
        fn () => Http::get('http://127.0.0.1:8000/api/name')->json(),
    ]
    );
    $time = hrtime(true) - $time;

    return response()->json(
        array_merge(
            $sentenceJson,
            $nameJson,
            ['time_ms' => $time / 1_000_000]
        )
    );
});

Route::get('/httpcall/parallel-witherror', function () {
    $time = hrtime(true);
    $sentenceJson = [];
    $nameJson = [];
    try {
        [$sentenceJson, $nameJson] = Octane::concurrently([
            fn () => Http::get('http://127.0.0.1:8000/api/sentence')->json() ?? [],
            fn () => Http::get('http://127.0.0.1:8000/api/error')->json() ?? [],
        ]
        );
    } catch (Exception $e) {
        // The error: $e->getMessage();
    }
    $time = hrtime(true) - $time;

    return response()->json(
        array_merge(
            $sentenceJson,
            $nameJson,
            ['time_ms' => $time / 1_000_000]
        )
    );
});

Octane::route('GET', '/httpcall/caching', function () {
    $time = hrtime(true);
    $sentenceJson = [];
    $nameJson = [];
    try {
        [$sentenceJson, $nameJson] =
        Cache::store('octane')->remember('key-checking', 20, function () {
            return Octane::concurrently([
                fn () => Http::get('http://127.0.0.1:8000/api/sentence')->json(),
                fn () => Http::get('http://127.0.0.1:8000/api/name')->json(),
            ]);
        });
    } catch (Exception $e) {
        // The error: $e->getMessage();
    }
    $time = hrtime(true) - $time;

    return response()->json(
        array_merge(
            $sentenceJson,
            $nameJson,
            ['time_ms' => $time / 1_000_000]
        )
    );
});

Octane::route('GET', '/httpcall/parallel-caching', function () {
    $getHttpCached = function ($url) {
        $data = Cache::store('octane')->remember('key-'.$url, 20, function () use ($url) {
            return Http::get('http://127.0.0.1:8000/api/'.$url)->json() ?? [];
        });

        return $data;
    };
    $time = hrtime(true);
    $sentenceJson = [];
    $nameJson = [];
    try {
        [$sentenceJson, $nameJson] = Octane::concurrently([
            fn () => $getHttpCached('sentence'),
            fn () => $getHttpCached('name'),
        ]
        );
    } catch (Exception $e) {
        // The error: $e->getMessage();
    }
    $time = hrtime(true) - $time;

    return response()->json(
        array_merge(
            $sentenceJson,
            $nameJson,
            ['time_ms' => $time / 1_000_000]
        )
    );
});
