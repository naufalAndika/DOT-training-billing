<?php

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

Route::post('/billings', 'BillingController@store')->name('billings.store');

Route::name('billings.')->prefix('billings')->group(function () {
    Route::post('/', 'BillingController@store')->name('store');
    Route::get('{id}/pay', 'BillingController@pay')->name('pay');
    Route::get('{id}/cancel', 'BillingController@destroy')->name('cancel');
});
