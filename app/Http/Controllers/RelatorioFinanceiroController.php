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


    public function index(Request $request)
    {
        $dtinicial = $request->get('datainicial');
        $dtfinal = $request->get('datafinal');

        $idUsuario = Auth::id();
        return view('relatorios.financeiro2', compact('idUsuario','dtinicial','dtfinal'));
    }

    public function dadosRelatorio(Request $request)
    {
        $relatorioFinanceiro = new RelatorioFinanceiro();

        $idusuario = json_decode($request->post('acesso'));

        $dtinicial = $request->get('dtinicial');
        $dtfinal = $request->get('dtfinal');
        date("Y-m-d", strtotime($dtinicial));
        date("Y-m-d", strtotime($dtfinal));

        $user = User::find($idusuario);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $permiteListagemCompleta = 0;
        if(!empty($user->getRoleNames())){
                foreach($user->getRoleNames() as $v){
                    if (($v == 'Admin') || ($v == 'Administrador') || ($v == 'DESENVOLVIMENTO')){
                        $permiteListagemCompleta = 1;
                    }
                }
        }
        if($permiteListagemCompleta == 1){
            $stringConsulta = $relatorioFinanceiro->consultaRelatorioFinanceiro($dtinicial, $dtfinal);
        }
        elseif($permiteListagemCompleta == 0){
            abort(401);
        }
        $dadosConsulta = DB::select($stringConsulta);
        return $dadosConsulta;
    }

}
