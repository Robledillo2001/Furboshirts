<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="registro-container">
    <div class="formulario">
        <h2>Añadir Administradores</h2>
        <form action="index.php?action=AnadirAdmin" method="POST">
            <div class="input-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" required>
            </div>

            <div class="input-group">
                <label for="apellidos">Apellidos</label>
                <input type="apellidos" name="apellidos" id="apellidos" required>
            </div>

            <div class="input-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" required>
            </div>

            <div class="input-group">
                <label for="nombreUser">Nombre de Usuario</label>
                <input type="text" name="nombreUser" id="nombreUser" required>
            </div>

            <div class="input-group">
                <label for="passwd">Contraseña</label>
                <input type="password" name="passwd" id="passwd" required>
            </div>

            <button type="submit" class="btn-login">REGISTRAR</button>
        </form>
        <a href="index.php?action=AnadirAdmin">Volver a Inicio</a></b>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>