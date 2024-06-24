$(document).ready(function() {

    $('#cerrar_sesion').click(function(e) {
        e.preventDefault(); // Previene la acción por defecto del botón

        // Envía una solicitud AJAX a logout.php
        $.ajax({
            url: './php/logout.php',
            type: 'POST',
            success: function(response) {
                // Redirige a index.html después de cerrar sesión
                window.location.href = '../index.html';
            },
            error: function() {
                // Maneja errores aquí, si los hay
                alert('Error al cerrar sesión');
            }
        });
    });
});
