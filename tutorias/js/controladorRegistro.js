$(document).ready(function() {
  $("#confirmationModal").hide();
  const $selectTutor = $("#tutor");
  const $selectGenero = $("#genero_tutor");

  $selectTutor.prop("disabled", true);

  $selectGenero.change(function() {
      const generoSeleccionado = $(this).val();

      $selectTutor.empty();

      jQuery.ajax({
          url: 'php/tutoresDisponibles.php',
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

  /*
    $("#registroForm").submit(function(event) {
        event.preventDefault();
        const formData = {};
        $(this).serializeArray().forEach(field => {
            formData[field.name] = field.value;
        });

        // Create the table within the modal
        const $table = $("<table>").appendTo("#modalTable");
        $.each(formData, function(key, value) {
            $table.append(`<tr><td>${key}:</td><td>${value}</td></tr>`);
        });

        $("#registroForm").hide();
        $("#confirmationModal").show();

        $("#btnConfirmar").click(function() {
            $.ajax({
                url: 'php/registro.php', // Replace with your actual PHP endpoint
                method: 'POST',
                data: formData,
                success: function(response) {
                    $("#confirmationModal").hide();
                    alert(response);
                    // Handle successful registration (e.g., show a success message)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error al procesar el registro: " + errorThrown); 
                }
            });
        });

        $("#btnModificar").click(function() {
            $("#confirmationModal").hide();
            $("#registroForm").show();
        });

        $(".close-button").click(function() {
            $("#confirmationModal").hide();
            $("#registroForm").show();
        });
    });

 */
});
