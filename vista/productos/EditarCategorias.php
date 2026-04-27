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
                <input type="text" name="prenda" id="prenda" value="<?= $categoria['PRENDA'] ?>">
            </div>

            <div class="input-group">
                <label for="desc">Descripcion</label>
                <textarea name="desc" id="desc" ><?= $categoria['DESCRIPCION'] ?></textarea>
            </div>

            <div class="input-group">
                <label for="deporte">Asignar Deporte</label>
                <?php
                    $asignadosSeguros = [];
                    if (isset($deportesAsignados) && is_array($deportesAsignados)) {
                        foreach ($deportesAsignados as $elemento) {
                            // Si el elemento es un array (ej: ['ID_DEPORTE' => 1]), extraemos el valor
                            if (is_array($elemento)) {
                                $asignadosSeguros[] = (string)reset($elemento); 
                            } else {
                                // Si ya es un valor simple
                                $asignadosSeguros[] = (string)$elemento;
                            }
                        }
                    }
                ?>
                
                <?php foreach($deportes as $d): ?>
                    <?php 
                        $idActual = (string)$d['ID_DEPORTE'];
                        $marcado = in_array($idActual, $asignadosSeguros) ? 'checked' : '';//Mostrara los deportes ya marcados si ya esta la categoria asociada a un deporte
                    ?>
                    <div class="checkbox-group">
                        <input type="checkbox" name="deporte[]" value="<?= $d['ID_DEPORTE'] ?>" id="dep_<?= $d['ID_DEPORTE'] ?>" <?= $marcado ?>>
                        <label for="dep_<?= $d['ID_DEPORTE'] ?>"><?= $d['DEPORTE'] ?></label>
                    </div>
                <?php endforeach; ?>
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