<?php include __DIR__ . '/header.php'; ?> 

<div class="container">
    <h1>PANEL DE ADMINISTRADOR</h1>
    <p>Sesion iniciada <?= $_SESSION['nombre'] ?></p>
    <div class="enlaces">
        <div class="enlace">
            <a href="index.php?action=GestionProductos">
                <h3>Gestionar Productos</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionCategorias">
                <h3>Gestionar Categorias</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionEquipos">
                <h3>Gestionar Equipos</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionSelecciones">
                <h3>Gestionar Selecciones</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionTemporadas">
                <h3>Gestionar Comp/Logos</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionTallas">
                <h3>Gestionar Tallas</h3>
            </a>
        </div>
        <div class="enlace">
            <a href="index.php?action=GestionPedidos">
                <h3>Historial de pedidos</h3>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?> 