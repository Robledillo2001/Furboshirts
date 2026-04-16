<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="login-container">
    <div class="redireccion-form">
        <a href="index.php?action=AnadirCompeticiones" class="formulario-link">« Añadir Competicion</a>
    </div>
    <div class="formulario">
        <?php if (isset($error)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <h2><i class="fas fa-tag"></i>Añadir Logo</h2>
        <form action="index.php?action=AnadirLogos" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="imagen">Escudo / Logo del Equipo:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
                <small style="color:#f4f4f4;">Formatos permitidos: JPG, PNG, WEBP.</small>
            </div>

            <div class="btn">
                <div class="acciones-form">
                    <button type="submit" class="btn-login">Guardar</button>
                    <a href="index.php?action=GestionTemporadas">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="redireccion-form">
        <a href="index.php?action=AsignarEquipos" class="formulario-link">Asignar Equipo »</a>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>