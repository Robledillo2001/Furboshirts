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
        <h2><i class="fas fa-trophys"></i>Editar Competicion</h2>
        <form action="index.php?action=EditarCompeticiones&id=<?=$_GET['id']; ?>" method="POST">
            <div class="input-group">
                <label for="comp">Competicion</label>
                <input type="text" name="comp" id="comp" value="<?= $competicion['NOMBRE_COMP']?>"  required >
            </div>

            <div class="input-group">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo">
                    <option value="nacional" <?= $competicion['TIPO_COMP'] == 'nacional' ? 'selected' : '' ?>>Nacional</option>
                    <option value="intercontinental" <?= $competicion['TIPO_COMP'] == 'intercontinental' ? 'selected' : '' ?>>Intercontinental</option>
                    <option value="seleccion" <?= $competicion['TIPO_COMP'] == 'seleccion' ? 'selected' : '' ?>>Selecciones</option>
                </select>
            </div>

             <div class="btn">
                <div class="acciones-form">
                    <button type="submit" class="btn-login">Guardar</button>
                    <a href="index.php?action=GestionTemporadas">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>