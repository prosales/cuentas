<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

//USERS
Route::resource('users', 'UsersController');
Route::get('users.data', [
    'uses' => 'UsersController@data',
    'as' => 'users.data',
]);
//BUSINESS
Route::resource('business', 'BusinessController');
Route::get('business.data', [
    'uses' => 'BusinessController@data',
    'as' => 'business.data',
]);
//DRIVERS
Route::resource('drivers', 'DriversController');
Route::get('drivers.data', [
    'uses' => 'DriversController@data',
    'as' => 'drivers.data',
]);
//RECEIPTS
Route::resource('receipts', 'ReceiptsController');
Route::get('reports.receipts', [
    'uses' => 'ReceiptsController@data',
    'as' => 'reports.receipts',
]);
Route::get('report/receipts', 'ReceiptsController@report')->name('receipts.report');
//DEPOSITS
Route::resource('deposits', 'DepositsController');
Route::get('reports.deposits', [
    'uses' => 'DepositsController@data',
    'as' => 'reports.deposits',
]);
Route::get('report/deposits', 'DepositsController@report')->name('deposits.report');
