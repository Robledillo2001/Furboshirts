<?php include __DIR__ . '/header.php'; ?>

<div class="admin-dashboard">

    <!-- Hero Banner -->
    <div class="admin-hero">
        <div class="admin-hero-left">
            <div class="admin-hero-avatar-wrap">
                <img src="<?= $_SESSION['IMAGEN'] ?? 'assets/img/user.png' ?>" class="admin-hero-avatar" alt="Avatar">
                <span class="admin-hero-online"></span>
            </div>
            <div class="admin-hero-text">
                <p class="admin-greeting">Bienvenido de vuelta</p>
                <h1 class="admin-name"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?></h1>
                <span class="admin-role-badge"><i class="fas fa-shield-alt"></i> Administrador</span>
            </div>
        </div>
        <div class="admin-hero-right">
            <div class="admin-date-badge">
                <i class="far fa-calendar-alt"></i>
                <span><?= date('d/m/Y') ?></span>
            </div>
            <a href="index.php?action=AnadirProducto" class="admin-hero-cta">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Panel Title -->
    <div class="admin-section-header">
        <div>
            <h2 class="admin-section-title">Panel de Control</h2>
            <p class="admin-section-subtitle">Gestiona todos los aspectos de tu tienda desde aquí</p>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="admin-cards-grid">

        <a href="index.php?action=GestionProductos" class="admin-card" data-color="green">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-tshirt"></i>
            </div>
            <div class="admin-card-body">
                <h3>Productos</h3>
                <p>Gestiona catálogo, stock e imágenes</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=AnadirProducto" class="admin-card" data-color="emerald">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="admin-card-body">
                <h3>Añadir Producto</h3>
                <p>Agrega nuevas camisetas al catálogo</p>
            </div>
            <div class="admin-card-footer">
                <span>Crear</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionCategorias" class="admin-card" data-color="blue">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-tags"></i>
            </div>
            <div class="admin-card-body">
                <h3>Categorías</h3>
                <p>Tipos de prenda y clasificaciones</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionEquipos" class="admin-card" data-color="orange">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="admin-card-body">
                <h3>Equipos</h3>
                <p>Clubs y entidades deportivas</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionSelecciones" class="admin-card" data-color="purple">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-globe-europe"></i>
            </div>
            <div class="admin-card-body">
                <h3>Selecciones</h3>
                <p>Selecciones nacionales e internacionales</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionTemporadas" class="admin-card" data-color="teal">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="admin-card-body">
                <h3>Temporadas</h3>
                <p>Competiciones, logos y parches oficiales</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionTallas" class="admin-card" data-color="pink">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-ruler-horizontal"></i>
            </div>
            <div class="admin-card-body">
                <h3>Tallas</h3>
                <p>Administra las tallas disponibles</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="index.php?action=GestionPedidos" class="admin-card" data-color="indigo">
            <div class="admin-card-icon-wrap">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="admin-card-body">
                <h3>Pedidos</h3>
                <p>Historial y seguimiento de pedidos</p>
            </div>
            <div class="admin-card-footer">
                <span>Ver todo</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

    </div>

    <!-- Quick Links -->
    <div class="admin-quick-links">
        <a href="index.php?action=GestionAdmin" class="admin-quick-link">
            <i class="fas fa-users-cog"></i> Gestionar Usuarios
        </a>
        <a href="index.php?action=configuracion" class="admin-quick-link">
            <i class="fas fa-cog"></i> Configuración
        </a>
        <a href="index.php?action=mostrarCatalogo" class="admin-quick-link" target="_blank">
            <i class="fas fa-store"></i> Ver Tienda
        </a>
    </div>

</div>

<?php include __DIR__ . '/footer.php'; ?>
