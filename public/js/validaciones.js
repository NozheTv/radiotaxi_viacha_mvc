/**
 * Validar nombre completo Bolivia:
 * Letras con acentos y Ñ, mínimo un nombre y un apellido de 2 letras mínimos, hasta 4 palabras.
 */
function validarNombreCompleto(valor) {
    const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}( [A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}){1,3}$/;
    return regexNombre.test(valor.trim());
}

/**
 * Validar email estándar
 */
function validarEmail(valor) {
    const regexEmail = /^[a-zA-Z0-9._%+-]{3,}@[a-zA-Z0-9.-]{3,}\.[a-zA-Z]{2,}$/;
    return regexEmail.test(valor.trim());
}

/**
 * Validar teléfono Bolivia: 8 dígitos empezando por 6 o 7
 */
function validarTelefonoBolivia(valor) {
    const regexTelefono = /^[67]\d{7}$/;
    return regexTelefono.test(valor);
}

/**
 * Validar dirección Bolivia (al menos un número y texto)
 */
function validarDireccion(valor) {
    const regexDireccion = /^(?=.*\d+)(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ]+).{5,255}$/;
    return regexDireccion.test(valor.trim());
}

/**
 * Validar contraseña (8-15 chars, al menos una mayúscula y un número)
 */
function validarPassword(valor) {
    const regexPassword = /^(?=.*[A-Z])(?=.*\d).{8,15}$/;
    return regexPassword.test(valor);
}

/**
 * Validar placa Bolivia:
 * 3 o 4 números seguidos de 3 letras, sin espacios ni guiones
 * Ejemplo: 123ABC o 1234XYZ
 */
function validarPlacaBolivia(valor) {
    const regexPlaca = /^\d{3,4}[A-Za-z]{3}$/;
    return regexPlaca.test(valor.trim());
}

/**
 * Validar modelo taxi:
 * Texto alfanumérico y espacios, entre 3 y 20 caracteres
 */
function validarModeloTaxi(valor) {
    const regexModelo = /^[A-Za-z0-9\s]{3,20}$/;
    return regexModelo.test(valor.trim());
}

/**
 * Configurar validaciones para formularios (clientes, conductores, radiotaxis)
 */
function setupValidacionesFormulario(formId, nombreId, emailId, passwordId, telefonoId, direccionId, placaId, modeloId) {
    const form = document.getElementById(formId);
    const nombre = nombreId ? document.getElementById(nombreId) : null;
    const email = emailId ? document.getElementById(emailId) : null;
    const password = passwordId ? document.getElementById(passwordId) : null;
    const telefono = telefonoId ? document.getElementById(telefonoId) : null;
    const direccion = direccionId ? document.getElementById(direccionId) : null;
    const placaInput = placaId ? document.getElementById(placaId) : null;
    const modeloInput = modeloId ? document.getElementById(modeloId) : null;

    // Bloquear espacios en email y password si existen
    [email, password].forEach(input => {
        if (input) {
            input.addEventListener('keydown', e => {
                if (e.key === ' ') e.preventDefault();
            });
            input.addEventListener('paste', e => {
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                if (paste.includes(' ')) e.preventDefault();
            });
        }
    });

    form.addEventListener('submit', e => {
        if (nombre) nombre.value = nombre.value.trim();
        if (email) email.value = email.value.trim();
        if (password) password.value = password.value.trim();
        if (direccion) direccion.value = direccion.value.trim();
        if (placaInput) placaInput.value = placaInput.value.trim();
        if (modeloInput) modeloInput.value = modeloInput.value.trim();

        if (nombre && !validarNombreCompleto(nombre.value)) {
            alert('Nombre incorrecto. Debe contener al menos un nombre y un apellido, mínimo 2 letras cada uno, y máximo 4 palabras.');
            e.preventDefault();
            nombre.focus();
            return;
        }

        if (email && !validarEmail(email.value)) {
            alert('Correo no válido. Ejemplo: nombre@univalle.edu, mínimo 3 caracteres antes y después del @, y 2 o más en extensión.');
            e.preventDefault();
            email.focus();
            return;
        }

        if (telefono && telefono.value.trim() !== '' && !validarTelefonoBolivia(telefono.value.trim())) {
            alert('Teléfono inválido. Debe tener 8 dígitos, comenzar con 6 o 7 y contener solo números.');
            e.preventDefault();
            telefono.focus();
            return;
        }

        if (direccion && direccion.value !== '' && !validarDireccion(direccion.value)) {
            alert('Dirección inválida. Debe contener número de vivienda y nombre de calle (al menos dos palabras).');
            e.preventDefault();
            direccion.focus();
            return;
        }

        if (password && !validarPassword(password.value)) {
            alert('Contraseña inválida. Debe tener entre 8 y 15 caracteres, al menos una mayúscula y un número.');
            e.preventDefault();
            password.focus();
            return;
        }

        if (email && email.value.includes(' ') || (password && password.value.includes(' '))) {
            alert('El correo y la contraseña no pueden contener espacios.');
            e.preventDefault();
            return;
        }

        if (placaInput && !validarPlacaBolivia(placaInput.value)) {
            alert('Placa inválida. Debe tener 3 o 4 números seguidos de 3 letras. Ejemplo: 123ABC o 1234XYZ');
            e.preventDefault();
            placaInput.focus();
            return;
        }

        if (modeloInput && !validarModeloTaxi(modeloInput.value)) {
            alert('Modelo inválido. Debe contener entre 3 y 20 caracteres alfanuméricos.');
            e.preventDefault();
            modeloInput.focus();
            return;
        }
    });
}
