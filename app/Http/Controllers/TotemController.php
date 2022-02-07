<?php

namespace App\Http\Controllers;

use App\Totem;
use App\ClienteLicenciado;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;


use App\User;
use Spatie\Permission\Models\Role;
use App\Providers\FormatacoesServiceProvider;

class TotemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:totem-list|totem-create|totem-edit|totem-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:totem-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:totem-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:totem-delete', ['only' => ['destroy']]);

        // $this->middleware('auth');
    }

    public function index()
    {

        $id_usr_criador = Auth::id();

        $clientelicenciado = new ClienteLicenciado();
            
        return view('totem.index', compact('id_usr_criador'));
    }


    public function inicio(Request $request)
    {
        $totem = new Totem();
        $id_usr_criador = json_decode($request->post('acesso'));

        $permiteListagemCompleta = $this->verificaPermissao($id_usr_criador);
        if ($permiteListagemCompleta == 1) {
            $stringConsulta = $totem->consultaTotem($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);

            return $dadosConsulta;
        } elseif ($permiteListagemCompleta == 0) {
            $stringConsulta = $totem->consultaTotem($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);
            
            return $dadosConsulta;        
        }
    }

    
    function pegaUsuarios(Request $request)
    {
        $id_usr_criador = json_decode($request->post('acesso'));

        $permiteListagemCompleta = $this->verificaPermissao($id_usr_criador);
        if ($permiteListagemCompleta == 1) {
            $listaUsuarios = User::all();
            return $listaUsuarios;
        }
        
    }

    public function verificaPermissao($id_usr_criador)
    {
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
        return $permiteListagemCompleta;
    }

    public function inicioHistorico(Request $request)
    {
        $totem = new Totem();

        $id_usr_criador = json_decode($request->post('acesso'));

        $permiteListagemCompleta = $this->verificaPermissao($id_usr_criador);

        if ($permiteListagemCompleta == 1) {
            $stringConsulta = $totem->consultaHistoricoTotem(null);
        } elseif ($permiteListagemCompleta == 0) {
            $stringConsulta = $totem->consultaHistoricoTotem($id_usr_criador);
        }
        $dadosConsulta = DB::select($stringConsulta);
        foreach ($dadosConsulta as $dados) {
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

        $totem = new Totem();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_usr_criador = json_decode($request->post('acesso'));
        settype($id_usr_criador, "string");
        
        $verificatotem = DB::table('totem')->where('n_serie', $dadosrequisicao[0]->n_serie)->first();

        if ($verificatotem) {
            return view('totem.index', compact('id_usr_criador'));
        }
        if (!$verificatotem) {

            DB::beginTransaction();


            if($dadosrequisicao[0]->licenciado){
                $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->licenciado->id);

                $criatotem = Totem::create([
                    'idlicenciado'       => $dadosrequisicao[0]->licenciado->id,
                    'n_serie'            => $dadosrequisicao[0]->n_serie,
                    'dtassociado'        => date("Y-m-d H:i:s"),
                    'id_usr_criador'     => $id_usr_criador,
                    'id_ult_alterador'   => $id_usr_criador,
                    'excluidototem'      => 0
    
                ]);
            }
            elseif ($dadosrequisicao[0]->cliente) {
                $cliente = $dadosrequisicao[0]->cliente;
                $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->cliente);
                $criatotem = Totem::create([
                    'idcliente'          => $dadosrequisicao[0]->cliente,
                    'idlicenciado'       => $id_usr_criador,
                    'n_serie'            => $dadosrequisicao[0]->n_serie,
                    'dtassociado'        => date("Y-m-d H:i:s"),
                    'id_usr_criador'     => $id_usr_criador,
                    'id_ult_alterador'   => $id_usr_criador,
                    'excluidototem'      => 0
    
                ]);
            }

            if ($criatotem) {
                DB::commit();

                $dadosrequisicao[0]->id = $criatotem->id;
                $dadosrequisicao[0]->created_at = $criatotem->created_at;
                $dadosrequisicao[0]->dtassociado = $criatotem->dtassociado;
                
                $tipo = 1;
                DB::insert($totem->insereHistorico($dadosrequisicao, $id_usr_criador, $dadoscliente["c_nome"], $tipo));

            } else {
                DB::rollBack();
            }


            $arrayRetorno = array(
                'id'                => $dadosrequisicao[0]->id,
                'cliente'           => $dadoscliente->c_nome,
                // 'licenciado'        => $dadoscliente->name,
                'licenciado'        => $dadosrequisicao[0]->licenciado->name,
                'n_serie'           => $dadosrequisicao[0]->n_serie,
                'dtassociado'       => $dadosrequisicao[0]->dtassociado,
                'id_usr_criador'    => $id_usr_criador,
                'id_ult_alterador'  => $id_usr_criador,
                'created_at'        => $dadosrequisicao[0]->created_at
            );

            echo json_encode($arrayRetorno);
        }
    }


    public function atualiza(Request $request)
    {
        $totem = new Totem();
        $dadosrequisicao = json_decode($request->post('models'));
        $id_ult_alterador = json_decode($request->post('acesso'));
        settype($dadosrequisicao[0]->id, "string");
        
        
        
        DB::beginTransaction();
        
        $permiteListagemCompleta = $this->verificaPermissao($id_ult_alterador);
        if ($permiteListagemCompleta == 1) {
            
            
            $totematualizado = DB::table('totem')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                
                'idlicenciado'       => $dadosrequisicao[0]->licenciado,
                'n_serie'            => $dadosrequisicao[0]->n_serie,
                'dtassociado'        => date("Y-m-d H:i:s"),
                'id_ult_alterador'   => $id_ult_alterador    
            ]);
            
            
        } elseif ($permiteListagemCompleta == 0) {
            // var_dump( $dadosrequisicao[0]);
            // exit;
            if(isset($dadosrequisicao[0]->cliente->id)):  
                $idcliente = $dadosrequisicao[0]->cliente->id;
                $nomecliente = $dadosrequisicao[0]->cliente->c_nome;
            else: 
                $idcliente = $dadosrequisicao[0]->cliente; 
                $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->idcliente);
                $nomecliente = $dadoscliente->c_nome;
            endif;
            
            $totematualizado = DB::table('totem')
            ->where("id", $dadosrequisicao[0]->id)
            ->update([
                
                    'id'                => $dadosrequisicao[0]->id,
                    'idcliente'         => $idcliente,
                    // 'idlicenciado'      => $id_ult_alterador,
                    // 'n_serie'           => $dadosrequisicao[0]->n_serie,
                    'dtassociado'       => $dadosrequisicao[0]->dtassociado,
                    'id_ult_alterador'  => $id_ult_alterador,
                    'updated_at'        => date("Y-m-d H:i:s")
                    // 'excluidototem'     => '0'
                    
                ]);
            }
            
            
            
            if ($totematualizado) {
                DB::commit();
                $tipo = 2;
                $dadoscliente = ClienteLicenciado::find($dadosrequisicao[0]->idcliente);
                DB::insert($totem->insereHistorico($dadosrequisicao, $id_ult_alterador, $dadoscliente["c_nome"], $tipo));
    
                if($permiteListagemCompleta == 1 && $dadosrequisicao[0]->licenciado){
                        $licenciado = User::find($dadosrequisicao[0]->licenciado);
                        $cliente = '';
                        $arrayRetorno = array(
                            'id'                => $dadosrequisicao[0]->id,
                            // 'idcliente'         => $dadosrequisicao[0]->cliente,
                            'idlicenciado'       => $dadosrequisicao[0]->idlicenciado,
                            'licenciado'         => $licenciado->name,
                            // 'idlicenciado'      => $dadosrequisicao[0]->idtotem,
                            'n_serie'           => $dadosrequisicao[0]->n_serie,
                            'dtassociado'       => $dadosrequisicao[0]->dtassociado,
                            // 'id_usr_criador'    => $id_usr_criador,
                            'id_ult_alterador'  => $id_ult_alterador,
                            'created_at'        => $dadosrequisicao[0]->created_at
                        );
                    }
                    elseif ($permiteListagemCompleta == 0 && $dadosrequisicao[0]->cliente) {
                        
                        // $cliente = $dadosrequisicao[0]->cliente;
                        $arrayRetorno = array(
                            'id'                => $dadosrequisicao[0]->id,
                            'idcliente'         => $idcliente,
                            // 'cliente'           => $dadosrequisicao[0]->cliente->c_nome,
                            'cliente'           => $nomecliente,
                            // 'idlicenciado'      => $dadosrequisicao[0]->idtotem,
                            'n_serie'           => $dadosrequisicao[0]->n_serie,
                            'dtassociado'       => $dadosrequisicao[0]->dtassociado,
                            // 'id_usr_criador'    => $id_usr_criador,
                            'id_ult_alterador'  => $id_ult_alterador,
                            'created_at'        => $dadosrequisicao[0]->created_at
                        );
                    }
                    
                } else {
                    DB::rollBack();
                }
                
                echo json_encode($arrayRetorno);
            }
            
            
    public function marcaComoExcluido(Request $request)
    {
        $totem = new Totem();

        $id = json_decode($request->post('id'));
        settype($id, "string");
        $dadostotem = Totem::find($id);
        $id_usr_criador = json_decode($request->post('acesso'));



        DB::beginTransaction();
        $dadoscliente = ClienteLicenciado::find($dadostotem->cliente);


        $totematualizado = DB::table('totem')
            ->where('id', $id)
            ->update([
                'updated_at'        => date("Y-m-d H:i:s"),
                'excluidototem'     => '1'
            ]);

        if ($totematualizado) {
            DB::commit();
            $tipo = 3;
    
            DB::insert($totem->insereHistorico($dadostotem, null, $dadoscliente["c_nome"], $tipo));
        } else {
            DB::rollBack();
        }

        $permiteListagemCompleta = $this->verificaPermissao($id_usr_criador);

        if ($permiteListagemCompleta == 1) {
            $stringConsulta = $totem->consultaTotem($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);

            // return $dadosConsulta;
            echo json_encode($dadosConsulta);

        } elseif ($permiteListagemCompleta == 0) {
            $stringConsulta = $totem->consultaTotem($permiteListagemCompleta, $id_usr_criador);
            $dadosConsulta = DB::select($stringConsulta);

            // return $dadosConsulta;        
            echo json_encode($dadosConsulta);
        }


    }

    public function buscaLicenciado($id)
    {
        $nomelicenciado = DB::select("SELECT name from users where id = $id");
        return $nomelicenciado;
    }
}
