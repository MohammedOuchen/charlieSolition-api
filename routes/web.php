<?php

use App\Http\Controllers\Api\V1\TrackerController;
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

Route::get('/',[TrackerController::class, 'dailySensorCounts']);
Route::get('/charlie',[TrackerController::class, 'geoFencingTrackers']);
