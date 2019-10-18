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
Route::get('barrido', 'DepositsController@barrido');

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
Route::get('receipts/{id}/create', 'ReceiptsController@index')->name('receipts.createreceipt');
Route::resource('receipts', 'ReceiptsController');
Route::get('reports.receipts', [
    'uses' => 'ReceiptsController@data',
    'as' => 'reports.receipts',
]);
Route::get('report/receipts', 'ReceiptsController@report')->name('receipts.report');
Route::get('receipts/{business_id}/pending', 'DepositsController@receipts');
//DEPOSITS
Route::resource('deposits', 'DepositsController');
Route::get('reports.deposits', [
    'uses' => 'DepositsController@data',
    'as' => 'reports.deposits',
]);
Route::get('report/deposits', 'DepositsController@report')->name('deposits.report');
//GALONAJES
Route::get('reports.galonajes', [
    'uses' => 'ReceiptsController@data_galonajes',
    'as' => 'reports.galonajes',
]);
Route::get('report/galonajes', 'ReceiptsController@report_galonaje')->name('galonajes.report');
//BANKS
Route::resource('banks', 'BanksController');
Route::get('banks.data', [
    'uses' => 'BanksController@data',
    'as' => 'banks.data',
]);
//PROJECTS
Route::resource('projects', 'ProjectsController');
Route::get('projects.data', [
    'uses' => 'ProjectsController@data',
    'as' => 'projects.data',
]);
//EXPENSES
Route::resource('expenses', 'ExpensesController');
Route::get('expenses.data', [
    'uses' => 'ExpensesController@data',
    'as' => 'expenses.data',
]);
//INCOMES
Route::resource('incomes', 'IncomesController');
Route::get('incomes.data', [
    'uses' => 'IncomesController@data',
    'as' => 'incomes.data',
]);