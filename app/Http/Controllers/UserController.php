<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function index()
    {
        // Logic to retrieve and display users
        return view('users.list');
    }

    public function create()
    {
        // Logic to show the form for creating a new user
        return view('users.create');
    }

    public function store(Request $request)
    {
        try{ 
        //Nome (obrigatório, mínimo de 3 caracteres, máximo de 200) 
        //E-mail (obrigatório, único por usuário, e-mail válido, máximo de 200 caracteres) 
        //Senha (obrigatório, mínimo de 8 caracteres, com letras, números e símbolos) 
        //Status (obrigatório, booleano)

        $request->validate([
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|unique:users,email|max:200',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|boolean',
        ]);
        
        $user = new User();   
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->status = $request->input('status');
        $user->profile_id = ProfileController::$USUARIO;
        $user->hash = Uuid::uuid4();
        $user->save();

        return response()->json(['mensagem' => 'Usuário Criado Com Sucesso'] , 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json($e->getMessage(), 400);
        } 
    }

    public function edit($hash)
    {
        $getUser = User::where('hash', $hash)->get();
        return view('users.edit', compact('getUser'));
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|min:3|max:200',
                'email' => 'email|required|unique:users,id',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'required|boolean',
                'hash' => 'required|string|exists:users,hash'
            ], [
                'email.unique' => 'Este e-mail já está em uso por outro usuário',
                'hash.exists' => 'Usuário não encontrado'
            ]);
    
            $user = User::where('hash', $request->input('hash'))->firstOrFail();
    
               // Atualiza os dados do usuário
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->status = $request->input('status');

            
            if ( ($request->input('password') != $user->password) && (!Hash::check($request->input('password'), $user->password)) ) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            return response()->json(['mensagem' => 'Usuário atualizado com sucesso'], 200);

        } catch (\Exception $e) {

            return response()->json([ $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // Logic to delete a user
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        // Logic to display a single user
        return view('users.show', compact('id'));
    }

    /**
     * Função para retornar todos os usuários
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAllUser()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Função para recuperar o usuário pelo hash
     */
    public function getUserHash($hash){

        $users = User::where('hash', $hash)->first();
        return response()->json($users);

    }

    
}
