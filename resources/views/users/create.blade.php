@extends('layouts.admin')

@section('title', 'Criar Usuário')

@section('css')

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@section('content')

            <!-- left column -->
            <div class="col-md-6 mt-5 mr-auto ml-auto">
                    <div class="card card-light">
                        <div class="card-header">
                        <h3 class="card-title">Criar Usuário</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="form-create-user" >
        
                        <div class="card-body">

                            <div class="form-group">
                                <label for="name-user">Nome</label>
                                <input type="text" class="form-control" name='name-user' id="name-user" placeholder="Nome">
                            </div>
                            <div class="form-group">
                                <label for="email-user">Email</label>
                                <input type="email" class="form-control" name='email-user'  id="email-user" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="email_confirmation">Confirme o E-mail</label>
                                <input type="email" name="email_confirmation" id="email-confirmation-user" class="form-control" required placeholder="Confirme o E-mail">
                            </div>
                            <div class="form-group">
                                <label for="password-user">Senha</label>
                                <input type="password" class="form-control" name="password-user" id="password-user" placeholder="Senha">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirme a Senha</label>
                                <input type="password" name="password_confirmation" id="password-confirmation-user" class="form-control" required placeholder="Confirme a Senha">
                            </div>
                            
                            <div class="form-group">
                                <label for="status-user">Status</label>
                                <select class="form-control select2" name="status-user" id="status-user">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                        </form>
                    </div>
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
      const createUser = "{{ route('users.store') }}";
        
    </script>

<!-- Custom JS -->
<script src="{{ asset('js/user.js') }}"></script>
    
@endsection