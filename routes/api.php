<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

Route::get('/listapipeline', 'PipelineController@inicio')->name('listapipeline');
Route::get('/criapipeline', 'PipelineController@cria')->name('criapipeline');
Route::post('/atualizapipeline', 'PipelineController@atualiza')->name('atualizapipeline');
Route::post('/excluipipeline', 'PipelineController@marcaComoExcluido')->name('excluipipeline');

