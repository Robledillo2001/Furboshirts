document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('contenedor-carrito');

    // 1. Validación de seguridad: Si no hay carrito en esta página, no ejecutar nada
    if (!contenedor) return; 

    let itemArrastrado = null;

    contenedor.addEventListener('dragstart', (e) => {
        itemArrastrado = e.target.closest('.producto-linea');
        if (itemArrastrado) {
            e.target.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
        }
    });

    contenedor.addEventListener('dragend', (e) => {
        e.target.style.opacity = '1';
        document.querySelectorAll('.producto-linea').forEach(el => el.classList.remove('drag-over'));
    });

    contenedor.addEventListener('dragover', (e) => {
        e.preventDefault(); 
        const target = e.target.closest('.producto-linea');
        if (target && target !== itemArrastrado) {
            target.classList.add('drag-over');
        }
    });

    contenedor.addEventListener('dragleave', (e) => {
        const target = e.target.closest('.producto-linea');
        if (target) target.classList.remove('drag-over');
    });

    contenedor.addEventListener('drop', (e) => {
        e.preventDefault();
        const target = e.target.closest('.producto-linea');

        if (target && target !== itemArrastrado) {
            // CORRECCIÓN AQUÍ: Definimos 'todos' para que coincida con el uso posterior
            const todos = [...contenedor.querySelectorAll('.producto-linea')]; 
            
            const indiceArrastrado = todos.indexOf(itemArrastrado);
            const indiceTarget = todos.indexOf(target);

            // Intercambio visual en el DOM
            if (indiceArrastrado < indiceTarget) {
                target.after(itemArrastrado);
            } else {
                target.before(itemArrastrado);
            }
        }
    });
});