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
        <h2><i class="fas fa-trophy"></i>Eliminar Competicion</h2>
        <form action="index.php?action=EliminarCompeticiones" method="POST">
            <div class="input-group">
                <label for="comp">Competicion</label>
                <select name="comp" id="comp">
                    <?php foreach($competiciones as $comp) :?>
                        <option value="<?= $comp['ID_COMP'] ?>"><?= $comp['NOMBRE_COMP'] ?></option>
                    <?php endforeach;?>
                </select>
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

<?php 
include __DIR__ . '/../footer.php'; 
?>