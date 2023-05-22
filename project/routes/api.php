<?php

use App\Http\Controllers\Api\TimetreeController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['api']], function () {
    Route::get('timetree-calenders', 'Api\TimetreeController@getCalenders');
    Route::get('timetree-schedules', 'Api\TimetreeController@getSchedules');
    Route::get('timetree-add-recommend', 'Api\TimetreeController@addRecommend');
});
