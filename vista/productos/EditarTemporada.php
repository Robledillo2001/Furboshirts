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
        <p style="color: #f4f4f4; text-align: center; margin-bottom: 20px;">Editar Año de la competicion</p>

       <form action="index.php?action=EditarTemporada&id_comp=<?=$_GET['id_comp']?>&id_logo=<?=$_GET['id_logo']?>&id_equipo=<?=$_GET['id_equipo']?>" method="POST">
            <div class="input-group">
                <label for="anio_edicion">Temporada / Año:</label>
                <input type="number" id="anio_edicion" name="anio_edicion" placeholder="Ej: 2025" required>
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