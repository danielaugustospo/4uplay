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


    public function index(Request $request)
    {
        $dtinicial = $request->get('datainicial');
        $dtfinal = $request->get('datafinal');

        $idUsuario = Auth::id();
        return view('relatorios.sintetico2', compact('idUsuario','dtinicial','dtfinal'));
    }

    public function dadosRelatorio(Request $request)
    {
        $relatorioSintetico = new RelatorioSintetico();

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
            $complemento = "";

            $stringConsulta = $relatorioSintetico->consultaRelatorioSintetico($complemento, $dtinicial, $dtfinal);
        }
        elseif($permiteListagemCompleta == 0){
            $complemento = " and u.id = ".$idusuario. " ";
            $stringConsulta = $relatorioSintetico->consultaRelatorioSintetico($complemento, $dtinicial, $dtfinal);
        }
        $dadosConsulta = DB::select($stringConsulta);

        // foreach ($dadosConsulta as $d){
        //     $d->datacriativo = '01-'.$d->datacriativo;
        //     strtotime($d->datacriativo);
        // } 
        return $dadosConsulta;

    }

}
