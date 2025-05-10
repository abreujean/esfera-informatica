<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Logic to retrieve and display users
        return view('users.index');
    }

    public function create()
    {
        // Logic to show the form for creating a new user
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new user
        // Validate and save the user data
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        // Logic to show the form for editing a user
        return view('users.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update the user data
        return redirect()->route('users.index');
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
    public function getUsers()
    {
        $users = User::all();
        return $users;
    }
}
