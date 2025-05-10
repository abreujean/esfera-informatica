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