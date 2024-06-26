$(document).ready(controlador);

function controlador() {
    loadContenido('alumnos.html');

    $('#alumnos').click(() => loadContenido('alumnos.html'));
    $('#tutores').click(() => loadContenido('tutores.html'));
    $('#administradores').click(() => loadContenido('administradores.html'));
}

function loadContenido(archivo) {
    $('#contenido').fadeOut(300);

    return $.ajax({
        url: `../admin/parciales/${archivo}`
    })
    .then(data => $('#contenido').html(data).fadeIn(300))
    .catch(error => {
        var msg = "Error al cargar el contenido: ";
        $('#contenido').html(msg + error.status + " " + error.statusText).fadeIn(300);
    });
}
