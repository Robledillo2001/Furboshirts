<?php include __DIR__ . '/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
        ✅ Datos actualizados correctamente.
    </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'pass'): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
        ❌ Las contraseñas no coinciden. Inténtalo de nuevo.
    </div>
<?php endif; ?>

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