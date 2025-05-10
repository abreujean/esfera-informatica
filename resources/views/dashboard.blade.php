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
                            <input type="radio" name="options" id="option_a1" autocomplete="off" checked=""> Minhas
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="options" id="option_a2" autocomplete="off"> Todas
                        </label>
                    </div>
                </div>

                <a class="btn btn-app" data-toggle="modal" data-target="#modal-lg-create-task">
                    <i class="fas fa-save"></i> Criar Tarefa
                </a>
            
            </div>

            </div>
        </div>
        </section>

        <section class="content pb-3">

        <div class="container-fluid h-100">

            <div class="card card-row card-primary">

                <div class="card-header">
                    <h3 class="card-title">
                    Pendente
                    </h3>
                </div>

                <div class="card-body">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Update Readme</h5>
                            <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-link">#2</a>
                            <a href="#" class="btn btn-tool">
                                <i class="fas fa-pen"></i>
                            </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>
                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                            Aenean commodo ligula eget dolor. Aenean massa.
                            Cum sociis natoque penatibus et magnis dis parturient montes,
                            nascetur ridiculus mus.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-row card-success">

                <div class="card-header">
                    <h3 class="card-title">
                    Concluída
                    </h3>
                </div>

                <div class="card-body">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Update Readme</h5>
                            <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-link">#2</a>
                            <a href="#" class="btn btn-tool">
                                <i class="fas fa-pen"></i>
                            </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>
                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                            Aenean commodo ligula eget dolor. Aenean massa.
                            Cum sociis natoque penatibus et magnis dis parturient montes,
                            nascetur ridiculus mus.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </section>
    </div>

    <!-- MODAL -->
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
                        <select class="form-control select2 select2-hidden-accessible" id="status" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            <option selected="selected" data-select2-id="Pendente">Pendente</option>
                            <option data-select2-id="Concluída">Concluída</option>
                        </select>
                    </div>

                    <div class="form-group" data-select2-id="82">
                        <label>Vinculos</label>
                        <select class="select2 select2-hidden-accessible" multiple="" id="user_id" data-placeholder="Select a State" style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true">
                            @foreach ($users as $user)
                                <option  value="{{ $user->id }}" @if($user->id == Auth::id()) selected @endif >{{ $user->name }}</option>
                            @endforeach
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

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        })

        const createTaskUrl = "{{ route('tasks.store') }}";
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('AdminLTE/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

@endsection