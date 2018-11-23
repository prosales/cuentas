<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'ApiController@register');
Route::post('update', 'ApiController@update');
Route::post('login', 'ApiController@login');
Route::post('token', 'ApiController@update_token');
Route::post('location', 'ApiController@location');
Route::get('events/{client_id}', 'ApiController@events_per_client');
Route::get('event/{event_id}/{client_id}/detail', 'ApiController@event_detail');
Route::post('agenda/add', 'ApiController@add_agenda');
Route::post('photos/upload', 'ApiController@upload_photos');
Route::get('photos/{event_id}/{client_id}/gallery', 'ApiController@photo_gallery');

Route::get('send/push', 'NotificacionesController@send_push');

Route::get('event/{id}', 'FrontendController@event');
Route::get('photos/{event_id}/{ultimo_id}', 'FrontendController@photos');
Route::get('top/{event_id}', 'FrontendController@top_users');
Route::get('notification/raffle/{type}', 'FrontendController@raffles');
Route::get('notification/contingente/{type}', 'FrontendController@contingente');
Route::get('notification/car/{type}', 'FrontendController@car');
Route::get('notification/bonus/{type}', 'FrontendController@bonus');
Route::get('notification/winner/{type}/{user_id}', 'FrontendController@winner');
Route::get('notification/all', 'FrontController@all');
Route::get('notification/one', 'FrontController@one');