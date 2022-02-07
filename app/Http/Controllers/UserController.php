<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Mensalidade;

use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        return view('users.index');


    }
    public function apiusuarios(Request $request)
    {

        // $listaUsuarios = DB::select("SELECT * from users");
        $listaUsuarios = User::all();
        // $listaUsuarios = DB::select("SELECT * from users where desativado = '0'");

        foreach ($listaUsuarios as $usuarios) {
            if (!empty($usuarios->getRoleNames())) {

                foreach ($usuarios->getRoleNames() as $permissoes) {
                    $usuarios->permissao = $permissoes;
                }
            }
        }
        return $listaUsuarios;
        // return view('users.index', compact('listaUsuarios'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',

            'rzsocial' => 'required',
            'cnpj' => 'required',
            'endereco' => 'required',
            'telefone' => 'required',
            'wpp' => 'required',
            'estado' => 'required',
            'municipio' => 'required',
            'resmunicipio' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $anoAtual = date('Y');
        $mensalidade = DB::select("SELECT valor from mensalidade where excluidomensalidade = 0 and ano = ". $anoAtual );

        $id = $user->id;
        $mensalidade = $mensalidade[0]->valor;
        $dataAtual = date('Y-m-d H:i:s');
        $usuarioLogado = Auth::id();

        DB::statement("INSERT INTO tb_reajuste
        (id, idusuario, valor, dtreajuste, id_usr_criador, id_ult_alterador, excluidoreajuste, created_at, updated_at)
        VALUES(NULL, $id, $mensalidade, '$dataAtual', $usuarioLogado, $usuarioLogado, '0', '$dataAtual', NULL)");



        return redirect()->route('users.index')
            ->with('success', 'Usuário cadastrado com sucesso');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::find($id);
        // return view('users.show', compact('user'));


        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.show', compact('user', 'roles', 'userRole'));
    }

    public function cadastro(Request $request)
    {
        if (Auth::id()) {
            $id = json_decode($request->post('id'));
            $user = User::find($id);
            if (Auth::id() == $id) {
                return view('cadastro.index', compact('user'));
            } else {
                return view('home');
            }
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);
        // $user = User::find($id);
        $atualizaUsuario = new User();

        if (($request->password == '') && ($request->get('confirm-password') == '')) {
            unset($request->password);
        } else {
            $atualizaUsuario->password              = $request->password;

            $this->validate(
                $request,
                [
                    'password' => 'required|same:confirm-password',
                ],
                [
                    'password.required' => 'O campo senha é obrigatório',
                    'password.same' => 'O campo senha e repita a senha devem ser iguais',
                ]
            );
        }


        $atualizaUsuario->name                  = $request->name;
        $atualizaUsuario->roles                 = $request->roles;
        $atualizaUsuario->rzsocial              = $request->rzsocial;
        $atualizaUsuario->cnpj                  = $request->cnpj;
        $atualizaUsuario->endereco              = $request->endereco;
        $atualizaUsuario->telefone              = $request->telefone;
        $atualizaUsuario->wpp                   = $request->wpp;
        $atualizaUsuario->estado                = $request->estado;
        $atualizaUsuario->municipio             = $request->municipio;
        $atualizaUsuario->resmunicipio          = $request->resmunicipio;

        if ($user->email != $request->email) {
            $this->validate($request, [
                'email' => 'required|email|unique:users,email',
            ]);
            $atualizaUsuario->email                 = $request->email;
        }


        $this->validate($request, [
            'name' => 'required',
            // 'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'rzsocial' => 'required',
            'cnpj' => 'required',
            'endereco' => 'required',
            'telefone' => 'required',
            'wpp' => 'required',
            'estado' => 'required',
            'municipio' => 'required',
            'resmunicipio' => 'required',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }


        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();


        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')
            ->with('success', 'Cadastro de usuário atualizado com sucesso');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'Cadastro de usuário excluído com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desativaUsuario(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->desativado = 1;
        try {
            $usuario->update();
        } catch (\Throwable $th) {
            return redirect()->route('users.index')
                ->with('error', 'Exclusão de usuário falhou. Tente novamente');
        }

        return redirect()->route('users.index')
            ->with('success', 'Cadastro de usuário desativado com sucesso');
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }


    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }
}
