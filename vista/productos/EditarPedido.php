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
        <h2><i class="fas fa-tags"></i>Editar Pedido</h2>
        <form action="index.php?action=EditarPedidos&id=<?=$_GET['id']; ?>" method="POST">
            <div class="input-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado">
                    <option value="Entregado" <?= $pedido['ESTADO'] == 'Entregado' ? 'selected' : ''?>>Entregado</option>
                    <option value="Pendiente" <?= $pedido['ESTADO'] == 'Pendiente' ? 'selected' : ''?>>Pendiente</option>
                    <option value="Cancelado" <?= $pedido['ESTADO'] == 'Cancelado' ? 'selected' : ''?>>Cancelado</option>
                </select>
            </div>

            <div class="acciones-form">
                <button type="submit">Guardar</button>
                <a href="index.php?action=GestionPedidos">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>