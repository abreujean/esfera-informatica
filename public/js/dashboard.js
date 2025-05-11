let users = [];

//Constantes
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

const listAllUsers = async () => {
    try {
        const response = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: listUsers,
            method: 'GET',
            dataType: 'json'
        });
        return response; 
    } catch (error) {
        Toast.fire({ icon: 'error', title: error.responseJSON?.message || 'Erro ao carregar usuários' });
        return []; 
    }
};

//Iniciliza Funções
$(document).ready(async () => {

    $('input[name="filter-tasks-dashboard"]').change(function() {
        const filterType = $(this).val();
        loadTasks( filterType);
    });

    // Carrega tarefas inicialmente (padrão: 'my_tasks')
    loadTasks('my_tasks');
    users = await listAllUsers();
    // Carrega o Select2 do filtro de tarefas
    loadUsers();
});

//Carrga as tarefas da tela inicial
const loadTasks = (type) => {
    if (type === 'my_tasks') {
        // Primeiro, desmarca
        $('input[name="filter-tasks-dashboard"]').prop('checked', false).parent().removeClass('active');
        // Agora, marca "
        $('input[name="filter-tasks-dashboard"][value="my_tasks"]').prop('checked', true).parent().addClass('active');

        loadTasksUserLoggedStatus('Pendente');
        loadTasksUserLoggedStatus('Concluida');
    }

    if (type === 'all_tasks') {
       // Primeiro, desmarca
        $('input[name="filter-tasks-dashboard"]').prop('checked', false).parent().removeClass('active');
        // Agora, marca "
        $('input[name="filter-tasks-dashboard"][value="all_tasks"]').prop('checked', true).parent().addClass('active');

        //carrega as tarefas de todos os usuários
        loadTasksAllUsers('Pendente');
        loadTasksAllUsers('Concluida');
    }
}

//Cadastra nova tarefa
$( "#task-create" ).submit(function( event ) {
    
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        title: $("#title").val(),
        description: $("#description").val(),
        status: $("#status").val(),
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: createTaskUrl,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
        // Fecha o modal
        $('#modal-lg-create-task').modal('hide');
        // Limpa o formulário 
        $('#task-create')[0].reset();  
          Toast.fire({ icon: 'success', title: data.mensagem });
          loadTasks('my_tasks');
      },
      error: function(jqXHR, status, error) { 
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });

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

// Função para criar cards de tarefas pendentes
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

        if (usersHtml.length === 0) {
            usersHtml = '';
            usersHtml += `
                <span class="text-muted mr-2">Nenhum Usuário Vinculado</span>
            `;
        }

        // Formata a data
        const createdAt = new Date(task.created_at).toLocaleDateString('pt-BR');
        
        // Cria o card completo
        const taskHtml = `
            <div class="card card-primary card-outline">
                <div class="card-header mb-3">
                    <div class="card-tools mb-3">
                        <a class="btn btn-tool text-primary" href="#">
                            <i class="fas fa-square"></i>
                        </a>
                        <a class="btn btn-tool btn-task-completed" href="#" data-task-hash="${task.hash}">
                            <i class="fas fa-thumbs-up"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-task-edit" data-task-hash="${task.hash}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-task-delete" data-task-hash="${task.hash}">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                    <h5 class="card-title mb-2">${task.title}</h5>
                </div>
                <div class="card-body">
                    <p>${task.description || 'Sem descrição fornecida'}</p>
                </div>
                <div class="card-footer">
                    <div class="row mb-3 mt-3">
                        <a href="#" class="btn-manage-users btn btn-tool btn-task-user-manage" data-task-hash="${task.hash}">
                            <i class="fas fa-arrow-circle-right"></i> Gerenciar Usuários
                        </a>
                    </div>
                    
                    <div class="d-wrap mb-3">
                        ${usersHtml}
                    </div>
            
                    <div class="d-flex justify-content-end align-items-center">
                        <small class="text-muted">Criado em: ${createdAt}</small>
                    </div>
                </div>
            </div>
        `;
        
        // Adiciona o card ao container
        $('#pendente').append(taskHtml);

    });
}

// Função para criar cards de tarefas concluídas
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
                       <a href="#" class="btn btn-tool btn-task-edit" data-task-hash="${task.hash}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="#" class="btn-delete btn btn-tool btn-task-delete" data-task-hash="${task.hash}">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p>${task.description || '<span class="text-muted font-italic">Sem descrição</span>'}</p>
                </div>
                <div class="card-footer">
                    <div class="row mb-3 mt-3">
                        <a href="#" class="btn-manage-users btn btn-tool btn-task-user-manage" data-task-hash="${task.hash}">
                            <i class="fas fa-arrow-circle-right"></i> Gerenciar Usuários
                        </a>
                    </div>
                    
                    <div class="d-wrap mb-3">
                        ${usersHtml}
                    </div>
            
                    <div class="d-flex justify-content-end align-items-center">
                        <small class="text-muted">Criado em: ${createdAt}</small>
                    </div>
                </div>
            </div>
        `;
        
        $('#concluida').append(taskHtml);
    });
}

// Função para carregar tarefas de todos os usuários com base no status
const loadTasksAllUsers = (status) => {
    $.get({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: listTaskAllStatus.replace(':status', status),
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

// Evento de clique no botão "Gerenciar Usuários" e carrega informações no modal
$(document).on('click', '.btn-task-user-manage', function(e) {

    e.preventDefault();
    const taskHash = $(this).data('task-hash');
    $('#task_hash').val(taskHash);
    
    // Limpa o formulário 
    $('#task-user-manage')[0].reset();   
    // Exibe o modal imediatamente
    $('#modal-lg-task-user-manage').modal('show');


    $.get({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: listTaskUserHash.replace(':hash', taskHash),
        dataType: 'json',
        type: 'GET',
        success: function(response) {
       
            // Carega  informações no modal
            $('.title-user-manage').text(response.title);
            $('.body-user-manage').text(response.description);

            // Limpa o select
            $('#user_manager_id').empty();

            // Percorre todos os usuários
                users.forEach(function(user) {
                    // Verifica se o usuário está na lista dos vinculados
                    let include = response.users.some(function(r) {
                        return r.id === user.id;
                    });
            
                    $('#user_manager_id').append(
                        `<option value="${user.id}" ${include ? 'selected' : ''}>${user.name}</option>`
                    );
        
                });
            
            
            // Inicializa o Select2
            if ($.fn.select2) {
                $('#user_manager_id').select2({
                    placeholder: "Selecione os usuários",
                    allowClear: true
                });
            }
         
        },
        error: function(jqXHR, status, error) { 
           Toast.fire({ icon: 'error', title: jqXHR.responseJSON })
        }       
    });
    

});

//Função para atualizar os usuários vinculados a uma tarefa via post
$( "#task-user-manage" ).submit(function( event ) {
    
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        hash: $('#task_hash').val(),
        user_id: $('#user_manager_id').val(),
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: updateTaskUserHash,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
         // Fecha o modal
         $('#modal-lg-task-user-manage').modal('hide');
        // Limpa o formulário 
        $('#task-user-manage')[0].reset();   
        // mensagem de aviso
        Toast.fire({ icon: 'success', title: data.mensagem });
        // Atualiza a lista de tarefas
        loadTasks('all_tasks');

      },
      error: function(jqXHR, status, error) { 
        // Fecha o modal
        $('#modal-lg-task-user-manage').modal('hide');
        // Limpa o formulário 
        $('#task-user-manage')[0].reset();   
        // mensagem de aviso
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });

 // Evento de clique no botão marcar tarefa como concluída
$(document).on('click', '.btn-task-completed', function(e) {

    e.preventDefault();
    
    const data = {
        hash: $(this).data('task-hash'),
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: updateTaskUpdateStatus,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
        $('#task-create')[0].reset();  
          Toast.fire({ icon: 'success', title: data.mensagem });
          loadTasks('all_tasks');
      },
      error: function(jqXHR, status, error) { 
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });
    

});

// Evento de clique no botão e carregar os dados da tarefa no modal
$(document).on('click', '.btn-task-edit', function(e) {

    //Cancela o comportamento padão de submit do formulário
    e.preventDefault();

    // Obtém o hash da tarefa a partir do atributo data
    const taskHash = $(this).data('task-hash');
    
    // Define o valor do campo oculto no modal
    $('#task_hash_update').val(taskHash);

    // Limpa o formulário 
    $('#task-user-manage')[0].reset();   
    // Exibe o modal imediatamente
    $('#modal-lg-edit-task').modal('show');

    $.get({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: listTaskUserHash.replace(':hash', taskHash),
        dataType: 'json',
        type: 'GET',
        success: function(response) {

            // Carega  informações no modal
            $('#title-update').val(response.title);
            $('#description-update').val(response.description);
            $('#status-update').val(response.status).trigger('change');

        },
        error: function(jqXHR, status, error) { 
           Toast.fire({ icon: 'error', title: jqXHR.responseJSON })
        }       
    });
    

});

//Função para atualizar a tarefa
$( "#task-update" ).submit(function( event ) {
    
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        title: $("#title-update").val(),
        description: $("#description-update").val(),
        status: $("#status-update").val(),
        hash: $('#task_hash_update').val(),
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: updateTask,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
         // Fecha o modal
         $('#modal-lg-edit-task').modal('hide');
        // Limpa o formulário 
        $('#task-update')[0].reset();   
        // mensagem de aviso
        Toast.fire({ icon: 'success', title: data.message });
        // Atualiza a lista de tarefas
        loadTasks('my_tasks');

      },
      error: function(jqXHR, status, error) { 
        // Fecha o modal
        $('#modal-lg-edit-task').modal('hide');
        // Limpa o formulário 
        $('#task-update')[0].reset();   
        // mensagem de aviso
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });

 //Evento de clique no botão para deletar a tarefa
 $(document).on('click', '.btn-task-delete', function(e) {

    const data = {
        hash: $(this).data('task-hash'),
    }

    Swal.fire({
        title: 'Tem certeza que deseja excluir ?',
        text: "Depois de excluir você não poderá mais recuperar os dados dessa tarefa.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        color: "#fff",
        confirmButtonText: 'Sim, tenho.',
        cancelButtonText: 'Não'
      }).then((result) => {

        if (result.isConfirmed) {

            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: deleteTask,
                dataType : 'json',
                type: 'POST',
                data: data,
                success:function(data) {
                    
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        text: '',
                        confirmButtonColor: "#005D92",
                        allowOutsideClick: false,
                        }).then((result) => {

                            //Confirma o resultado
                            if (result.isConfirmed) {
                                // Atualiza a lista de tarefas
                                loadTasks('all_tasks');
                            } else {
                                // Atualiza a lista de tarefas
                                loadTasks('all_tasks');
                            }
                             
                        })

                },
                error: function(jqXHR, status, error) { 
                   Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
                }       
            });

        }
      })

 });

 //Função para carregar todos os usuários no multiselect do filtro de tarefas
const loadUsers = () => {
    // Limpa o select
    $('#user-filter').empty();
    // Percorre todos os usuários
    users.forEach(function(user) {
        $('#user-filter').append(
            `<option value="${user.id}">${user.name}</option>`
        );
    });
    // Inicializa o Select2
    if ($.fn.select2) {
        $('#user-filter').select2({
            placeholder: "Selecione os usuários",
            allowClear: true
        });
    }
}

 //Função para Filtrar tarefas pelos status, por pessoas, pesquisar pelo título, levando em consideração  o filtro de todos os usuário ou só do usuário logado via post pelo subimit do task-filter-form.
$( "#task-filter-form" ).submit(function( event ) {
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        title: $("#title-filter").val(),
        status: $("#status-filter").val(),
        user_id: $("#user-filter").val(),
        type: $('input[name="filter-tasks-dashboard"]:checked').val()
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: filterTask,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {

        // dividir o data em dois arrays, um para pendente e outro para concluida
        const pendingTasks = data.filter(task => task.status === 'Pendente');
        const completedTasks = data.filter(task => task.status === 'Concluída');

        console.log(data);
        console.log(pendingTasks);
        console.log(completedTasks);

        creatCardTaskPending(pendingTasks);
        creatCardTaskCompleted(completedTasks);

      },
      error: function(jqXHR, status, error) { 
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });

