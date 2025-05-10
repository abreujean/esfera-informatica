<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class TaskController extends Controller
{

    /**
     * Função para criar uma nova tarefa no sistema.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{ 
           //validar Título (obrigatório, mínimo de 3 caracteres, máximo de 255).  
           // Descrição (opcional, máximo de 500 caracteres). 
           // Status (pendente ou concluída). 
           
           $request->validate([
                'title' => 'required|min:3|max:255',
                'description' => 'nullable|max:500',
                'status' => 'required|in:Pendente,Concluída',
            ]);
            
            //Criar a tarefa
            $task = new task();
            $task->hash = Uuid::uuid4();
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->save();

            //Fazer vinculo entre a tarefa e o usuário
            $task->users()->sync(Auth::id());

            return response()->json(['mensagem' => 'A tarefa foi criada com sucesso'] , 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json($e->getMessage(), 400);
        } 
    }

    /**
     * Função para listar as tarefas do usuário logado e pelo status.
     */
    public function listTaskUserLoggedStatus($status)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuário não autenticado.'], 401);
        }

        // Obtém o ID do usuário autenticado
        $userId = Auth::id();

        $tasks = Task::with(['users' => function($query) {
            $query->select('users.id', 'users.name'); // Seleciona apenas campos necessários
        }])
        ->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        if ($status) {
            $tasks->where('status', $status);
        }

        return response()->json($tasks->get());
    }

    /**
     * Função para listar todas as tarefas de todos os usuário pelo status.
     */
    public function listTaskAllStatus($status)
    {

        $tasks = Task::with(['users' => function($query) {
            $query->select('users.id', 'users.name'); 
        }]);

        if ($status) {
            $tasks->where('status', $status);
        }

        return response()->json($tasks->get());
    }

    /**
     * Função para listar todos usuários vinculados a uma tarefa específica pelo hash.
     */
    public function listTaskUserHash($hash)
    {

        $task = Task::with(['users' => function($query) {
            $query->select('users.id', 'users.name'); 
        }])->where('hash', $hash)->first();

        return response()->json($task);
    }

    /**
     * Função para atualizar remover ou atualizar todos os usuário vinculados a uma tarefa específica pelo hash.
     */
    public function updateTaskUserHash(Request $request)
    {
        //se o user_id não for passado, remover todos os usuários vinculados a tarefa
        if (!$request->input('user_id')) {
            $task = Task::where('hash', $request->input('hash'))->first();
            $task->users()->detach();
            return response()->json(['mensagem' => 'Todos os usuários foram removidos com sucesso'] , 200);
        } else{
            // Atualiza os usuários vinculados à tarefa
            $task = Task::where('hash', $request->input('hash'))->first();
            $task->users()->sync($request->input('user_id'));
            return response()->json(['mensagem' => 'Os usuários foram atualizados com sucesso'] , 200);
        }

    }

    /**
     * Função para marcar a tarefa como concluida.
     */
    public function updateTaskStatusCompleted(Request $request){
        $task = Task::where('hash', $request->input('hash'))->first();
        $task->status = 'Concluída';
        $task->save();
        return response()->json(['mensagem' => 'A tarefa foi marcada como concluída com sucesso'] , 200);

    }

    
}
