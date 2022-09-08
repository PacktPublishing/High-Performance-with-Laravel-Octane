<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Response;
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
Route::get('/dashboard', function () {
    return new Response((new DashboardController)->index());
});
Octane::route('GET', '/dashboard-concurrent', function () {
    return new Response((new DashboardController)->indexConcurrent());
});
Octane::route('GET', '/dashboard-concurrent-cached', function () {
    return new Response((new DashboardController)->indexConcurrentCached());
});
Octane::route('GET', '/dashboard-tick-cached', function () {
    return new Response((new DashboardController)->indexTickCached());
});
