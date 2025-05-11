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

//iniciaalizar o datatable
$(document).ready(function() {
    listUserTable();
});

 //Função para listar todos os usuários
 const listUserTable = () => {
    $.get({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: listUsers,
        dataType : 'json',
        type: 'GET',
        success:function(data) {

            $("#tableUser").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, data: data, "language": {"url": "/js/dataTableLinguagemJs.json"},
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

                "columns":[
                      
                    {"data":"name"},
                    {"data":"email"},

                    {
                        "data": "status",
                        render: function(data, type, row) {
                            return data == 1 ? '<span class="badge badge-success">Ativado</span>' : '<span class="badge badge-danger">Desativado</span>';
                        }
                    },

                    {
                        "data": null,
                        render: function(data, type, row, meta) {
                            let url = `/users/${row.hash}/edit`; // monta a URL com hash
                            return `<a href="${url}" class="btn btn-tool btn-user-edit"><i class="fas fa-pen"></i></a>`;
                        }
                     },

                    {
                       "data": null,
                        render: function(data, type, row, meta){
                        return `<a href="#" class="btn btn-tool btn-user-delete" data-task-hash="${row.hash}"> <i class="far fa-trash-alt"></i></a>`
                       }
                    },
                 ]

                }).buttons().container().appendTo('#tableUser_wrapper .col-md-6:eq(0)');

        },
        error: function(jqXHR, status, error) { 
            console.log(jqXHR);
           Toast.fire({ icon: 'error', title: jqXHR.responseJSON })
        }       
    });
};


//Evento de clique no botão para deletar a tarefa
$(document).on('click', '.btn-user-delete', function(e) {

    const data = {
        hash: $(this).data('task-hash'),
    }

    Swal.fire({
        title: 'Tem certeza que deseja excluir ?',
        text: "Depois de excluir você não poderá mais recuperar os dados desse usuário.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, tenho.',
        cancelButtonText: 'Não'
      }).then((result) => {

        if (result.isConfirmed) {

            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: deleteUsers,
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
                                listUserTable();
                            } else {
                                listUserTable();
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