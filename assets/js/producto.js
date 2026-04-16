document.addEventListener("DOMContentLoaded", function() {
    // Cambiar imagen principal
    const mainImg = document.getElementById('imgGrande');
    const thumbnails = document.querySelectorAll('.img-thumbnail-custom');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            mainImg.src = this.src;
        });
    });

    // Forzar mayúsculas en el nombre
    const inputNombre = document.querySelector('input[name="nombre_personalizado"]');
    if(inputNombre) {
        inputNombre.addEventListener('keyup', function() {
            this.value = this.value.toUpperCase();
        });
    }
});