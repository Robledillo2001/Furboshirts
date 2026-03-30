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
        <h2><i class="fas fa-tags"></i>Editar Categoria</h2>
        <form action="index.php?action=EditarCategoria&id=<?= $_GET['id']; ?>" method="POST">
            <div class="input-group">
                <label for="prenda">Prenda</label>
                <input type="text" name="prenda" id="prenda">
            </div>

            <div class="input-group">
                <label for="desc">Descripcion</label>
                <textarea name="desc" id="desc"></textarea>
            </div>

            <div class="acciones-form">
                <button type="submit">Guardar</button>
                <a href="index.php?action=GestionCategorias">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>