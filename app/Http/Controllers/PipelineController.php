<?php

namespace App\Http\Controllers;

use App\Pipeline;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use App\ClienteLicenciado;



use App\User;
use Spatie\Permission\Models\Role;
use App\Providers\FormatacoesServiceProvider;


class PipelineController extends Controller
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
        return view('pipeline.index', compact('idUsuario'));
    }

    public function inicio(Request $request)
    {
        $pipeline = new Pipeline();

        $idlicenciado = json_decode($request->post('acesso'));
        $user = User::find($idlicenciado);
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
            $stringConsulta = $pipeline->consultaPipeline(null);
        }
        elseif($permiteListagemCompleta == 0){
            $stringConsulta = $pipeline->consultaPipeline($idlicenciado);
        }
        $dadosConsulta = DB::select($stringConsulta);

        foreach($dadosConsulta as $dados){
            $dados->proposta = FormatacoesServiceProvider::validaValoresParaView($dados->proposta);
            $dados->fechamento = FormatacoesServiceProvider::validaValoresParaView($dados->fechamento);
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
                    if (($v == 'Administrador') || ($v == 'DESENVOLVIMENTO')){
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
        foreach($dadosConsulta as $dados){
            $dados->h_proposta = FormatacoesServiceProvider::validaValoresParaView($dados->h_proposta);
            $dados->h_fechamento = FormatacoesServiceProvider::validaValoresParaView($dados->h_fechamento);
        }

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

        $pipeline = new Pipeline();
        $dadosrequisicao = json_decode($request->post('models'));
        $idlicenciado = json_decode($request->post('acesso'));
       

        
        
        
        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->cliente);

        $pipelineatualizado = Pipeline::create([
            'cliente'           => $dadosrequisicao[0]->cliente,
            'qualificacao'      => $dadosrequisicao[0]->qualificacao,
            'proposta'          => $dadosrequisicao[0]->proposta,
            'fechamento'        => $dadosrequisicao[0]->fechamento,
            'negociacao'        => $dadosrequisicao[0]->negociacao,
            'idautor'           => $idlicenciado,
            'id_ult_alterador'  => $idlicenciado,
            'excluidopipeline'  => '0',
        ]);

        
        if ($pipelineatualizado) {
            DB::commit();

            $dadosrequisicao[0]->id = $pipelineatualizado->id;
            DB::insert($pipeline->insereHistorico($dadosrequisicao, $idlicenciado, $dadoscliente["c_nome"], $tipo=1));

        } else {
            DB::rollBack();
        }


        $arrayRetorno = array(
            'id'                => $pipelineatualizado->id,
            'cliente'           => $dadoscliente["c_nome"],
            'qualificacao'      => $dadosrequisicao[0]->qualificacao,
            'proposta'          => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->proposta),
            'fechamento'        => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->fechamento),
            'negociacao'        => $dadosrequisicao[0]->negociacao,
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
        $pipeline = new Pipeline();
        $dadosrequisicao = json_decode($request->post('models'));
        $idlicenciado = json_decode($request->post('acesso'));
        settype($dadosrequisicao[0]->id, "string");
    
        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->cliente);

        $pipelineatualizado = DB::table('pipeline')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                'cliente'           => $dadosrequisicao[0]->cliente,
                'qualificacao'      => $dadosrequisicao[0]->qualificacao,
                'proposta'          => $dadosrequisicao[0]->proposta,
                'fechamento'        => $dadosrequisicao[0]->fechamento,
                'negociacao'        => $dadosrequisicao[0]->negociacao,
                'id_ult_alterador'  => $idlicenciado,
                'updated_at'        => date("Y-m-d H:i:s")

                // 'created_at'        => $dadosrequisicao[0]->created_at,
            ]);

        if ($pipelineatualizado) {
            DB::commit();
            DB::insert($pipeline->insereHistorico($dadosrequisicao, $idlicenciado, $dadoscliente["c_nome"], $tipo=2));
        } else {
            DB::rollBack();
        }



        $arrayRetorno = array(
            'id'                => $dadosrequisicao[0]->id,
            // 'cliente'           => $dadosrequisicao[0]->cliente,
            'cliente'           => $dadoscliente["c_nome"],
            'qualificacao'      => $dadosrequisicao[0]->qualificacao,
            'proposta'          => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->proposta),
            'fechamento'        => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->fechamento),
            'negociacao'        => $dadosrequisicao[0]->negociacao,
            'created_at'        => $dadosrequisicao[0]->created_at
        );
        echo json_encode($arrayRetorno);
    }


    public function marcaComoExcluido(Request $request)
    {
        $pipeline = new Pipeline();
      
        $id            = json_decode($request->post('id'));
        settype($id, "string");
        $dadospipeline = Pipeline::find($id);

        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadospipeline->cliente);

        $pipelineatualizado = DB::table('pipeline')
            ->where('id', $id)
            ->update([
                'id_ult_alterador'  => $id,
                'excluidopipeline'  => '1',
            ]);

        if ($pipelineatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        DB::insert($pipeline->insereHistorico($dadospipeline, null, $dadoscliente["c_nome"], $tipo=3));

        $pipeline = new Pipeline();
        $stringConsulta = $pipeline->consultaPipeline($id);
        $dadosConsulta = DB::select($stringConsulta);

        foreach($dadosConsulta as $dados){
            $dados->proposta = FormatacoesServiceProvider::validaValoresParaView($dados->proposta);
            $dados->fechamento = FormatacoesServiceProvider::validaValoresParaView($dados->fechamento);
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
