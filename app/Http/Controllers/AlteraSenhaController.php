<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;

class AlteraSenhaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('users/alterasenha');
    } 
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'nova_senha' => ['required'],
            'confirme_a_senha' => ['same:nova_senha'],
        ],
                [
                    'current_password.required' => 'O campo senha atual é obrigatório',
                    'nova_senha.required' => 'O campo senha é obrigatório',
                    'confirme_a_senha.same' => 'O campo senha e repita a senha devem ser iguais',
                ]);
   
            try {
                User::find(auth()->user()->id)->update(['password'=> Hash::make($request->nova_senha)]);
                
                return redirect()->route('alterasenha')
                    ->with('success', 'Senha alterada com sucesso');

            } catch (\Throwable $th) {
                return redirect()->route('alterasenha')
                    ->with('error', 'Ocorreu um erro ao alterar a senha. Por favor, tente noavamente');
            }
            
   
        dd('Password change successfully.');
    }
}