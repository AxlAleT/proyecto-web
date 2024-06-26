$(document).ready(onDocumentReady);

function onDocumentReady() {
    $('#cerrar_sesion').click(onCerrarSesionClick);
    $('#inicio').click(onCerrarSesionClick);

    solicitarDatosAlumnos();

    $('table tbody').on('click', '.modificar, .eliminar', function() {
        var boleta = $(this).closest('.btn-group').attr('id'); 
        if ($(this).hasClass('eliminar')) {
            eliminar(boleta); 
        } else if ($(this).hasClass('modificar')) {
            modificar(boleta);
        }
        solicitarDatosAlumnos();
    });
    $('#create').on('click', create);


    $('#submitForm').click(function() {
        var formData = $('#registroForm').serialize();
        $.ajax({
          type: "POST",
          url: "php/create.php",
          data: formData,
          success: function() {
              alert("Registro creado con éxito.");
              $('#createFormModal').modal('hide'); // Cierra el modal
              $('#registroForm')[0].reset(); // Resetea el formulario
              solicitarDatosAlumnos();
            },
          error: function() {
            alert("Error en la solicitud AJAX.");
          }
        });
            solicitarDatosAlumnos();
      });

      const $selectTutor = $("#tutor");
      const $selectGenero = $("#genero_tutor");
    
      $selectTutor.prop("disabled", true);
    
      $selectGenero.change(function() {
          const generoSeleccionado = $(this).val();
    
          $selectTutor.empty();
    
          jQuery.ajax({
              url: '../php/tutoresDisponibles.php',
              method: 'POST',
              data: { genero: generoSeleccionado },
              dataType: 'json',
              success: function(data) {
                  if (data.length > 0) {
                      $selectTutor.prop("disabled", false);
    
                      data.forEach(tutor => {
                          $selectTutor.append(`<option value="${tutor.id}">${tutor.nombre} ${tutor.apellido_paterno} ${tutor.apellido_materno}</option>`);
                      });
                  } else {
                      $selectTutor.append('<option value="">No se encontraron tutores.</option>');
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  let errorMessage = "Error al cargar los tutores.";
                  if (jqXHR.status === 404) {
                      errorMessage = "Archivo PHP no encontrado.";
                  } else if (jqXHR.status === 500) {
                      errorMessage = "Error interno del servidor.";
                  }
                  alert(errorMessage);
                  console.error(errorThrown); 
              }
          });
      });
}

function onCerrarSesionClick(e) {
    e.preventDefault();
    enviarSolicitudCerrarSesion();
}

function enviarSolicitudCerrarSesion() {
    $.ajax({
        url: 'php/logout.php',
        type: 'POST',
        success: onCerrarSesionSuccess,
        error: onCerrarSesionError
    });
}

function onCerrarSesionSuccess(response) {
    window.location.href = '../index.html';
}

function onCerrarSesionError() {
    alert('Error al cerrar sesión');
}

function solicitarDatosAlumnos(pagina = 1, alumnosPorPagina = 50) {
    $.ajax({
        url: 'php/recuperarAlumnos.php',
        type: 'POST',
        dataType: 'json',
        data: {
            pagina: pagina,
            alumnosPorPagina: alumnosPorPagina
        },
        success: onRecuperarAlumnosSuccess,
        error: onRecuperarAlumnosError
    });
}

function onRecuperarAlumnosSuccess(alumnos) {
    var tbody = $('table tbody');
    tbody.empty();
    $.each(alumnos, function(i, alumno) {
        var tr = $('<tr>').append(
            $('<td>').text(alumno.boleta),
            $('<td>').text(alumno.nombre + " " + alumno.apellido_paterno + " " + alumno.apellido_materno),
            $('<td>').text(alumno.semestre),
            $('<td>').text(alumno.carrera),
            $('<td>').text(alumno.nombre_tutor),
            $('<td>').text(alumno.tipo_tutoria.nombre),
            $('<td>').html('<div class="btn-group" role="group" id="' + alumno.boleta + '"><button class="btn btn-primary btn-sm modificar">Modificar</button><button class="btn btn-danger btn-sm eliminar">Eliminar</button></div>')
        );
        tbody.append(tr);
    });
}

function onRecuperarAlumnosError(jqXHR, textStatus, errorThrown) {
    alert('Error al recuperar los datos de los alumnos: ' + textStatus);
}

function eliminar(boleta) {
    $.ajax({
        url: 'php/delete.php',
        type: 'POST',
        dataType: 'json',
        data: {boleta: boleta},
        success: function(response) {
            if (response.error) {
                alert('Error al eliminar el alumno: ' + response.error);
            } else {
                alert('Alumno eliminado correctamente');
            }
        },
        error: function(xhr, status, error) {
            alert('Error al eliminar el alumno');
        }
    });
    solicitarDatosAlumnos();
}

function modificar(boleta) {
    alert('Modificar alumno con boleta: ' + boleta);
}

function create(){

}