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

//Editar novo usuário
$( "#form-edit-user" ).submit(function( event ) {
    
    //Cancela o comportamento padão de submit do formulário
    event.preventDefault();

    const data = {
        name: $("#name-user").val(),
        email: $("#email-user").val(),
        password: $("#password-user").val(),
        password_confirmation: $("#password-confirmation-user").val(),
        status: $("#status-user").val() ,
        email_confirm: $("#email-confirm-user").val(),
        hash: $("#hash-user").val(),
    }

    $.post({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: updateUser,
      dataType : 'json',
      type: 'POST',
      data: data,
      success:function(data) {
          Toast.fire({ icon: 'success', title: data.mensagem });
          location.href='/users/index';
      },
      error: function(jqXHR, status, error) { 
         Toast.fire({ icon: 'error', title: jqXHR.responseJSON });
      }       
    });

 });