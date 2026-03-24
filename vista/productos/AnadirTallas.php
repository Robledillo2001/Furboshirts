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
        <h2><i class="fas fa-tags"></i>Añadir Categoria</h2>
        <form action="index.php?action=AnadirTallas" method="POST">
            <div class="input-group">
                <label for="talla">Talla</label>
                <input type="text" name="talla" id="talla" required>
            </div>

            <div class="acciones-form">
                <button type="submit">Guardar</button>
                <a href="index.php?action=GestionCategorias"><button type="submit">Cancelar</button></a>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>