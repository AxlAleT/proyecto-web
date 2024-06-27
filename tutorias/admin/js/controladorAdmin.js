$(document).ready(onDocumentReady);

function onDocumentReady() {
    $('#cerrar_sesion').click(onCerrarSesionClick);
    $('#actualizar').click(solicitarDatosAlumnos());
    $('#inicio').click(onCerrarSesionClick);
    solicitarDatosAlumnos();
    $('#createFormModal').modal('hide');
    $('#updateFormModal').modal('hide');
    $('#crear_registro').click(onCrearRegistroClick);
    onEliminarModificarClick();
    onBuscarClick();
}

function onBuscarClick() {
    $('#buscar').click(function(e) {
        e.preventDefault();
        var busqueda = $('#busquedaBoleta').val();
        $.ajax({
            url: 'php/read.php',
            type: 'POST',
            data: {boleta: busqueda},
            success: function(response) {
                var data = JSON.parse(response);
                if(data.success) {
                    var tbody = $('table tbody');
                    tbody.empty();
                        var tr = $('<tr>').append(
                            $('<td>').text(data.alumno.boleta),
                            $('<td>').text(data.alumno.nombre + " " + data.alumno.apellido_paterno + " " + data.alumno.apellido_materno),
                            $('<td>').text(data.alumno.semestre),
                            $('<td>').text(data.alumno.carrera),
                            $('<td>').text(data.alumno.tutor.nombre + " " + data.alumno.tutor.apellido_paterno + " " + data.alumno.tutor.apellido_materno),
                            $('<td>').text(data.alumno.tipo_tutoria.nombre),
                            $('<td>').html('<div class="btn-group" role="group" id="' + data.alumno.boleta + '"><button class="btn btn-primary btn-sm modificar">Modificar</button><button class="btn btn-danger btn-sm eliminar">Eliminar</button></div>')
                        );
                        tbody.append(tr); 
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al buscar:', error);
            }
        });
    });
}


function onEliminarModificarClick() {
    $(document).on('click', '.btn-group button', function() {

        var boleta = $(this).parent('.btn-group').attr('id');
    
        if ($(this).hasClass('modificar')) {
            onModificarClick(boleta);
        } else if ($(this).hasClass('eliminar')) {
            $.ajax({
                url: 'php/delete.php',
                type: 'POST',
                data: {boleta: boleta},
                success: function(response) {
                    var data = JSON.parse(response);
                    if(data.success) {
                        solicitarDatosAlumnos();
                    } else {
                        console.error(data.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar:', error);
                }
            });
        }
    });
}

function onModificarClick(boleta) {
    // Paso 2: Realizar solicitud AJAX a read.php
    $.ajax({
        url: 'php/read.php',
        type: 'POST',
        data: { boleta: boleta },
        success: function(response) {
            // Paso 3: Obtener datos del alumno
            if (response.success) {
                const alumno = response.alumno;
                // Paso 4: Abrir modal
                $("#Ututoria_select").change(onUTutoriaSelect);
                $("#Ututor").change(onUTutorSelect);
                $('#updateFormModal').modal('show');
                // Paso 5: Precargar datos en el formulario
                $('#Uboleta').val(alumno.boleta);
                $('#Unombre').val(alumno.nombre);
                $('#UAP').val(alumno.apellido_paterno);
                $('#UAM').val(alumno.apellido_materno);
                $('#Utel').val(alumno.telefono);
                $('#Usemestre').val(alumno.semestre);
                $('#Ucarrera').val(alumno.carrera);
                $('#Ucorreo').val(alumno.correo);
                $('#Ututoria_select').val(alumno.tipo_tutoria.id_tipo_tutoria);
                onUTutoriaSelect();
                $('#Ututor').val(alumno.tutor.id_tutor);
                onUTutorSelect();
                $('#Ugrupo').val(alumno.grupo.id_grupo);
            } else {
                alert('No se encontró el alumno.');
            }
        },
        error: function() {
            alert('Error al realizar la solicitud.');
        }
    });

    // Paso 6 y 7: Escuchar evento de clic en el botón de enviar y recopilar datos
    $('#UbtnRegistrar').off('click').on('click', function() {
        const formData = {
            boleta: $('#Uboleta').val(),
            nombre: $('#Unombre').val(),
            AP: $('#UAP').val(),
            AM: $('#UAM').val(),
            tel: $('#Utel').val(),
            semestre: $('#Usemestre').val(),
            carrera: $('#Ucarrera').val(),
            correo: $('#Ucorreo').val(),
            contrasena: $('#Ucontrasena').val(),
            id_tipo_tutoria: $('#Ututoria_select').val(),
            id_tutor: $('#Ututor').val(),
            grupo: $('#Ugrupo').val()
        };

        // Paso 8: Realizar solicitud AJAX a update.php
        $.ajax({
            url: 'php/update.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                    alert(response.mensaje);
                    $('#updateFormModal').modal('hide');
                    solicitarDatosAlumnos();
            },
            error: function() {
                alert('Error al realizar la solicitud de actualización.');
            }
        });
    });
}

function onUTutoriaSelect() {
    idTipoTutoria = $("#Ututoria_select").val();
    $.ajax({
        url: '../php/tutores_disponibles.php',
        type: 'POST',
        data: { id_tipo_tutoria: idTipoTutoria },
        dataType: 'json',
        success: function(data) {
            var select = $('#Ugrupo');
                select.empty();
                select.append($('<option>', { 
                    value: "-1",
                    text : "Selecciona un grupo",
                    disabled: true,
                    selected: true
                }));

            var select = $('#Ututor');
            select.empty();
            select.append($('<option>', { 
                value: "",
                text : "Selecciona un tutor",
                disabled: true,
                selected: true
            }));

            $.each(data, function(index, item) {
                select.append($('<option>', { 
                    value: item.id,
                    text : item.nombre + " " + item.apellido_paterno + " " + item.apellido_materno
                }));
            });
            select.prop("disabled", false);
        },
        error: function(error) {
            alert('Error: ', error);
        }
    });
}


function onUTutorSelect() {
    if ($("#Ututoria_select").val() == 2) {
        var idTutor = $("#Ututor").val();

        $.ajax({
            url: '../php/grupos_disponibles.php',
            type: 'POST',
            data: { id_tutor: idTutor },
            dataType: 'json',
            success: function(data) {
                var select = $('#Ugrupo');
                select.empty();
                select.append($('<option>', { 
                    value: "-1",
                    text : "Selecciona un grupo",
                    disabled: true,
                    selected: true
                }));
                $.each(data, function(index, item) {
                    select.append($('<option>', { 
                        value: item.id_grupo,
                        text : item.codigo_grupo
                    }));
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
}



function onCrearRegistroClick() {

        $("#grupo").hide();
        $("#grupo_label").hide();
        $("#grupo_label").prop("disabled", true);
        $("#tutor").prop("disabled", true);
    
        $("#tutoria_select").change(onTutoriaSelect);
        $("#tutor").change(onTutorSelect);
        $("#btnRegistrar").click(function(e) {
            e.preventDefault();
            registrarInformacion();
            });
}

function onTutoriaSelect() {
    idTipoTutoria = $("#tutoria_select").val();
    $.ajax({
        url: '../php/tutores_disponibles.php',
        type: 'POST',
        data: { id_tipo_tutoria: idTipoTutoria },
        dataType: 'json',
        success: 
        function(data) {
            var select = $('#grupo');
            select.empty();
            select.append($('<option>', { 
                value: "-1",
                text : "Selecciona un grupo",
                disabled: true,
                selected: true
            }));
            var select = $('#tutor');
            select.empty();
            select.append($('<option>', { 
                value: "",
                text : "Selecciona un tutor",
                disabled: true,
                selected: true
            }));
            $.each(data, function(index, item) {
                select.append($('<option>', { 
                    value: item.id,
                    text : item.nombre + " " + item.apellido_paterno + " " + item.apellido_materno
                }));
            });
            select.prop("disabled", false);
        },
        error: function(error) {
            alert('Error: ', error);
        }
    });
}


function onTutorSelect() {
    if ($("#tutoria_select").val() == 2) {
        $("#grupo").show();
        $("#grupo_label").show();
        var idTutor = $("#tutor").val();

        $.ajax({
            url: '../php/grupos_disponibles.php',
            type: 'POST',
            data: { id_tutor: idTutor },
            dataType: 'json',
            success: function(data) {
                var select = $('#grupo');
                select.empty();
                select.append($('<option>', { 
                    value: "-1",
                    text : "Selecciona un grupo",
                    disabled: true,
                    selected: true
                }));
                $.each(data, function(index, item) {
                    select.append($('<option>', { 
                        value: item.id_grupo,
                        text : item.codigo_grupo
                    }));
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
}

function registrarInformacion() {
    var formData = {
        boleta: $("#boleta").val(),
        nombre: $("#nombre").val(),
        AP: $("#AP").val(),
        AM: $("#AM").val(),
        tel: $("#tel").val(),
        semestre: $("#semestre").val(),
        carrera: $("#carrera").val(),
        correo: $("#correo").val(),
        contrasena: $("#contrasena").val(),
        grupo: $("#grupo").val(),
        id_tipo_tutoria: $("#tutoria_select").val(),
        id_tutor: $("#tutor").val()
    };

    $.ajax({
        url: 'php/create.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                alert("Error: " + response.error);
            } else {
                alert("Registro exitoso");
                $('#createFormModal').modal('hide');
                $('#createFormModal').trigger('reset');
                solicitarDatosAlumnos();
            }
        },
        error: function(xhr, status, error) {
            alert("Error al registrar: " + error);
        }
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
            $('<td>').text(alumno.tutor.nombre + " " + alumno.tutor.apellido_paterno + " " + alumno.tutor.apellido_materno),
            $('<td>').text(alumno.tipo_tutoria.nombre),
            $('<td>').html('<div class="btn-group" role="group" id="' + alumno.boleta + '"><button class="btn btn-primary btn-sm modificar">Modificar</button><button class="btn btn-danger btn-sm eliminar">Eliminar</button></div>')
        );
        tbody.append(tr);
    });
}

function onRecuperarAlumnosError(jqXHR, textStatus, errorThrown) {
    alert('Error al recuperar los datos de los alumnos: ' + textStatus);
}