$(document).ready(function() {
    // Obtener los parámetros de la URL
    var params = new URLSearchParams(window.location.search);
    
    // Verificar si existe el parámetro 'error'
    if (params.has('error')) {
        var error = params.get('error');
        
        // Decodificar el mensaje de error (reemplazar '+' con espacios, etc.)
        var mensajeError = decodeURIComponent(error.replace(/\+/g, ' '));
        
        // Mostrar el mensaje de error en un alerta
        alert("Error: " + mensajeError);
    }
});