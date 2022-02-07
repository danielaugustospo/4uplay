<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\RelatorioFinanceiro;
use Illuminate\Support\Facades\Auth;


use App\User;
use Spatie\Permission\Models\Role;


class RelatorioFinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:relatorio-financeiro', ['only' => ['index']]);
    }


    public function index()
    {

        $idUsuario = Auth::id();
        return view('relatorios.financeiro2', compact('idUsuario'));
    }

    public function dadosRelatorio(Request $request)
    {
        $relatorioFinanceiro = new RelatorioFinanceiro();

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
            $stringConsulta = $relatorioFinanceiro->consultaRelatorioFinanceiro();
        }
        elseif($permiteListagemCompleta == 0){
            abort(401);
        }
        $dadosConsulta = DB::select($stringConsulta);
        return $dadosConsulta;
    }

}
