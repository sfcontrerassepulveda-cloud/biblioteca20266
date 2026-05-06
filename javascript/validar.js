function validarFormulario() {
    //const form = document.forms["registro"];
    const nombre = document.getElementById("nombre");
    const apellido1 = document.getElementById("apellido1");
    const apellido2 = document.getElementById("apellido2");
    const correo = document.getElementById("correo");
    const contrasena = document.getElementById("contrasena");
    const repetir = document.getElementById("repetir_contrasena");
    const identificacion = document.getElementById("identificacion").value;
    const telefono = document.getElementById("telefono").value;
   

    //const telefono = form["telefono"];

    limpiarMensajes();

    let esValido = true;
    const correoValido = /^[^@]+@[^@]+\.(com)$/;
    const contrasenaValida = /^[a-zA-Z0-9.]+$/;
    const soloNumeros = /^[0-9]+$/;
  console.log("entra a validar.js")
    if (!nombre.value.trim()) {
        mostrarError(nombre, "El nombre es obligatorio.");
        esValido = false;
         ayuda.classList.remove("azul");
        ayuda.classList.add("rojo");
    }

    if (!apellido1.value.trim()) {
        mostrarError(apellido1, "El primer apellido es obligatorio.");
        esValido = false;
    }

    if (!apellido2.value.trim()) {
        mostrarError(apellido2, "El segundo apellido es obligatorio.");
        esValido = false;
    }

    if (!correo.value.trim()) {
        mostrarError(correo, "El correo es obligatorio.");
        esValido = false;
    } else if (!correoValido.test(correo.value)) {
        mostrarError(correo, "Correo inválido. Debe terminar en .com");
        esValido = false;
    }

    if (!contrasena.value) {
        mostrarError(contrasena, "La contraseña es obligatoria.");
        esValido = false;
    } else if (!contrasenaValida.test(contrasena.value)) {
        mostrarError(contrasena, "Solo letras, números y puntos.");
        esValido = false;
    } else if (contrasena.value.length < 5 || contrasena.value.length > 10) {
        mostrarError(contrasena, "Debe tener entre 5 y 10 caracteres.");
        esValido = false;
    }

    if (!repetir.value) {
        mostrarError(repetir, "Debe repetir la contraseña.");
        esValido = false;
    } else if (contrasena.value !== repetir.value) {
        mostrarError(repetir, "Las contraseñas no coinciden.");
        esValido = false;
    }

    const soloDiezNumeros = /^\d{10}$/;

if (!identificacion.value.trim()) {
    mostrarError(identificacion, "La identificación es obligatoria.");
    esValido = false;
} else if (!soloDiezNumeros.test(identificacion.value)) {
    mostrarError(identificacion, "La identificación debe tener exactamente 10 números.");
    esValido = false;
}

 

if (!telefono) {
    mostrarError(telefono, "El teléfono es obligatorio.");
    esValido = false;
    console.log("primera validacion");
    event.preventDefault();
} else if (!soloDiezNumeros.test(telefono)) {
    mostrarError(telefono, "Debe tener exactamente 10 dígitos numéricos.");
    esValido = false;
    console.log("segunda validacion");
    event.preventDefault();
}


    return esValido;
}

function mostrarError(input, mensaje) {
    const ayuda = input.nextElementSibling;
     ayuda.classList.add("azul");
    if (ayuda && ayuda.classList.contains("ayuda")) {
        ayuda.textContent = mensaje;
        ayuda.classList.remove("azul");
        ayuda.classList.add("rojo");
    }
}

function limpiarMensajes() {
    const ayudas = document.querySelectorAll(".ayuda");
    ayudas.forEach(div => {
        div.textContent = "";
        div.classList.remove("rojo", "azul");
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const campos = document.querySelectorAll("input");

    campos.forEach(campo => {
        // Bloquear espacios al escribir
        campo.addEventListener("keydown", (e) => {
            if (e.key === " ") {
                e.preventDefault();
            }

            // Bloquear letras en identificacion y telefono
            if ((campo.name === "identificacion" || campo.name === "telefono") && 
                !/[0-9]/.test(e.key) &&
                !["Backspace", "ArrowLeft", "ArrowRight", "Tab"].includes(e.key)) {
                e.preventDefault();
            }
        });

        // Eliminar espacios pegados
        campo.addEventListener("input", () => {
            campo.value = campo.value.replace(/\s/g, "");

            // Eliminar todo lo que no sea número en identificacion y telefono
            if (campo.name === "identificacion" || campo.name === "telefono") {
                campo.value = campo.value.replace(/\D/g, "");
            }
        });

        // Mostrar ayuda al enfocar
        campo.addEventListener("focus", () => {
    const mensaje = campo.getAttribute("data-ayuda");
    const ayuda = campo.nextElementSibling;
    if (ayuda && ayuda.classList.contains("ayuda")) {
        ayuda.textContent = mensaje;
        ayuda.classList.remove("rojo");
        ayuda.classList.add("azul");
    }
});

        // Limpiar ayuda si no hay error
        campo.addEventListener("blur", () => {
            const ayuda = campo.nextElementSibling;
            if (ayuda && ayuda.classList.contains("ayuda") && !ayuda.classList.contains("rojo")) {
                ayuda.textContent = "";
                ayuda.classList.remove("azul");
            }
        });
    });
});
