// Validación sencilla antes de enviar el formulario
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
        const correo = document.getElementById("correo").value.trim();
        const contrasena = document.getElementById("contrasena").value.trim();

        if (correo === "" || contrasena === "") {
            alert("Por favor, completa todos los campos.");
            e.preventDefault();
        }
    });
});
