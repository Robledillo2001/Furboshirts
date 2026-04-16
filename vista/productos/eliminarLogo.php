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

        <h2><i class="fas fa-tag"></i>Eliminar Logo</h2>
        <p style="color: #f4f4f4; text-align: center; margin-bottom: 20px;">Eliminar Parche</p>

       <form action="index.php?action=EliminarLogos" method="post">
            <div class="input-group">
                <label for="id_logo">LOGO</label>
                <select id="id_logo" name="logo" onchange="actualizarVistaPrevia(this)">
                    <option value="todos">-- Selecciona Logo</option>
                    <?php foreach ($logos as $logo): ?>
                        <option value="<?= $logo['ID_LOGO']; ?>" data-img="<?= $logo['PARCHE']; ?>">
                            Parche #<?= $logo['ID_LOGO']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="preview-logo-container" style="margin-top: 10px; display: none; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px;">
                    <p style="font-size: 0.8rem; color: #ccc; margin-bottom: 5px;">Vista previa del parche:</p>
                    <img id="img-preview-logo" src="" alt="Vista previa" style="max-width: 60px; height: auto; border: 1px solid #fff3; border-radius: 4px;">
                </div>
            </div>

            <div class="btn">
                <div class="acciones-form">
                    <button type="submit" class="btn-login">Eliminar</button>
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