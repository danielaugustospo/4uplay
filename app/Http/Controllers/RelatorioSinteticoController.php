<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\RelatorioSintetico;
use Illuminate\Support\Facades\Auth;


use App\User;
use Spatie\Permission\Models\Role;

class RelatorioSinteticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:relatorio-sintetico', ['only' => ['index']]);
    }


    public function index()
    {

        $idUsuario = Auth::id();
        return view('relatorios.sintetico2', compact('idUsuario'));
    }

    public function dadosRelatorio(Request $request)
    {
        $relatorioSintetico = new RelatorioSintetico();

        $idusuario = json_decode($request->post('acesso'));
        $user = User::find($idusuario);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $permiteListagemCompleta = 0;
        if(!empty($user->getRoleNames())){
                foreach($user->getRoleNames() as $v){
                    if (($v == 'Administrador') || ($v == 'DESENVOLVIMENTO')){
                        $permiteListagemCompleta = 1;
                    }
                }
        }
        if($permiteListagemCompleta == 1){
            $complemento = "";
            $stringConsulta = $relatorioSintetico->consultaRelatorioSintetico($complemento);
        }
        elseif($permiteListagemCompleta == 0){
            $complemento = " and u.id = ".$idusuario. " ";
            $stringConsulta = $relatorioSintetico->consultaRelatorioSintetico($complemento);
        }
        $dadosConsulta = DB::select($stringConsulta);
        return $dadosConsulta;
    }

}
