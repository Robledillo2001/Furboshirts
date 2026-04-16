<?php 
include __DIR__ . '/../header.php';
?>

<?php if (isset($_SESSION['error_val'])): ?>
    <script>
        alert("<?php echo $_SESSION['error_val']; ?>");
    </script>
    <?php unset($_SESSION['error_val']); // Importante borrarlo para que no salga siempre ?>
<?php endif; ?>

<div class="login-container">
    <div class="formulario">
        <?php if (isset($error)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <h2>INICIAR SESION</h2>
        <form action="index.php?action=login" method="POST">
            <div class="input-group">
                <label for="usuario">Nombre o Correo</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>

            <div class="input-group">
                <label for="passwd">Contraseña</label>
                <input type="password" name="passwd" id="passwd" required>
            </div>

            <div class="checkbox-group">
                <label for="recordar">Recordar Usuario</label>
                <input type="checkbox" name="recordar" id="recordar">
            </div>

            <button type="submit" class="btn-login">INICIAR SESION</button>
        </form>
        <b>¿No tienes cuenta? <a href="index.php?action=registrar">Registrate</a></b>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>