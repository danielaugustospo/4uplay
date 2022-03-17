<?php

namespace App\Http\Controllers;

use App\Criativo;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;


use App\User;
use App\Totem;
use Spatie\Permission\Models\Role;
use App\Providers\FormatacoesServiceProvider;
use App\ClienteLicenciado;



class CriativoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:pipeline-list|pipeline-create|pipeline-edit|pipeline-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:pipeline-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pipeline-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pipeline-delete', ['only' => ['destroy']]);

        // $this->middleware('auth');
    }

    public function index()
    {

        $idUsuario = Auth::id();
        return view('criativo.index', compact('idUsuario'));
    }

    public function inicio(Request $request)
    {
        $criativo = new Criativo();

        $idlicenciado = json_decode($request->post('acesso'));
        $user = User::find($idlicenciado);
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
            $stringConsulta = $criativo->consultaCriativo(null);
        }
        elseif($permiteListagemCompleta == 0){
            $stringConsulta = $criativo->consultaCriativo($idlicenciado);
        }
        $dadosConsulta = DB::select($stringConsulta);

        foreach($dadosConsulta as $dados){
            $dados->valunit     = FormatacoesServiceProvider::validaValoresParaView($dados->valunit);
            $dados->valtotal    = FormatacoesServiceProvider::validaValoresParaView($dados->valtotal);
        }

        return $dadosConsulta;
    }

    public function inicioHistorico(Request $request)
    {
        $pipeline = new Pipeline();

        $idlicenciado = json_decode($request->post('acesso'));
        $user = User::find($idlicenciado);
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
            $stringConsulta = $pipeline->consultaHistoricoPipeline(null);
        }
        elseif($permiteListagemCompleta == 0){
            $stringConsulta = $pipeline->consultaHistoricoPipeline($idlicenciado);
        }
        $dadosConsulta = DB::select($stringConsulta);
        return $dadosConsulta;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function cria(Request $request)
    {

        $criativo = new Criativo();
        $dadosrequisicao = json_decode($request->post('models'));
        $idlicenciado = json_decode($request->post('acesso'));
        settype($idlicenciado, "string");



        $idTotem = $dadosrequisicao[0]->idtotem->id;

        $dadostotem = Totem::find($idTotem);
        $nSerieTotem = $dadostotem["n_serie"];
        $dadosrequisicao[0]->nSerieTotem = $nSerieTotem;

        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->cliente);
        $dadosrequisicao[0]->valtotal = $dadosrequisicao[0]->quantidade * $dadosrequisicao[0]->valunit;
        $criativoatualizado = Criativo::create([
            'cliente'           => $dadosrequisicao[0]->cliente,
            'tipocriativo'      => $dadosrequisicao[0]->tipocriativo,
            'quantidade'        => $dadosrequisicao[0]->quantidade,
            'valunit'           => $dadosrequisicao[0]->valunit,
            'valtotal'          => $dadosrequisicao[0]->valtotal,
            'idtotem'           => $idTotem,
            'datacriacao'       => $dadosrequisicao[0]->datacriacao,
            'idlicenciado'      => $idlicenciado,
            'id_ult_alterador'  => $idlicenciado,
            'excluidocriativo'  => '0',
        ]);

        
        if ($criativoatualizado) {
            DB::commit();

            $dadosrequisicao[0]->id = $criativoatualizado->id;
            $dadosrequisicao[0]->updated_at = $criativoatualizado->updated_at;
            DB::insert($criativo->insereHistorico($dadosrequisicao, $idlicenciado, $dadoscliente["c_nome"], $tipo=1));

        } else {
            DB::rollBack();
        }


        $arrayRetorno = array(
            'id'                => $dadosrequisicao[0]->id,
            // 'cliente'           => $dadosrequisicao[0]->cliente,
            'cliente'           => $dadoscliente["c_nome"],
            'tipocriativo'      => $dadosrequisicao[0]->tipocriativo,
            'quantidade'        => $dadosrequisicao[0]->quantidade,
            'valunit'           => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valunit),
            'valtotal'          => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valtotal),
            'idlicenciado'      => $idlicenciado,
            'idtotem'           => $nSerieTotem,
            'datacriacao'       => $dadosrequisicao[0]->datacriacao,
            'created_at'        => $dadosrequisicao[0]->created_at
        );

        echo json_encode($arrayRetorno);

    }

    public function exibe()
    {
        # code...
    }

    public function atualiza(Request $request)
    {
        $criativo = new Criativo();
        $dadosrequisicao = json_decode($request->post('models'));
        $idlicenciado = json_decode($request->post('acesso'));
        settype($dadosrequisicao[0]->id, "string");
        
        
        $idTotem = $dadosrequisicao[0]->idtotem;
        settype($idTotem, "string");
        $dadostotem = Totem::find($idTotem);
        $nSerieTotem = $dadostotem["n_serie"];
        $dadosrequisicao[0]->nSerieTotem = $nSerieTotem;


        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->cliente);
        $dadosrequisicao[0]->valtotal = $dadosrequisicao[0]->quantidade * $dadosrequisicao[0]->valunit;

        $criativoatualizado = DB::table('criativo')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                'cliente'           => $dadosrequisicao[0]->cliente,
                'tipocriativo'      => $dadosrequisicao[0]->tipocriativo,
                'quantidade'        => $dadosrequisicao[0]->quantidade,
                'valunit'           => $dadosrequisicao[0]->valunit,
                'valtotal'          => $dadosrequisicao[0]->valtotal,
                'idtotem'           => $dadosrequisicao[0]->idtotem,
                'datacriacao'       => $dadosrequisicao[0]->datacriacao,   
                'idlicenciado'      => $idlicenciado,
                'id_ult_alterador'  => $idlicenciado,
                'updated_at'        => date("Y-m-d H:i:s")

                // 'excluidocriativo'  => '0',
            ]);

        if ($criativoatualizado) {
            DB::commit();
            DB::insert($criativo->insereHistorico($dadosrequisicao, $idlicenciado, $dadoscliente["c_nome"], $tipo=2));

        } else {
            DB::rollBack();
        }


        
        $arrayRetorno = array(
            // 'cliente'           => $dadosrequisicao[0]->cliente,
            'cliente'           => $dadoscliente["c_nome"],
            'tipocriativo'      => $dadosrequisicao[0]->tipocriativo,
            'quantidade'        => $dadosrequisicao[0]->quantidade,
            'valunit'           => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valunit),
            'valtotal'          => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valtotal),
            'idtotem'           => $dadosrequisicao[0]->nSerieTotem,
            'datacriacao'       => $dadosrequisicao[0]->datacriacao,
            'idlicenciado'      => $idlicenciado,
            'id_ult_alterador'  => $idlicenciado,
            'created_at'        => $dadosrequisicao[0]->created_at
        );
        echo json_encode($arrayRetorno);
    }


    public function marcaComoExcluido(Request $request)
    {
        $criativo = new Criativo();
      
        $id = json_decode($request->post('id'));
        $nSerieTotem = $request->get('totem');

        settype($id, "string");
        $dadoscriativo = Criativo::find($id);
        $dadoscriativo->nSerieTotem = $nSerieTotem;

        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadoscriativo->cliente);

        $criativoatualizado = DB::table('criativo')
            ->where('id', $id)
            ->update([
                'id_ult_alterador'  => $id,
                'excluidocriativo'  => '1',
            ]);

        if ($criativoatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        DB::insert($criativo->insereHistorico($dadoscriativo, null, $dadoscliente["c_nome"], $tipo=3));

        $criativo = new Criativo();
        $stringConsulta = $criativo->consultaCriativo($id);
        $dadosConsulta = DB::select($stringConsulta);
        foreach($dadosConsulta as $dados){
            $dados->valunit     = FormatacoesServiceProvider::validaValoresParaView($dados->valunit);
            $dados->valtotal    = FormatacoesServiceProvider::validaValoresParaView($dados->valtotal);
        }

        echo json_encode($dadosConsulta);
    }

    public function buscaLicenciado($id)
    {
        $nomelicenciado = DB::select("SELECT name from users where id = $id");
        return $nomelicenciado;
    }

    public function deleta()
    {
        # code...
    }
}
