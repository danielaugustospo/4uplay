<?php


// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('products','ProductController');
    Route::resource('pipeline','PipelineController');
});


