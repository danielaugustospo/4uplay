<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteLicenciado;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;


use App\User;
use Spatie\Permission\Models\Role;
use App\Providers\FormatacoesServiceProvider;



class ClienteLicenciadoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:clienteslicenciado-list|clienteslicenciado-create|clienteslicenciado-edit|clienteslicenciado-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:clienteslicenciado-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:clienteslicenciado-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:clienteslicenciado-delete', ['only' => ['destroy']]);

        // $this->middleware('auth');
    }

    public function index()
    {

        $id_usr_criador = Auth::id();

        return view('clientelicenciado.index', compact('id_usr_criador'));
    }


    public function inicio(Request $request)
    {
        $clientelicenciado = new ClienteLicenciado();

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
            
            $stringConsulta = $clientelicenciado->consultaClienteLicenciado($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);

            return $dadosConsulta;
        } elseif ($permiteListagemCompleta == 0) {
            $stringConsulta = $clientelicenciado->consultaClienteLicenciado($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);

            return $dadosConsulta;
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

        $clientelicenciado = new ClienteLicenciado();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_usr_criador = json_decode($request->post('acesso'));
        settype($id_usr_criador, "string");

        // $dadosrequisicao[0]->ano = $dadosrequisicao[0]->ano;

        $verificaclientelicenciado = DB::table('users')->where('id',$id_usr_criador);
        if(!$verificaclientelicenciado) {
            return view('clientelicenciado.index', compact('id_usr_criador'));        
        }         
        if($verificaclientelicenciado) {
 
            DB::beginTransaction();

            $criaclientelicenciado = ClienteLicenciado::create([
                'c_nome'            => $dadosrequisicao[0]->c_nome,
                'c_email'           => $dadosrequisicao[0]->c_email,
                'c_endereco'        => $dadosrequisicao[0]->c_endereco,
                'c_telefone'        => $dadosrequisicao[0]->c_telefone,
                'c_estado'          => $dadosrequisicao[0]->c_estado,
                'c_municipio'       => $dadosrequisicao[0]->c_municipio,
                'c_idlicenciado'    => $id_usr_criador,
                'c_excluido'        => '0'
            ]);


            if ($criaclientelicenciado) {
                DB::commit();

                $dadosrequisicao[0]->id = $criaclientelicenciado->id;
                $dadosrequisicao[0]->created_at = $criaclientelicenciado->created_at;
                $dadosrequisicao[0]->updated_at = $criaclientelicenciado->updated_at;
                $dadosrequisicao[0]->c_excluido = $criaclientelicenciado->c_excluido;
                // DB::insert($clienteslicenciado->insereHistorico($dadosrequisicao, $id_usr_criador, $tipo=1));

            } else {
                DB::rollBack();
            }


            $arrayRetorno = array(
                'id'                => $dadosrequisicao[0]->id,
                'c_nome'            => $dadosrequisicao[0]->c_nome,
                'c_email'           => $dadosrequisicao[0]->c_email,
                'c_endereco'        => $dadosrequisicao[0]->c_endereco,
                'c_telefone'        => $dadosrequisicao[0]->c_telefone,
                'c_estado'          => $dadosrequisicao[0]->c_estado,
                'c_municipio'       => $dadosrequisicao[0]->c_municipio,
                'c_idlicenciado'    => $id_usr_criador,
                'c_excluido'        => $dadosrequisicao[0]->c_excluido,
                'created_at'        => $dadosrequisicao[0]->created_at
            );

            echo json_encode($arrayRetorno);
        }
    }

    public function exibe()
    {
        # code...
    }

    public function atualiza(Request $request)
    {
        $clientelicenciado = new ClienteLicenciado();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_usr_criador = json_decode($request->post('acesso'));
        settype($dadosrequisicao[0]->id, "string");

        // $dadosrequisicao[0]->ano = $dadosrequisicao[0]->ano;


        DB::beginTransaction();

        $clientelicenciadoatualizado = DB::table('clienteslicenciado')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                'c_nome'            => $dadosrequisicao[0]->c_nome,
                'c_email'           => $dadosrequisicao[0]->c_email,
                'c_endereco'        => $dadosrequisicao[0]->c_endereco,
                'c_telefone'        => $dadosrequisicao[0]->c_telefone,
                'c_estado'          => $dadosrequisicao[0]->c_estado,
                'c_municipio'       => $dadosrequisicao[0]->c_municipio,
                'c_idlicenciado'    => $id_usr_criador,
                'c_excluido'        => '0',
                'updated_at'        => date("Y-m-d H:i:s")

            ]);

        if ($clientelicenciadoatualizado) {
            DB::commit();

            // DB::insert($clienteslicenciado->insereHistorico($dadosrequisicao, $id_usr_criador, $tipo=2));

        } else {
            DB::rollBack();
        }


        $arrayRetorno = array(

            'id'                => $dadosrequisicao[0]->id,
            'c_nome'            => $dadosrequisicao[0]->c_nome,
            'c_email'           => $dadosrequisicao[0]->c_email,
            'c_endereco'        => $dadosrequisicao[0]->c_endereco,
            'c_telefone'        => $dadosrequisicao[0]->c_telefone,
            'c_estado'          => $dadosrequisicao[0]->c_estado,
            'c_municipio'       => $dadosrequisicao[0]->c_municipio,
            'c_idlicenciado'    => $id_usr_criador,
            'created_at'        => $dadosrequisicao[0]->created_at


        );
        echo json_encode($arrayRetorno);
    }


    public function marcaComoExcluido(Request $request)
    {
        $clientelicenciado = new ClienteLicenciado();

        $id_usr_criador = json_decode($request->post('acesso'));
        settype($id_usr_criador, "string");

        $id = json_decode($request->post('id'));
        settype($id, "string");


        $dadoscriativo = ClienteLicenciado::find($id);

        $verificaclientelicenciado = DB::table('clienteslicenciado')->where('c_licenciado',$id_usr_criador, 'id', $id);
        if(!$verificaclientelicenciado) {
            return view('clientelicenciado.index', compact('id_usr_criador'));        
        }         
        if($verificaclientelicenciado) {

        DB::beginTransaction();

        $clientelicenciadoatualizado = DB::table('clienteslicenciado')
            ->where('id', $id)
            ->update([
                'c_idlicenciado'  => $id,
                'c_excluido'  => '1',
            ]);

        if ($clientelicenciadoatualizado) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        // DB::insert($clienteslicenciado->insereHistorico($dadoscriativo, null, $tipo=3));

        $clientelicenciado = new ClienteLicenciado();
        $stringConsulta = $clientelicenciado->consultaClienteLicenciado($permiteListagemCompleta, $id_usr_criador);
        $dadosConsulta = DB::select($stringConsulta);

        echo json_encode($dadosConsulta);
    }
    }

}
