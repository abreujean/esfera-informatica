@extends('layouts.admin')

@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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

                <a class="btn btn-app" data-toggle="modal" data-target="#modal-lg">
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
    <div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Large Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

@endsection