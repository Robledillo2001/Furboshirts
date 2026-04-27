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

        <h2>Editar Año de Temporada</h2>
        <p style="color: #f4f4f4; text-align: center; margin-bottom: 20px;">Editar Temporada</p>

       <form action="index.php?action=EditarTemporada&id_comp=<?=$_GET['id_comp']?>&id_logo=<?=$_GET['id_logo']?>&id_equipo=<?=$_GET['id_equipo']?>" 
       method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="id_logo">LOGO</label>
                <select id="id_logo" name="id_logo" onchange="actualizarVistaPrevia(this)">
                    <option value="todos">-- Selecciona Logo</option>
                    <?php foreach ($logos as $logo): ?>
                        <option value="<?= $logo['ID_LOGO']; ?>" data-img="<?= $logo['PARCHE']; ?>"
                            <?= $logo['ID_LOGO'] == $temporada['ID_LOGO'] ? 'selected' : '' ?>>
                            Parche #<?= $logo['ID_LOGO']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="preview-logo-container" style="margin-top: 10px; display: none; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px;">
                    <p style="font-size: 0.8rem; color: #ccc; margin-bottom: 5px;">Vista previa del parche:</p>
                    <img id="img-preview-logo" src="" alt="Vista previa" style="max-width: 60px; height: auto; border: 1px solid #fff3; border-radius: 4px;">
                </div>
            </div>
            
            <div class="input-group">
                <label for="anio_edicion">Temporada / Año:</label>
                <input type="number" id="anio_edicion" name="anio_edicion" placeholder="Ej: 2025" value="<?= $temporada['ANO_EDICION'] ?>" required>
            </div>

             <div class="checkbox-group">
                <input type="checkbox" id="activar_parche" name="activar_parche" onchange="ParcheEspecial()">
                <label for="activar_parche">¿Este equipo usa un parche especial diferente al estándar?</label>
            </div>


            <div id="parche_especial" style="display: none; border: 1px dashed #f4f4f4; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <div class="input-group">
                    <label for="imagen"><strong>Subir Parche Exclusivo:</strong></label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <small style="color:#f4f4f4;">Usa esta opción solo si el parche es único para este equipo y/o temporada.</small>
                </div>
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
<script src="assets/js/logos.js"></script>
<?php 
include __DIR__ . '/../footer.php'; 
?>