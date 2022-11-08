<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

class MyClass
{
    public static $number = 0;

    public function __construct()
    {
        print "Construct\n";
    }
    public function __destruct()
    {
        print "Deconstruct\n";
    }
    public function add()
    {
        self::$number++;
    }
    public function get()
    {
        return self::$number;
    }
}

Route::get('/static-class', function (MyClass $myclass) {
    $myclass->add();
    return $myclass->get();
});
