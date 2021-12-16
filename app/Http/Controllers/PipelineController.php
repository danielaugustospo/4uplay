<?php

namespace App\Http\Controllers;

use App\Pipeline;
use Illuminate\Support\Facades\App;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Loader\ObjectLoader;
use DB;
use App\Providers\AppServiceProvider;

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
    }

    public function index()
    {

    }

    public function inicio()
    {
        $pipeline = new Pipeline();
        $stringConsulta = $pipeline->consultaPipeline(null);
        $dadosConsulta = DB::select($stringConsulta);
        return $dadosConsulta;
    }


    public function cria(Request $request)
    {
        $dadosrequisicao = json_decode($request->post('models'));

        DB::beginTransaction();

        $pipelineatualizado = Pipeline::create([
                'cliente'           => $dadosrequisicao[0]->cliente,
                'qualificacao'      => $dadosrequisicao[0]->qualificacao,
                'proposta'          => $dadosrequisicao[0]->proposta,
                'fechamento'        => $dadosrequisicao[0]->fechamento,
                'negociacao'        => $dadosrequisicao[0]->negociacao,
                'idautor'           => '1',
                'id_ult_alterador'  => '1',
                'excluidopipeline'  => '0',
            ]);

        if ($pipelineatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        $arrayRetorno = array(
            'cliente'           => $dadosrequisicao[0]->cliente,
            'qualificacao'      => $dadosrequisicao[0]->qualificacao,
            'proposta'          => $dadosrequisicao[0]->proposta,
            'fechamento'        => $dadosrequisicao[0]->fechamento,
            'negociacao'        => $dadosrequisicao[0]->negociacao,
             'created_at'        => $dadosrequisicao[0]->created_at
        );

        echo json_encode($arrayRetorno);
    }

    public function exibe(Type $var = null)
    {
        # code...
    }

    public function atualiza(Request $request)
    {

        $dadosrequisicao = json_decode($request->post('models'));
        
        DB::beginTransaction();

        $pipelineatualizado = DB::table('pipeline')
            ->where('id', $dadosrequisicao[0]->id)
            ->update([
                'cliente' => $dadosrequisicao[0]->cliente,
                'qualificacao'      => $dadosrequisicao[0]->qualificacao,
                'proposta'          => $dadosrequisicao[0]->proposta,
                'fechamento'        => $dadosrequisicao[0]->fechamento,
                'negociacao'        => $dadosrequisicao[0]->negociacao,
                'id_ult_alterador'  => '1',
                // 'created_at'        => $dadosrequisicao[0]->created_at,
            ]);

        if ($pipelineatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        $arrayRetorno = array(
            'cliente' => $dadosrequisicao[0]->cliente,
            'qualificacao'      => $dadosrequisicao[0]->qualificacao,
            'proposta'          => $dadosrequisicao[0]->proposta,
            'fechamento'        => $dadosrequisicao[0]->fechamento,
            'negociacao'        => $dadosrequisicao[0]->negociacao,
            'created_at'        => $dadosrequisicao[0]->created_at
        );
        echo json_encode($arrayRetorno);
    }


    public function marcaComoExcluido(Request $request)
    {
        $dadosrequisicao = json_decode($request->post('id'));

        DB::beginTransaction();

        $pipelineatualizado = DB::table('pipeline')
            ->where('id', $dadosrequisicao)
            ->update([
                'id_ult_alterador'  => '1',
                'excluidopipeline'  => '1',
            ]);

        if ($pipelineatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }


        $pipeline = new Pipeline();
        $stringConsulta = $pipeline->consultaPipeline($dadosrequisicao);
        $dadosConsulta = DB::select($stringConsulta);

        echo json_encode($dadosConsulta);



    }

    public function deleta(Type $var = null)
    {
        # code...
    }


}
