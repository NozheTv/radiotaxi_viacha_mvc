/**
 * Descripción: Contiene todas las validaciones necesarias para formularios de Clientes y Conductores.
 * Incluye validaciones personalizadas para Bolivia, formatos específicos, y controles UX para evitar entradas inválidas.
 */


// Validar nombre completo Bolivia:
// - Letras solo incluyendo caracteres acentuados y Ñ
// - Al menos un nombre corto (mín 2 letras) y al menos un apellido (mín 2 letras)
// - Permite máximo 3 espacios intercalados (hasta 4 nombres o apellidos)
// - Ejemplos válidos: "Juan Pérez", "Ana María Soto"
function validarNombreCompleto(valor) {
    const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}( [A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}){1,3}$/;
    return regexNombre.test(valor.trim());
}


// Validar email con regla estándar (mín 3 caracteres antes y después del @ y dos o más para la extensión)
function validarEmail(valor) {
    const regexEmail = /^[a-zA-Z0-9._%+-]{3,}@[a-zA-Z0-9.-]{3,}\.[a-zA-Z]{2,}$/;
    return regexEmail.test(valor.trim());
}


// Validar teléfono Bolivia:
// - Exactamente 8 dígitos,
// - Comienza con 6,7,8 o 9
// - Solo números
function validarTelefonoBolivia(valor) {
    const regexTelefono = /^[67]\d{7}$/;
    return regexTelefono.test(valor);
}


// Validar dirección Bolivia:
// - Debe contener número de vivienda y nombre de calle (al menos dos palabras, una con número y otra de texto)
// - Ejemplo válido: "123 Calle Sucre"
function validarDireccion(valor) {
    // Busca por lo menos un número y al menos una palabra consecutiva en texto
    const regexDireccion = /^(?=.*\d+)(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ]+).{5,255}$/;
    return regexDireccion.test(valor.trim());
}


// Validar contraseña:
// - Entre 8 y 15 caracteres
// - Al menos una mayúscula
// - Al menos un número
// - Permite caracteres especiales
function validarPassword(valor) {
    const regexPassword = /^(?=.*[A-Z])(?=.*\d).{8,15}$/;
    return regexPassword.test(valor);
}


/**
 * Asignar eventos para bloquear espacios en email y password, y validar campos al enviar formulario.
 * @param {string} formId - ID del formulario
 * @param {string} nombreId - ID del input nombre
 * @param {string} emailId - ID del input email
 * @param {string} passwordId - ID del input password
 * @param {string} telefonoId - ID del input teléfono
 * @param {string} direccionId - ID del input dirección
 */
function setupValidacionesFormulario(formId, nombreId, emailId, passwordId, telefonoId, direccionId) {
    const form = document.getElementById(formId);
    const nombre = document.getElementById(nombreId);
    const email = document.getElementById(emailId);
    const password = document.getElementById(passwordId);
    const telefono = document.getElementById(telefonoId);
    const direccion = document.getElementById(direccionId);

    // Bloquear espacios en email y password (tanto en escritura como pegar)
    [email, password].forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === ' ') e.preventDefault();
        });
        input.addEventListener('paste', e => {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (paste.includes(' ')) e.preventDefault();
        });
    });

    form.addEventListener('submit', e => {
        // Trim valores básicos
        nombre.value = nombre.value.trim();
        email.value = email.value.trim();
        password.value = password.value.trim();
        direccion.value = direccion.value.trim();

        // Validar nombre completo
        if (!validarNombreCompleto(nombre.value)) {
            alert('Nombre incorrecto. Debe contener al menos un nombre y un apellido, mínimo 2 letras cada uno, y máximo 4 palabras.');
            e.preventDefault();
            nombre.focus();
            return;
        }

        // Validar email
        if (!validarEmail(email.value)) {
            alert('Correo no válido. Ejemplo: nombre@univalle.edu, mínimo 3 caracteres antes y después del @, y 2 o más en extensión.');
            e.preventDefault();
            email.focus();
            return;
        }

        // Validar teléfono (opcional)
        if (telefono.value.trim() !== '' && !validarTelefonoBolivia(telefono.value.trim())) {
            alert('Teléfono inválido. Debe tener 8 dígitos, comenzar con 6 o 7 y contener solo números.');
            e.preventDefault();
            telefono.focus();
            return;
        }

        // Validar dirección (opcional)
        if (direccion.value !== '' && !validarDireccion(direccion.value)) {
            alert('Dirección inválida. Debe contener número de vivienda y nombre de calle (al menos dos palabras).');
            e.preventDefault();
            direccion.focus();
            return;
        }

        // Validar contraseña
        if (!validarPassword(password.value)) {
            alert('Contraseña inválida. Debe tener entre 8 y 15 caracteres, al menos una mayúscula y un número.');
            e.preventDefault();
            password.focus();
            return;
        }

        // Bloquear envío si email o password contienen espacios (en medio)
        if (email.value.includes(' ') || password.value.includes(' ')) {
            alert('El correo y la contraseña no pueden contener espacios.');
            e.preventDefault();
            return;
        }
    });
}
