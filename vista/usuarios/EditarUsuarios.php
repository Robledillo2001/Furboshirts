<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="registro-container">
    <div class="formulario">
        <h2>Editar Usuario</h2>
        <form action="index.php?action=EditarUsuario&id=<?= $_GET['id'] ?>&from<?=$_GET['from'] ?>" method="POST">
            <div class="input-group">
                <label for="rol">Rol del Usuario</label>
                <select name="rol" id="rol">
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn-login">EDITAR DATOS</button>
        </form>
        <a href="index.php?action=EditarUsuario">Volver a Inicio</a></b>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>