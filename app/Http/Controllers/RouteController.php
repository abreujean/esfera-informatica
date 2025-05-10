<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteController extends Controller
{

    protected $userController;
    
    /**
     * Injeção de dependência via construtor
     */
    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    /**
     * Função para retornar a view do dashboard
     *
     * @return view
     */
    public function dashboard()
    {

        //deco json
        $listUser = json_decode($this->userController->listAllUser()->getContent(), true);
        return view('dashboard', compact('listUser'));
    }
}
