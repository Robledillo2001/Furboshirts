<?php include __DIR__ . '/header.php'; ?> 

<div class="container">
    <h1>CONFIGURACIÓN DE CUENTA</h1>
    <p>Gestiona tus datos, <?= $_SESSION['nombre'] ?></p>

    <div class="enlaces">
        <div class="enlace">
            <a href="index.php?action=EditarPerfil">
                <i class="fas fa-user-edit" ></i>
                <h3>Editar Datos Perfil</h3>
            </a>
        </div>

        <div class="enlace">
            <a href="index.php?action=CambiarFoto">
                <h3>Cambiar Foto de Perfil</h3>
            </a>
        </div>

        <div class="enlace">
            <?php if ($_SESSION['ROL'] === 'admin'): ?>
                <a href="index.php?action=MenuAdmin">
                    <i class="fas fa-arrow-left"></i>
                    <h3>Volver al Panel</h3>
                </a>
            <?php else: ?>
                <a href="index.php?action=inicio">
                    <i class="fas fa-arrow-left"></i>
                    <h3>Volver al Inicio</h3>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>