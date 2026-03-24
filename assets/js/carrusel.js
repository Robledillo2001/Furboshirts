// Esperamos a que todo el DOM (HTML) esté cargado para evitar errores de "undefined"
document.addEventListener("DOMContentLoaded", () => {
    let slideIndex = 0;
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");

    // Función principal para mostrar el slide correcto
    function showSlides(n) {
        // Si el índice se pasa del final, vuelve al principio
        if (n >= slides.length) slideIndex = 0;
        // Si el índice es menor a cero, va al último
        if (n < 0) slideIndex = slides.length - 1;

        // Quitamos la clase 'active' de todas las imágenes y puntos
        slides.forEach(slide => slide.classList.remove("active"));
        dots.forEach(dot => dot.classList.remove("active"));

        // Activamos solo el que corresponde
        slides[slideIndex].classList.add("active");
        if (dots[slideIndex]) {
            dots[slideIndex].classList.add("active");
        }
    }

    // --- EVENTOS DE LOS BOTONES ---

    // Al hacer clic en la flecha Derecha
    nextBtn.addEventListener("click", () => {
        slideIndex++;
        showSlides(slideIndex);
    });

    // Al hacer clic en la flecha Izquierda
    prevBtn.addEventListener("click", () => {
        slideIndex--;
        showSlides(slideIndex);
    });

    // --- EVENTOS DE LOS PUNTOS (ÍNDICE) ---
    // Recorremos todos los puntos y les asignamos su función según su posición (i)
    dots.forEach((dot, i) => {
        dot.addEventListener("click", () => {
            slideIndex = i; // El índice ahora es el número del punto clicado
            showSlides(slideIndex);
        });
    });

    // --- CAMBIO AUTOMÁTICO DEL CARRUSEL QUE SE CAMBIA CADA 5 SEGUNDOS---
    setInterval(() => {
        slideIndex++;
        showSlides(slideIndex);
    }, 5000);
});