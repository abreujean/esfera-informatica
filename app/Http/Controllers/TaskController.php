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
     * Função para listar todos dados da tarefas e usuários vinculados pelo hash.
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

    /**
     * Função para atualizar a tarefa.
     */
    public function update(Request $request)
    {
        try{ 
            // Validar os dados da requisição
            $request->validate([
                'title' => 'required|min:3|max:255',
                'description' => 'nullable|max:500',
                'status' => 'required|in:Pendente,Concluída',
            ]);

            // Encontrar a tarefa pelo hash
            $task = Task::where('hash', $request->input('hash'))->first();

            // Atualizar os campos da tarefa
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->save();

            // Acrecentar id do usuário da sessão na pabela pivo de tasks_users
            $task->users()->syncWithoutDetaching(Auth::id());


            return response()->json(['message' => 'Tarefa atualizada com sucesso.'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json($e->getMessage(), 400);
        } 
    }

    /**
     * Função para deletar a tarefa pelo hash.
     */
    public function destroy(Request $request)
    {
        // Encontrar a tarefa pelo hash
        $task = Task::where('hash', $request->input('hash'))->first();

        if ($task) {
            //atualizar para o status excluida
            $task->status = 'Excluída';
            $task->save();
            
            // Remover todos os usuários vinculados à tarefa
            $task->users()->detach();   
            return response()->json(['message' => 'Tarefa deletada com sucesso.'], 200);
        } else {
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
    }

    /**
     * Função para Filtrar tarefas pelos status, por pessoas, pesquisar pelo título, levando em consideração  o filtro de todos os usuário ou só do usuário logado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterTasks(Request $request)
    {
        // Iniciando a query com o relacionamento com usuários
        $query = Task::with(['users' => function($q) {
            $q->select('users.id', 'users.name');
        }]);

        // Filtrar por tipo: minhas tarefas ou todas
        if ($request->input('type') === 'my_tasks') {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }
    
        // Filtrar por status (se não for "todos")
        if ($request->filled('status') && $request->input('status') !== 'todos') {
            $query->where('status', $request->input('status'));
        }
    
        // Filtrar por array de user_id(s)
        if ($request->filled('user_id') && is_array($request->input('user_id'))) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->whereIn('user_id', $request->input('user_id'));
            });
        }
    
        // Filtrar por título
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }
    
        $tasks = $query->get();
    
        return response()->json($tasks);
    }
    
}
