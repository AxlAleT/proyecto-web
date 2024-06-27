document.addEventListener('DOMContentLoaded', function () {
    const boleta = document.getElementById('boleta');
    const nombre = document.getElementById('nombre');
    const AP = document.getElementById('AP');
    const AM = document.getElementById('AM');
    const tel = document.getElementById('tel');
    const correo = document.getElementById('correo'); // Añadido para la validación del correo

    const setError = (element, message) => {
        const errorDiv = element.nextElementSibling;
        errorDiv.textContent = message;
        element.classList.add('error');  
    };

    const clearError = (element) => {
        const errorDiv = element.nextElementSibling;
        errorDiv.textContent = '';
        element.classList.remove('error');  
    };

    const validateField = (element, pattern, message) => {
        if (!pattern.test(element.value)) {
            setError(element, message);
        } else {
            clearError(element);
        }
    };

    boleta.addEventListener('input', function () {
        const boletaPattern = /^\d{0,10}$/;
        validateField(boleta, boletaPattern, 'El No. de Boleta debe ser un número de hasta 10 dígitos.');
    });

    const namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/;
    [nombre, AP, AM].forEach(element => {
        element.addEventListener('input', function () {
            validateField(element, namePattern, 'Este campo solo puede contener letras, espacios y acentos.');
        });
    });

    tel.addEventListener('input', function () {
        const phonePattern = /^\d{0,10}$/;
        validateField(tel, phonePattern, 'El número de teléfono debe ser un número de hasta 10 dígitos.');
    });

    // Añadido para la validación del correo
    correo.addEventListener('input', function () {
        const emailPattern = /^[^@]+@ipn\.mx$/;
        validateField(correo, emailPattern, 'El correo debe terminar en @ipn.mx.');
    });

    const form = document.getElementById('registroForm');
    form.addEventListener('submit', function (event) {
        let valid = true;

        if (boleta.value.length !== 10) {
            setError(boleta, 'El No. de Boleta debe ser un número de 10 dígitos.');
            valid = false;
        } else {
            clearError(boleta);
        }

        if (nombre.value.trim() === '' || !namePattern.test(nombre.value)) {
            setError(nombre, 'El nombre solo puede contener letras, espacios y acentos.');
            valid = false;
        } else {
            clearError(nombre);
        }

        if (AP.value.trim() === '' || !namePattern.test(AP.value)) {
            setError(AP, 'El apellido paterno solo puede contener letras, espacios y acentos.');
            valid = false;
        } else {
            clearError(AP);
        }

        if (AM.value.trim() === '' || !namePattern.test(AM.value)) {
            setError(AM, 'El apellido materno solo puede contener letras, espacios y acentos.');
            valid = false;
        } else {
            clearError(AM);
        }

        if (tel.value.length !== 10) {
            setError(tel, 'El número de teléfono debe ser un número de 10 dígitos.');
            valid = false;
        } else {
            clearError(tel);
        }

        // Añadido para la validación del correo en el evento submit
        if (!emailPattern.test(correo.value)) {
            setError(correo, 'El correo debe terminar en @ipn.mx.');
            valid = false;
        } else {
            clearError(correo);
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});