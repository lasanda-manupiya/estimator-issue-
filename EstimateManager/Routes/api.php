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

Route::middleware('auth:api')->get('/estimatemanager', function (Request $request) {
    return $request->user();
});
Route::post('library/{id}', 'EstimateManagerController@library_by_project');
Route::post('library/expanded/{id}', 'EstimateManagerController@expanded_library_by_project');