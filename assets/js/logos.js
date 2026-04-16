function actualizarVistaPrevia(selectElement) {
    const container = document.getElementById('preview-logo-container');
    const img = document.getElementById('img-preview-logo');
        
    // Obtenemos la opción seleccionada
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const rutaImagen = selectedOption.getAttribute('data-img');

    // Verificamos que exista la ruta y que no sea la opción por defecto ("todos")
    if (rutaImagen && selectElement.value !== "todos") {
        // Si tus imágenes están en una carpeta específica, añádela aquí, ej: 'assets/parches/' + rutaImagen
        img.src = rutaImagen; 
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        img.src = "";
    }
}


function ParcheEspecial() {//Funcion para mostrar la opcion de poner un parche especial si la situacion se requiere
    const checkbox = document.getElementById('activar_parche');
    const contenedor = document.getElementById('parche_especial');
    const inputImagen = document.getElementById('imagen');
    if (checkbox.checked) {
        contenedor.style.display = 'block';
        inputImagen.required = true; // Obligamos a subirlo si el div está activo
    } else {
        contenedor.style.display = 'none';
        inputImagen.required = false;
        inputImagen.value = ""; // Limpiamos el archivo seleccionado si se arrepiente
    }
}