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
                'user_id' => 'required',
            ], [
                // Mensagens personalizadas aqui
                'user_id.required' => 'Você precisa selecionar pelo menos um usuário.',
            ]);
            
            // validar se o usuario da sessão está no cadastro
            if (!in_array(Auth::id(), $request->input('user_id'))) {
                throw ValidationException::withMessages([
                    'user_id' => ['Você precisa estar vinculado à tarefa.'],
                ]);
            }

            //Criar a tarefa
            $task = new task();
            $task->hash = Uuid::uuid4();
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->save();

            //Fazer vinculo entre a tarefa e o usuário
            $task->users()->sync($request->input('user_id'));
  

            return response()->json(['mensagem' => 'A tarefa foi criada com sucesso'] , 200);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json($e->getMessage(), 400);
        } 
    }
}
