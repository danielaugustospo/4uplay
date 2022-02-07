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

// Route::get('/listapipeline', 'PipelineController@inicio')->name('listapipeline');
// Route::get('/criapipeline', 'PipelineController@cria')->name('criapipeline');
// Route::post('/atualizapipeline', 'PipelineController@atualiza')->name('atualizapipeline');
// Route::post('/excluipipeline', 'PipelineController@marcaComoExcluido')->name('excluipipeline');


Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');


Route::get('/listapipeline', 'PipelineController@inicio')->name('listapipeline');
Route::get('/listahistoricopipeline', 'PipelineController@inicioHistorico')->name('listahistoricopipeline');
Route::get('/criapipeline', 'PipelineController@cria')->name('criapipeline');
Route::post('/excluipipeline', 'PipelineController@marcaComoExcluido')->name('excluipipeline');
Route::get('/atualizapipeline', 'PipelineController@atualiza')->name('atualizapipeline');

Route::get('/listacriativo', 'CriativoController@inicio')->name('listacriativo');
Route::get('/listahistoricocriativo', 'CriativoController@inicioHistorico')->name('listahistoricocriativo');
Route::get('/criacriativo', 'CriativoController@cria')->name('criacriativo');
Route::post('/excluicriativo', 'CriativoController@marcaComoExcluido')->name('excluicriativo');
Route::get('/atualizacriativo', 'CriativoController@atualiza')->name('atualizacriativo');

Route::get('/listamensalidade', 'MensalidadeController@inicio')->name('listamensalidade');
Route::get('/listahistoricomensalidade', 'MensalidadeController@inicioHistorico')->name('listahistoricomensalidade');
Route::get('/criamensalidade', 'MensalidadeController@cria')->name('criamensalidade');
Route::post('/excluimensalidade', 'MensalidadeController@marcaComoExcluido')->name('excluimensalidade');
Route::get('/atualizamensalidade', 'MensalidadeController@atualiza')->name('atualizamensalidade');

Route::get('/listatotem', 'TotemController@inicio')->name('listatotem');
Route::get('/listahistoricototem', 'TotemController@inicioHistorico')->name('listahistoricototem');
Route::get('/criatotem', 'TotemController@cria')->name('criatotem');
Route::post('/excluitotem', 'TotemController@marcaComoExcluido')->name('excluitotem');
Route::get('/atualizatotem', 'TotemController@atualiza')->name('atualizatotem');
Route::get('/listausuarios', 'TotemController@pegaUsuarios')->name('listausuarios');

Route::get('/listaclientelicenciado', 'ClienteLicenciadoController@inicio')->name('listaclientelicenciado');
Route::get('/listahistoricoclientelicenciado', 'ClienteLicenciadoController@inicioHistorico')->name('listahistoricoclientelicenciado');
Route::get('/criaclientelicenciado', 'ClienteLicenciadoController@cria')->name('criaclientelicenciado');
Route::post('/excluiclientelicenciado', 'ClienteLicenciadoController@marcaComoExcluido')->name('excluiclientelicenciado');
Route::get('/atualizaclientelicenciado', 'ClienteLicenciadoController@atualiza')->name('atualizaclientelicenciado');

Route::get('/relatoriofinanceiro', 'RelatorioFinanceiroController@dadosRelatorio')->name('relatoriofinanceiro');
Route::get('/relatoriosintetico', 'RelatorioSinteticoController@dadosRelatorio')->name('relatoriosintetico');

Route::group([
    'middleware' => ['jwt.verify'],

], function ($router) {

    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');


});
