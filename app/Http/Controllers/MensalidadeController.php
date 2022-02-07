<?php

namespace App\Http\Controllers;

use App\Mensalidade;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;


use App\User;
use Spatie\Permission\Models\Role;
use App\Providers\FormatacoesServiceProvider;



class MensalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:mensalidade-list|mensalidade-create|mensalidade-edit|mensalidade-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:mensalidade-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:mensalidade-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mensalidade-delete', ['only' => ['destroy']]);

        // $this->middleware('auth');
    }

    public function index()
    {

        $id_usr_criador = Auth::id();

        return view('mensalidade.index', compact('id_usr_criador'));
    }


    public function inicio(Request $request)
    {
        $mensalidade = new Mensalidade();

        $id_usr_criador = json_decode($request->post('acesso'));
        $user = User::find($id_usr_criador);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $permiteListagemCompleta = 0;
        if (!empty($user->getRoleNames())) {
            foreach ($user->getRoleNames() as $v) {
                if (($v == 'Administrador') || ($v == 'DESENVOLVIMENTO')) {
                    $permiteListagemCompleta = 1;
                }
            }
        }
        if ($permiteListagemCompleta == 1) {
            $stringConsulta = $mensalidade->consultaMensalidade();
            $dadosConsulta = DB::select($stringConsulta);
            foreach($dadosConsulta as $dados){
                $dados->valor = FormatacoesServiceProvider::validaValoresParaView($dados->valor);
            }
            return $dadosConsulta;
        } elseif ($permiteListagemCompleta == 0) {
            return view('home.index');
        }
    }

    public function inicioHistorico(Request $request)
    {
        $pipeline = new Pipeline();

        $id_usr_criador = json_decode($request->post('acesso'));
        $user = User::find($id_usr_criador);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $permiteListagemCompleta = 0;
        if (!empty($user->getRoleNames())) {
            foreach ($user->getRoleNames() as $v) {
                if (($v == 'Administrador') || ($v == 'DESENVOLVIMENTO')) {
                    $permiteListagemCompleta = 1;
                }
            }
        }
        if ($permiteListagemCompleta == 1) {
            $stringConsulta = $pipeline->consultaHistoricoPipeline(null);
        } elseif ($permiteListagemCompleta == 0) {
            $stringConsulta = $pipeline->consultaHistoricoPipeline($id_usr_criador);
        }
        $dadosConsulta = DB::select($stringConsulta);
        foreach($dadosConsulta as $dados){
            $dados->valor = FormatacoesServiceProvider::validaValoresParaView($dados->valor);
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

        $mensalidade = new Mensalidade();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_usr_criador = json_decode($request->post('acesso'));
        settype($id_usr_criador, "string");

        // $dadosrequisicao[0]->ano = $dadosrequisicao[0]->ano;

        $verificamensalidade = DB::table('mensalidade')->where('ano',$dadosrequisicao[0]->ano)->first();
        if($verificamensalidade) {
            return view('mensalidade.index', compact('id_usr_criador'));        
        }         
        if(!$verificamensalidade) {
 
            DB::beginTransaction();

            $criamensalidade = Mensalidade::create([
                'ano'               => $dadosrequisicao[0]->ano,
                'valor'             => $dadosrequisicao[0]->valor,
                'id_usr_criador'    => $id_usr_criador,
                'id_ult_alterador'      => $id_usr_criador,
                'excluidomensalidade'  => '0',
            ]);


            if ($criamensalidade) {
                DB::commit();

                $dadosrequisicao[0]->id = $criamensalidade->id;
                $dadosrequisicao[0]->updated_at = $criamensalidade->updated_at;
                // DB::insert($mensalidade->insereHistorico($dadosrequisicao, $id_usr_criador, $tipo=1));

            } else {
                DB::rollBack();
            }


            $arrayRetorno = array(
                'id'                => $dadosrequisicao[0]->id,
                'ano'           => $dadosrequisicao[0]->ano,
                'valor'             => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valor),
                'id_usr_criador'         => $id_usr_criador,
                'id_ult_alterador'  => $id_usr_criador,
                'created_at'        => $dadosrequisicao[0]->created_at
            );

            echo json_encode($arrayRetorno);
        }
    }

    public function exibe()
    {
        $anoAtual = date('Y');
        $mensalidade    = DB::select("SELECT valor from mensalidade where ano = $anoAtual");
        $dadosreajuste  = DB::select("SELECT * from tb_reajuste where excluidoreajuste = 0");
        
        $mensalidade = $mensalidade[0]->valor;
        foreach ($dadosreajuste as $reajuste) {

            $dataUltimoReajuste = new \DateTime($reajuste->dtreajuste);
            $dataAtual = new \DateTime(date('Y-m-d'));
            $diferenca = $dataUltimoReajuste->diff($dataAtual);
            $tempoEmAnosSemReajuste = $diferenca->y;
            print($tempoEmAnosSemReajuste);


            DB::statement("UPDATE tb_reajuste
            SET valor= $mensalidade, dtreajuste='".date("Y-m-d H:i:s")."'
            WHERE id= $reajuste->id;
            ");
        }
      
    }

    public function atualiza(Request $request)
    {
        $mensalidade = new Mensalidade();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_usr_criador = json_decode($request->post('acesso'));
        settype($dadosrequisicao[0]->id, "string");

        // $dadosrequisicao[0]->ano = $dadosrequisicao[0]->ano;


        DB::beginTransaction();

        $mensalidadeatualizado = DB::table('mensalidade')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                'ano'           => $dadosrequisicao[0]->ano,
                'valor'             => $dadosrequisicao[0]->valor,
                'id_usr_criador'         => $id_usr_criador,
                'id_ult_alterador'  => $id_usr_criador,
                'excluidomensalidade'  => '0',

            ]);

        if ($mensalidadeatualizado) {
            DB::commit();

            // DB::insert($mensalidade->insereHistorico($dadosrequisicao, $id_usr_criador, $tipo=2));

        } else {
            DB::rollBack();
        }


        $arrayRetorno = array(

            'id'                => $dadosrequisicao[0]->id,
            'ano'           => $dadosrequisicao[0]->ano,
            'valor'             => FormatacoesServiceProvider::validaValoresParaView($dadosrequisicao[0]->valor),
            'id_usr_criador'         => $id_usr_criador,
            'id_ult_alterador'  => $id_usr_criador,
            'excluidomensalidade'  => '0',
            'created_at'        => $dadosrequisicao[0]->created_at


        );
        echo json_encode($arrayRetorno);
    }


    public function marcaComoExcluido(Request $request)
    {
        $mensalidade = new Mensalidade();

        $id = json_decode($request->post('id'));
        settype($id, "string");
        $dadoscriativo = Mensalidade::find($id);


        DB::beginTransaction();

        $mensalidadeatualizado = DB::table('mensalidade')
            ->where('id', $id)
            ->update([
                'id_ult_alterador'  => $id,
                'excluidomensalidade'  => '1',
            ]);

        if ($mensalidadeatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        // DB::insert($mensalidade->insereHistorico($dadoscriativo, null, $tipo=3));

        $mensalidade = new Mensalidade();
        $stringConsulta = $mensalidade->consultaMensalidade($id);
        $dadosConsulta = DB::select($stringConsulta);
        foreach($dadosConsulta as $dados){
            $dados->valor = FormatacoesServiceProvider::validaValoresParaView($dados->valor);
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
