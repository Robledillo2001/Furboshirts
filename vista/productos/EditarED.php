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
        <h2><i class="fas fa-shield-alt"></i>Editar ED</h2>
        <form action="index.php?action=EditarED&id=<?= $_GET['id'] ?? '' ?>&from=<?= $_GET['from'] ?? 'GestionEquipos' ?>" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nombre_equipo">NOMBRE</label>
                <input type="text" name="nombre_equipo" id="nombre_equipo" value="<?=$ED['NOMBRE_EQUIPO']  ?>">
            </div>

            <div class="input-group">
                <label for="tipo">TIPO</label> <select name="tipo" id="tipo">
                    <option value="Equipo" <?= $ED['TIPO'] == 'Equipo' ? 'selected' : '' ?>>Equipo</option>
                    <option value="Seleccion" <?= $ED['TIPO'] == 'Seleccion' ? 'selected' : '' ?>>Selección</option>
                </select>
            </div>  

            <div class="input-group">
                <label for="imagen">Escudo / Logo de la ED:</label>
                <?php if (isset($ED['ESCUDO'])): ?>
                    <img src="<?= $ED['ESCUDO'] ?>" alt="Logo actual" style="width: 50px; display: block; margin-bottom: 10px;">
                <?php endif; ?>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small style="color:#f4f4f4;">(Opcional) Formatos: JPG, PNG, WEBP.</small>
            </div>

            <div class="acciones-form">
                <button type="submit">Guardar</button>
                <a href="index.php?action=<?= $_GET['from'] ?? 'GestionEquipos' ?>" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>