@extends('layouts.admin')

@section('title', 'Dashboard')

@section('css')
    <!-- Myself Css -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@section('content')

    <div class="content-wrapper kanban" >
            <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                <div class="col-sm-12 d-flex flex-row align-items-center justify-content-between">

                    <div class="row">
                        <h1 class="mr-3">Tarefas</h1>

                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input type="radio" name="filter-tasks-dashboard" id="option_a1" value="my_tasks" autocomplete="off" checked=""> Minhas
                            </label>
                            <label class="btn btn-secondary">
                                <input type="radio" name="filter-tasks-dashboard" id="option_a2" value="all_tasks" autocomplete="off"> Todas
                            </label>
                        </div>
                    </div>

                    <a class="btn btn-app" data-toggle="modal" data-target="#modal-lg-create-task">
                        <i class="fas fa-save"></i> Criar Tarefa
                    </a>
                
                </div>

                </div>
            </div>

            <h6 class="text-center display-4">Filtros de Pesquisa</h6>
            <form id="task-filter-form" >
                <div class="row" >
                    <div class="col-md-10 offset-md-1" >
                        <div class="row" >
                             <div class="col-6" >
                                <div class="form-group" >
                                    <label>Status:</label>
                                    <select class="select2 select2-hidden-accessible" id="status-filter" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option selected="" value="">Todos</option>
                                        <option value="Pendente">Pendente</option>
                                        <option value="Concluída">Concluída</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-6" >
                                <div class="form-group" >
                                    <label>Pessoas:</label>
                                    <select class="select2 select2-hidden-accessible" multiple="" id="user-filter" data-placeholder="Selecione os usuários" style="width: 100%;"  tabindex="-1" aria-hidden="true">
         
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <input type="search" class="form-control form-control-lg" placeholder="Titulo" id="title-filter"">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <section class="content pb-3">
            <div class="container-fluid h-100">

                <div class="card card-row card-primary">

                    <div class="card-header">
                        <h3 class="card-title">
                        Pendente
                        </h3>
                    </div>

                    <div class="card-body" id="pendente">

                    </div>
                </div>

                <div class="card card-row card-success">

                    <div class="card-header">
                        <h3 class="card-title">
                        Concluída
                        </h3>
                    </div>

                    <div class="card-body" id="concluida">


                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- MODAL LG CREATE TASK-->
    <div class="modal fade" id="modal-lg-create-task" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Criar Tarefa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card card-light">
                <div class="card-header">
                    
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="task-create">
                    <div class="card-body">
                    <div class="form-group">
                        <label for="title">Titulo</label>
                        <input type="text" class="form-control" id="title" name="titulo" placeholder="Titulo">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Descrição">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select2 select2-hidden-accessible" id="status" style="width: 100%;"  tabindex="-1" aria-hidden="true">
                            <option selected="selected" value="Pendente">Pendente</option>
                            <option value="Concluída">Concluída</option>
                        </select>
                    </div>

    
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                    <button type="submit" class="btn btn-light">Cadastrar</button>
                    </div>
                </form>
                </div>
                
            </div>
    
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- MODAL GERENCIAS USUÁRIOS-->
    <div class="modal fade" id="modal-lg-task-user-manage" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Gerenciar Usuários Vinculados</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card card-light">
                <div class="card-header">
                    
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                
                    <div class="card-body">

                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title title-user-manage"></h3>

                            <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body body-user-manage" style="display: block;">
                            
                        </div>
                        <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    
                <form id="task-user-manage">
                    <input type="hidden" id="task_hash" name="task_hash" value="">
                    <div class="form-group">
                        <label>Vinculos</label>
                        <select class="select2 select2-hidden-accessible" multiple="" id="user_manager_id" data-placeholder="Select a State" style="width: 100%;"  tabindex="-1" aria-hidden="true"></select>
                    </div>
    
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                    <button type="submit" class="btn btn-light">Cadastrar</button>
                    </div>
                </form>
                </div>
                
            </div>
    
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- MODAL LG UPDATE TASK-->
    <div class="modal fade" id="modal-lg-edit-task" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Atualizar Tarefa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card card-light">
                <div class="card-header">
                    
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="task-update">
                    <div class="card-body">
                    <input type="hidden" id="task_hash_update" name="task_hash" value="">
                    <div class="form-group">
                        <label for="title">Titulo</label>
                        <input type="text" class="form-control" id="title-update" name="titulo" placeholder="Titulo">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <input type="text" class="form-control" id="description-update" name="description" placeholder="Descrição">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select2 select2-hidden-accessible" id="status-update" style="width: 100%;"  tabindex="-1" aria-hidden="true">
                            <option value="Pendente">Pendente</option>
                            <option value="Concluída">Concluída</option>
                        </select>
                    </div>


                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                    <button type="submit" class="btn btn-light">Cadastrar</button>
                    </div>
                </form>
                </div>
                
            </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      

@endsection


@section('js')

<!-- Select2 -->
<script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('AdminLTE/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements   
            $('.select2').select2()
        })

        //Constants Routes
        const listUsers = "{{ route('users.list') }}";
        const createTaskUrl = "{{ route('tasks.store') }}";
        const listTaskUserLoggedStatus = "{{ route('tasks.user.logged.status', ['status' => ':status']) }}";
        const listTaskAllStatus = "{{ route('tasks.all.status', ['status' => ':status']) }}";
        const listTaskUserHash = "{{ route('tasks.user.logged', ['hash' => ':hash']) }}";
        const updateTaskUserHash = "{{ route('tasks.user.update') }}";
        const updateTaskUpdateStatus = "{{ route('tasks.update.status') }}";
        const updateTask = "{{ route('tasks.update') }}";
        const deleteTask = "{{ route('tasks.delete') }}";
        const filterTask = "{{ route('tasks.filter') }}";
        
    </script>

    

    <script src="{{ asset('js/dashboard.js') }}"></script>

@endsection