$(document).ready(onDocumentReady);

function onDocumentReady() {
    $('#cerrar_sesion').click(onCerrarSesionClick);
    solicitarDatosAlumnos();
}

function onCerrarSesionClick(e) {
    e.preventDefault(); // Previene la acción por defecto del botón
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

function solicitarDatosAlumnos() {
    $.ajax({
        url: 'php/recuperarAlumnos.php',
        type: 'POST',
        dataType: 'json',
        success: onRecuperarAlumnosSuccess,
        error: onRecuperarAlumnosError
    });
}

function onRecuperarAlumnosSuccess(alumnos) {
    var tbody = $('table tbody');
    tbody.empty(); // Limpiar la tabla antes de añadir nuevos datos
    $.each(alumnos, function(i, alumno) {
        var tr = $('<tr>').append(
            $('<td>').text(alumno.boleta),
            $('<td>').text(alumno.nombre),
            $('<td>').text(alumno.apellido_paterno),
            $('<td>').text(alumno.apellido_materno),
            $('<td>').text(alumno.semestre),
            $('<td>').text(alumno.carrera),
            $('<td>').text(alumno.nombre_tutor),
            $('<td>').html('<div class="btn-group" role="group"><button class="btn btn-primary btn-sm" id="modificar">Modificar</button><button class="btn btn-danger btn-sm" id="eliminar">Eliminar</button></div>')
        );
        tbody.append(tr);
    });
}

function onRecuperarAlumnosError() {
    alert('Error al recuperar los datos de los alumnos');
}