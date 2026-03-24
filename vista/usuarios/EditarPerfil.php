<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="registro-container">
    <div class="formulario">
        <h2>Editar Perfil</h2>
        <form action="index.php?action=EditarPerfil" method="POST">
            <div class="input-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre">
            </div>

            <div class="input-group">
                <label for="apellidos">Apellidos</label>
                <input type="apellidos" name="apellidos" id="apellidos">
            </div>

            <div class="input-group">
                <label for="nombreUser">Nombre de Usuario</label>
                <input type="text" name="nombreUser" id="nombreUser">
            </div>

            <div class="input-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo">
            </div>

            <div class="input-group">
                <label for="passwd">Contraseña</label>
                <input type="password" name="passwd" id="passwd">
            </div>

            <div class="input-group">
                <label for="passwd">Comprobar Contraseña</label>
                <input type="password" name="passwd2" id="passwd2">
            </div>

            <button type="submit" class="btn-login">EDITAR DATOS</button>
        </form>
        <a href="index.php?action=EditarPerfil">Volver a Inicio</a></b>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>