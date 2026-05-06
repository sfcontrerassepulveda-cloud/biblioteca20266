document.addEventListener("DOMContentLoaded", () => {
    // Saludo en consola
    console.log("Bienvenido a la Biblioteca Virgilio Barco");

    // Efecto al pasar el mouse por los títulos
    const titulos = document.querySelectorAll("h1, h2");
    titulos.forEach(titulo => {
        titulo.addEventListener("mouseenter", () => {
            titulo.style.color = "#ff6600";
        });
        titulo.addEventListener("mouseleave", () => {
            titulo.style.color = "#0a3d62";
        });
    });
});
