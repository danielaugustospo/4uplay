<?php


// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider;
use Symfony\Component\HttpFoundation\Request;

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

Route::get('/', function () { return view('index'); });
Route::get('/relfinanceiro', function () { return view('relatorios.financeiro'); });
Route::get('/relsintetico', function () { return view('relatorios.sintetico'); });

Route::get('/cadastro', function () { return view('cadastro.index'); });

// Route::get('/pipeline', function () { return view('pipeline.index'); });
Route::get('/criativo', function () { return view('criativo.index'); });
// Route::get('/criativo2', function () { return view('criativo.index2'); });
Route::get('/historicopipeline', function () { return view('pipeline.historico'); })->name('historicopipeline');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('products','ProductController');
    Route::resource('pipeline','PipelineController');
    Route::resource('mensalidade','MensalidadeController');
    Route::get('/cadastro2', 'UserController@cadastro')->name('cadastro2');
    Route::get('/desativaUsuario', 'UserController@desativaUsuario')->name('desativaUsuario');
    Route::get('/apiusuarios', 'UserController@apiusuarios')->name('apiusuarios');

    Route::get('/financeiro2', 'RelatorioFinanceiroController@index')->name('financeiro2');
    Route::get('/sintetico2', 'RelatorioSinteticoController@index')->name('sintetico2');

    Route::get('/criativo2', 'CriativoController@index')->name('criativo2');
    Route::get('/pipeline', 'PipelineController@index')->name('pipeline');
    Route::get('/mensalidade', 'MensalidadeController@index')->name('mensalidade');
    Route::get('/totem', 'TotemController@index')->name('totem');
    Route::get('/clientelicenciado', 'ClienteLicenciadoController@index')->name('clientelicenciado');
    
});


