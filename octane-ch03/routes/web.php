<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

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

Route::get('/serial-task', function () {
    $start = hrtime(true);
    [$fn1, $fn2] = [
        function () {
            sleep(2);

            return 'Hello';
        },
        function () {
            sleep(2);

            return 'World';
        },
    ];
    $result1 = $fn1();
    $result2 = $fn2();
    $end = hrtime(true);

    return "{$result1} {$result2} in ".($end - $start) / 1000000000 .' seconds';
});
use Illuminate\Support\Facades\Log;

Route::get('/concurrent-task', function () {
    $start = hrtime(true);
    [$result1, $result2] = Octane::concurrently([
        function () {
            sleep(2);

            return 'Hello';
        },
        function () {
            sleep(2);

            return 'World';
        },
    ]);
    $end = hrtime(true);

    return "{$result1} {$result2} in ".($end - $start) / 1000000000 .' seconds';
});

use Illuminate\Support\Facades\Route;

Route::get('/who-is-the-first', function () {
    $start = hrtime(true);
    [$result1, $result2] = Octane::concurrently([
        function () {
            sleep(2);
            Log::info('Concurrent function: First');

            return 'Hello';
        },
        function () use (&$variable) {
            sleep(1);
            Log::info('Concurrent function: Second');

            return 'World';
        },
    ]);
    $end = hrtime(true);

    return "{$result1} {$result2} in ".($end - $start) / 1000000000 .' seconds';
});

use Laravel\Octane\Facades\Octane;

Route::get('/get-random-number', function () {
    $number = Cache::store('octane')->get('last-random-number', 0);

    return $number;
});
Route::get('/last-memory-peak', function () {
    $number = Cache::store('octane')->get('last-memory-peak', 0);

    return $number;
});

Route::get('/increment-number', function () {
    $number = Cache::store('octane')->increment('my-number');

    return $number;
});
Route::get('/decrement-number', function () {
    $number = Cache::store('octane')->decrement('my-number');

    return $number;
});
Route::get('/get-number', function () {
    $number = Cache::store('octane')->get('my-number', 0);

    return $number;
});

Route::get('/save-many', function () {
    Cache::store('octane')->putMany([
        'my-number' => 42,
        'my-string' => 'Hello World!',
        'my-array' => ['Kiwi', 'Strawberry', 'Lemon'],
    ]);

    return 'Items saved!';
});
Route::get('/get-many', function () {
    $array = Cache::store('octane')->many([
        'my-number',
        'my-string',
        'my-array',
    ]);

    return $array;
});
Route::get('/get-one-from-many/{key?}', function ($key = 'my-number') {
    return Cache::store('octane')->get($key);
});

Route::get('/table-create', function () {
    // Getting the table instance
    $table = Octane::table('my-table');
    // looping 1..90 creating rows with fake() helper
    for ($i = 1; $i <= 90; $i++) {
        $table->set($i,
        [
            'uuid' => fake()->uuid(),
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 99),
            'value' => fake()->randomFloat(2, 0, 1000),
        ]);
    }

    return 'Table created!';
});
Route::get('/table-get', function () {
    $table = Octane::table('my-table');
    $row = $table->get(1);

    return $row;
});

Route::get('/table-get-all', function () {
    $table = Octane::table('my-table');
    $rows = [];
    foreach ($table as $key => $value) {
        $rows[$key] = $table->get($key);
    }
    // adding as first row the table rows count
    $rows[0] = count($table);

    return $rows;
});

Route::get('/stats', function () {
    $server = App::make(Swoole\Http\Server::class);

    return $server->stats();
});
