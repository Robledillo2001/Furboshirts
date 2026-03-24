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

            <input type="hidden" name="id_logo" id="id_logo"><!--Se pone un input oculto para poner el mismo ID de la competicion a su respectivo parche-->

            <div class="input-group">
                <label for="anio_edicion">Temporada / Año:</label>
                <input type="number" id="anio_edicion" name="anio_edicion" placeholder="Ej: 2025" required>
            </div>

            <div class="input-group">
                <label for="imagen"><strong>¿Parche Especial?</strong> (Solo para casos específicos):</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small style="color:#f4f4f4;">Sube la imagen solo si este equipo usa un parche distinto al estándar.</small>
            </div>

            <div class="btn">
                <div class="acciones-form">
                    <button type="submit" class="btn-login">Guardar</button>
                    <a href="index.php?action=GestionTemporadas"><button class="btn-login">Cancelar</button></a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Script para que al asignar una competicion a un equipo se asigne un parche de la misma ya que tendran el mismo ID al añadirlos
    document.getElementById('id_competicion').addEventListener('change', function() {
        // Al cambiar la competición, asignamos su ID al campo oculto del logo
        document.getElementById('id_logo').value = this.value;
        console.log("Logo asignado automáticamente con ID: " + this.value);
    });
</script>

<?php 
include __DIR__ . '/../footer.php'; 
?>