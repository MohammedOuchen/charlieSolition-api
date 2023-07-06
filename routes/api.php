<?php

use App\Http\Controllers\Api\V1\TrackerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('V1')->name('api.v1')->group(function(){

    Route::get('/',[TrackerController::class, 'dailySensorCounts']);
    Route::get('/charlie',[TrackerController::class, 'geoFencingTrackers']);
});