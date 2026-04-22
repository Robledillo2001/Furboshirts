<?php include __DIR__ . '/header.php'; ?>

<script src="assets/js/carrusel.js"></script>

<!-- Hero Carousel -->
<section class="carousel">
    <div class="carousel-container">
        <div class="slide active">
            <img src="assets/img/banner1.png" alt="Nueva Temporada LaLiga">
            <div class="slide-content">
                <h2>Temporada 2026</h2>
                <p>Las mejores camisetas de todos los equipos y competiciones disponibles</p>
                <a href="index.php?action=mostrarCatalogo" class="btn-shop">
                    <i class="fas fa-tshirt"></i> Explorar
                </a>
            </div>
        </div>

        <div class="slide">
            <img src="assets/img/banner2.png" alt="Selecciones Nacionales">
            <div class="slide-content">
                <h2>Orgullo Nacional</h2>
                <p>Equípate para el próximo mundial con tu selección.</p>
                <a href="index.php?action=mostrarCatalogo&tipo=Seleccion" class="btn-shop">
                    <i class="fas fa-globe-europe"></i> Selecciones
                </a>
            </div>
        </div>

        <div class="slide">
            <img src="assets/img/banner3.png" alt="Personalización">
            <div class="slide-content">
                <h2>Personalización</h2>
                <p>Nombre, dorsal y parche de competición a tu elección.</p>
                <a href="index.php?action=mostrarCatalogo&tipo=Equipo" class="btn-shop">
                    <i class="fas fa-pen"></i> Personalizar
                </a>
            </div>
        </div>

        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div>

    <div class="dots-container">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</section>

<!-- Service Banner -->
<section class="servicios-container">
    <div class="servicio-card">
        <i class="fas fa-headset"></i>
        <div class="servicio-info">
            <h3>Atención al Cliente</h3>
            <p>Lun-Vie: 9-21h | Sáb: 9-13h</p>
        </div>
    </div>
    <div class="servicio-card">
        <i class="fas fa-tshirt"></i>
        <div class="servicio-info">
            <h3>Personalización</h3>
            <p>Camisetas y parches a tu gusto</p>
        </div>
    </div>
    <div class="servicio-card">
        <i class="fas fa-truck"></i>
        <div class="servicio-info">
            <h3>Envíos Rápidos</h3>
            <p>Entrega en 2-3 días naturales</p>
        </div>
    </div>
    <div class="servicio-card">
        <i class="fas fa-shield-alt"></i>
        <div class="servicio-info">
            <h3>Pagos Seguros</h3>
            <p>Garantía de seguridad 100%</p>
        </div>
    </div>
</section>

<!-- Promo Banner -->
<section class="promo-banner">
    <div class="promo-banner-content">
        <span class="promo-badge"><i class="fas fa-bolt"></i> Temporada 2025/26</span>
        <h2>Colecciones Oficiales</h2>
        <p>Descubre las últimas incorporaciones: camisetas de La Liga, Champions League, Premier League y mucho más. Envío gratis en todos los pedidos.</p>
        <a href="index.php?action=mostrarCatalogo" class="btn-promo">
            <i class="fas fa-arrow-right"></i> Ver catálogo completo
        </a>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
