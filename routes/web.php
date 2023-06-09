<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// map
Route::get('/map', 'App\Http\Controllers\MapController@index');

// calendar
Route::get('/calendar-event', 'App\Http\Controllers\CalendarController@index');
Route::post('/calendar-crud-ajax', 'App\Http\Controllers\CalendarController@calendarEvents');