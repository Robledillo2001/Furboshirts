document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('contenedor-carrito');

    // 1. Validación de seguridad: Si no hay carrito en esta página, no ejecutar nada
    if (!contenedor) return; 

    let itemArrastrado = null;

    contenedor.addEventListener('dragstart', (e) => {
        itemArrastrado = e.target.closest('.cart-item');
        if (itemArrastrado) {
            e.target.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
        }
    });

    contenedor.addEventListener('dragend', (e) => {
        e.target.style.opacity = '1';
        document.querySelectorAll('.cart-item').forEach(el => el.classList.remove('drag-over'));
    });

    contenedor.addEventListener('dragover', (e) => {
        e.preventDefault(); 
        const target = e.target.closest('.cart-item');
        if (target && target !== itemArrastrado) {
            target.classList.add('drag-over');
        }
    });

    contenedor.addEventListener('dragleave', (e) => {
        const target = e.target.closest('.cart-item');
        if (target) target.classList.remove('drag-over');
    });

    contenedor.addEventListener('drop', (e) => {
        e.preventDefault();
        const target = e.target.closest('.cart-item');

        if (target && target !== itemArrastrado) {
            // CORRECCIÓN AQUÍ: Definimos 'todos' para que coincida con el uso posterior
            const todos = [...contenedor.querySelectorAll('.cart-item')]; 
            
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

// Funcion para cambiar la cantidad de productos en el carrito
function actualizarCantidad(indice, cantidad) {
    const nCantidad = parseInt(cantidad);
    
    if (nCantidad >= 1 && nCantidad <= 99) {
        // Redirigimos a la acción del controlador pasándole los parámetros por URL
        window.location.href = `index.php?action=actualizarCantidad&indice=${indice}&cantidad=${nCantidad}`;
    } else {
        alert("La cantidad debe estar entre 1 y 99");
        location.reload(); // Recargamos para restaurar el valor anterior
    }
}

function abrirModal() {//Metodo para abrir el modala para confirmar el pedido
    const direccion = document.getElementById('direccion-api').value.trim();
    if (!direccion) {
        document.getElementById('direccion-api').focus();
        document.getElementById('direccion-api').style.borderColor = '#e53e3e';
        document.getElementById('direccion-api').style.boxShadow = '0 0 0 3px rgba(229,62,62,0.18)';
        return;
    }
    const pago = document.querySelector('input[name="metodo_pago"]:checked').value;
    document.getElementById('modal-direccion').textContent = direccion;
    document.getElementById('modal-pago').textContent = pago;
    document.getElementById('modal-confirm').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {//Metodo que cierra el modal por si el cliente quiere comprobar los datos del pedido
    document.getElementById('modal-confirm').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('direccion-api').addEventListener('input', function() {
    this.style.borderColor = '';
    this.style.boxShadow = '';
});

document.getElementById('modal-confirm').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// Variable global para el mapa y el marcador
let map = null;
let marker = null;

//Metodo asyncrono para mostrar la ubicacion 
async function validarDireccion() {
    const input = document.getElementById('direccion-api');
    const status = document.getElementById('status-ubicacion');
    const mapDiv = document.getElementById('map'); // El contenedor del mapa
    const query = input.value.trim();

    if (query.length < 5) {
        alert("Por favor, escribe una dirección más específica.");
        return;
    }

    status.style.display = "block";
    status.style.color = "orange";
    status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando ubicación...';

    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;

    try {
        const response = await fetch(url, {
            headers: { 'User-Agent': 'Furboshirts-App' }
        });
        const data = await response.json();

        if (data.length > 0) {
            const lat = data[0].lat;
            const lon = data[0].lon;

            input.value = data[0].display_name;
            status.style.color = "green";
            status.innerHTML = '<i class="fas fa-check"></i> Ubicación encontrada y validada.';

            // --- LÓGICA DEL MAPA ---
            mapDiv.style.display = "block"; // Mostramos el div del mapa

            if (map === null) {
                // Si el mapa no existe, lo creamos
                map = L.map('map').setView([lat, lon], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                marker = L.marker([lat, lon]).addTo(map);
            } else {
                // Si ya existe, solo movemos la vista y el marcador
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]);
            }
            
            // Forzar a Leaflet a recalcular el tamaño (evita que el mapa salga gris)
            setTimeout(() => { map.invalidateSize(); }, 200);

        } else {
            status.style.color = "red";
            status.innerHTML = '<i class="fas fa-times"></i> No se encontró la ubicación.';
            mapDiv.style.display = "none";
        }
    } catch (error) {
        status.style.color = "red";
        status.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error de conexión.';
    }
}

