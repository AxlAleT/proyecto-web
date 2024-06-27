
document.addEventListener('DOMContentLoaded', function () {
    const boleta = document.getElementById('boleta');
    const nombre = document.getElementById('nombre');
    const AP = document.getElementById('AP');
    const AM = document.getElementById('AM');
    const tel = document.getElementById('tel');
    const correo = document.getElementById('correo');
    const contrasena = document.getElementById('contrasena');

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
        if (!element.value.trim()) {
            setError(element, 'Este campo es obligatorio.');
            return false;
        } else if (!pattern.test(element.value)) {
            setError(element, message);
            return false;
        } else {
            clearError(element);
            return true;
        }
    };

    boleta.addEventListener('input', function () {
        const boletaPattern = /^\d{10}$/;
        validateField(boleta, boletaPattern, 'El No. de Boleta debe ser un número de 10 dígitos.');
    });

    const namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/;
    [nombre, AP, AM].forEach(element => {
        element.addEventListener('input', function () {
            validateField(element, namePattern, 'Este campo solo puede contener letras, espacios y acentos.');
        });
    });

    tel.addEventListener('input', function () {
        const phonePattern = /^\d{10}$/;
        validateField(tel, phonePattern, 'El número de teléfono debe ser un número de 10 dígitos.');
    });

    correo.addEventListener('input', function () {
        const emailPattern = /^[^@]+@ipn\.mx$/;
        validateField(correo, emailPattern, 'El correo debe terminar con @ipn.mx.');
    });

    contrasena.addEventListener('input', function () {
        const passwordPattern = /^.{6,}$/;
        validateField(contrasena, passwordPattern, 'La contraseña debe tener al menos 6 caracteres.');
    });

    const form = document.getElementById('registroForm');
    form.addEventListener('submit', function (event) {
        let valid = true;
        let firstInvalidElement = null;

        if (!validateField(boleta, /^\d{10}$/, 'El No. de Boleta debe ser un número de 10 dígitos.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || boleta;
        }

        if (!validateField(nombre, namePattern, 'El nombre solo puede contener letras, espacios y acentos.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || nombre;
        }

        if (!validateField(AP, namePattern, 'El apellido paterno solo puede contener letras, espacios y acentos.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || AP;
        }

        if (!validateField(AM, namePattern, 'El apellido materno solo puede contener letras, espacios y acentos.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || AM;
        }

        if (!validateField(tel, /^\d{10}$/, 'El número de teléfono debe ser un número de 10 dígitos.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || tel;
        }

        if (!validateField(correo, /^[^@]+@ipn\.mx$/, 'El correo debe terminar con @ipn.mx.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || correo;
        }

        if (!validateField(contrasena, /^.{6,}$/, 'La contraseña debe tener al menos 6 caracteres.')) {
            valid = false;
            firstInvalidElement = firstInvalidElement || contrasena;
        }

        if (!valid) {
            event.preventDefault();
            firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidElement.focus();
        }
    });
});

