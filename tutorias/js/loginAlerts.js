$(document).ready(function() {
    // Utilizar jQuery para analizar los parámetros de la URL
    var params = new URLSearchParams(window.location.search);

    // Verificar si el parámetro 'error' existe
    if (params.has('error')) {
        var error = params.get('error');
        switch (error) {
            case 'invalid_credentials':
                alert('Credenciales inválidas. Por favor, intente de nuevo.');
                break;
            case 'user_not_found':
                alert('Usuario no encontrado. Por favor, verifique sus datos.');
                break;
            case 'missing_credentials':
                alert('Faltan credenciales. Por favor, complete todos los campos.');
                break;
            default:
                alert('Error desconocido. Por favor, intente de nuevo.');
        }
    }
});