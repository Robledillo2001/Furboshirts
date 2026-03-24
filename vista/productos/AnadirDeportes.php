<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="login-container">
    <div class="formulario">
        <?php if (isset($error)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <h2><i class="fas fa-solid fa-futbol"></i>Añadir Deporte</h2>
        <form action="index.php?action=AnadirDeporte" method="POST">
            <div class="input-group">
                <label for="desc">Nombre del Deporte</label>
                <input name="deporte" id="deporte" required>
            </div>

            <div class="acciones-form">
                <button type="submit">Guardar</button>
                <a href="index.php?action=GestionCategorias"><button class="btn-login">Cancelar</button></a>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>