$(document).ready(function() {
    // Monitora mudança nos radios
    $('input[name="filter-tasks-dashboard"]').change(function() {
        const filterType = $(this).val();
        loadTasks(filterType);
    });

    // Carrega tarefas inicialmente (padrão: 'my_tasks')
    loadTasks('my_tasks');
});

$( "#task-create" ).submit(function( event ) {
    
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        title: $("#title").val(),
        description: $("#description").val(),
        status: $("#status").val(),
        user_id: $("#user_id").val(),
    }


    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: createTaskUrl,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
        console.log(data);
          Toast.fire({ icon: 'success', title: data.mensagem });
          location.href='/dashboard';
      },
      error: function(jqXHR, status, error) { 
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });


const loadTasks = (type) => {
    if (type === 'my_tasks') {
        console.log('Carregando tarefas do usuário logado');
        loadTasksUserLoggedStatus('Pendente');
        loadTasksUserLoggedStatus('Concluida');
    }
}

// Função para carregar tarefas com base no status do usuário logado
const loadTasksUserLoggedStatus = (status) => {

    $.get({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: listTaskUserLoggedStatus.replace(':status', status),
        dataType : 'json',
        type: 'GET',
        //data: 'status='+status,
        success:function(tasks) {
            
            if(status == 'Pendente'){
                creatCardTaskPending(tasks);
            }
            if(status == 'Concluida'){
                creatCardTaskCompleted(tasks);
            }

        },
        error: function(jqXHR, status, error) { 
           Toast.fire({ icon: 'error', title: jqXHR.responseJSON })
        }       
    });

}

const creatCardTaskPending = (tasks) => {

    // Limpa o container
    $('#pendente').empty();

    if (tasks.length === 0) {
        $('#pendente').html('<div class="text-center text-muted py-4">Nenhuma tarefa pendente encontrada</div>');
        return;
    }

    // Itera sobre cada tarefa
    tasks.forEach(task => {
        // Cria o HTML para os usuários vinculados
        let usersHtml = '';
        task.users.forEach(user => {
            usersHtml += `
                <span class="text-muted mr-2">${user.name}</span>
            `;
        });
        
        // Cria o card completo
        const taskHtml = `
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">${task.title}</h5>
                    <div class="card-tools">
                        <a class="btn btn-tool text-primary" href="#">
                            <i class="fas fa-square"></i>
                        </a>
                        <a class="btn btn-tool" href="#">
                            <i class="fas fa-thumbs-up"></i>
                        </a>
                        <a href="#" class="btn btn-tool">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="#" class="btn btn-tool">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p>${task.description || 'Sem descrição fornecida'}</p>
                </div>
                <div class="card-footer">
                    <div class="row mb-3 mt-3">
                        <a href="#" class="btn btn-tool">
                            <i class="fas fa-arrow-circle-right"></i> Gerenciar Usuários
                        </a>
                    </div>
                    <div class="d-wrap">
                        ${usersHtml}
                    </div>
                </div>
            </div>
        `;
        
        // Adiciona o card ao container
        $('#pendente').append(taskHtml);

    });
}

const creatCardTaskCompleted = (tasks) => {

    // Limpa o container
    $('#concluida').empty();

            
    if (tasks.length === 0) {
        $('#concluida').html('<div class="text-center text-muted py-4">Nenhuma tarefa concluída encontrada</div>');
        return;
    }

    tasks.forEach(task => {
        // Cria o HTML para os usuários vinculados
        let usersHtml = task.users.map(user => 
            `<span class="text-muted mr-2">${user.name}</span>`
        ).join('');

        // Formata a data
        const createdAt = new Date(task.created_at).toLocaleDateString('pt-BR');
        
        // Cria o card completo
        const taskHtml = `
            <div class="card card-success card-outline" data-task-id="${task.id}">
                <div class="card-header">
                    <h5 class="card-title">${task.title}</h5>
                    <div class="card-tools">
                        <a class="btn-status btn btn-tool text-success" data-status="Pendente">
                            <i class="fas fa-square"></i>
                        </a>
                        <a href="#" class="btn-edit btn btn-tool" data-task-id="${task.id}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="#" class="btn-delete btn btn-tool" data-task-id="${task.id}">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p>${task.description || '<span class="text-muted font-italic">Sem descrição</span>'}</p>
                </div>
                <div class="card-footer">
                    <div class="row mb-3 mt-3">
                        <a href="#" class="btn-manage-users btn btn-tool" data-task-id="${task.id}">
                            <i class="fas fa-arrow-circle-right"></i> Gerenciar Usuários
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="users-list">
                            ${usersHtml}
                        </div>
                        <small class="text-muted">Criado em: ${createdAt}</small>
                    </div>
                </div>
            </div>
        `;
        
        $('#concluida').append(taskHtml);
    });
}

const loadTasksAllUsers = () => {
}