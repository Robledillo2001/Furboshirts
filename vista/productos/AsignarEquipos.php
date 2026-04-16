<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="login-container">
    <div class="redireccion-form">
        <a href="index.php?action=AnadirLogos" class="formulario-link">« Añadir Logos</a>
    </div>
    <div class="formulario">
        <?php if (isset($error)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <h2><i class="fas fa-handshake"></i>Asignación de Temporada</h2>
        <p style="color: #f4f4f4; text-align: center; margin-bottom: 20px;">Vincula competiciones, equipos y sus parches específicos.</p>

        <form action="index.php?action=AsignarEquipos" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="id_competicion">Seleccionar Competición:</label>
                <select id="id_competicion" name="id_competicion" required>
                    <option value="">-- Selecciona una competición --</option>
                    <?php foreach ($competiciones as $comp): ?>
                        <option value="<?php echo $comp['ID_COMP']; ?>"><?php echo $comp['NOMBRE_COMP']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label for="id_equipo">Equipo</label>
                <select id="id_equipo" name="id_equipo">
                    <option value="todos">-- Selecciona Equipo</option>
                    <?php foreach ($equipos as $equipo): ?>
                        <option value="<?php echo $equipo['ID_EQUIPO']; ?>"><?php echo $equipo['NOMBRE_EQUIPO']; ?></option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#ccc;">Selecciona un equipo solo si lleva un parche especial (Ej: Real Madrid).</small>
            </div>

            <div class="input-group">
                <label for="id_logo">LOGO</label>
                <select id="id_logo" name="id_logo" onchange="actualizarVistaPrevia(this)">
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
            
            <div class="input-group">
                <label for="anio_edicion">Temporada / Año:</label>
                <input type="number" id="anio_edicion" name="anio_edicion" placeholder="Ej: 2025" required>
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
                    <a href="index.php?action=GestionTemporadas"><button class="btn-login">Cancelar</button></a>
                </div>
            </div>
        </form>
    </div>
    <div class="redireccion-form">
        <a href="index.php?action=GestionTemporadas" class="formulario-link">Temporadas »</a>
    </div>
</div>

<script src="assets/js/logos.js"></script>

<?php 
include __DIR__ . '/../footer.php'; 
?>