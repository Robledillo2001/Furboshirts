<?php
    $rol_actual = $_SESSION['ROL'] ?? 'visitante';
    $foto_perfil = $_SESSION['IMAGEN'] ?? 'assets/img/user.png';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furboshirts - Tienda de Camisetas</title>
    <link rel="icon" type="image/png" href="assets/img/FurboshirtsLogo.png">

    <link rel="stylesheet" href="assets/estilos/estilos.css">
    <link rel="stylesheet" href="assets/estilos/inicio.css">
    <link rel="stylesheet" href="assets/estilos/header.css">
    <link rel="stylesheet" href="assets/estilos/footer.css">
    <link rel="stylesheet" href="assets/estilos/form.css">
    <link rel="stylesheet" href="assets/estilos/registro.css">
    <link rel="stylesheet" href="assets/estilos/Tienda.css">
    <link rel="stylesheet" href="assets/estilos/paginacion.css">
    <link rel="stylesheet" href="assets/estilos/catalogo.css">
    <link rel="stylesheet" href="assets/estilos/producto.css">
    <link rel="stylesheet" href="assets/estilos/carrito.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
        $rol_actual = $_SESSION['ROL'] ?? 'visitante';
        $foto_perfil = $_SESSION['IMAGEN'] ?? 'assets/img/user.png';
    ?>

    <header class="main-header">
        <div class="logo">
            <a href="index.php?action=<?= ($rol_actual === 'admin') ? 'MenuAdmin' : 'inicio' ?>">
                <img src="assets/img/FurboshirtsLogo.png" alt="Furboshirts">
            </a>
        </div>

        <nav class="nav-menu">
            <?php if($rol_actual === 'admin'): ?>
                <div class="nav-a">
                    <a href="index.php?action=MenuAdmin">Gestionar Tienda</a>
                </div>
                <div class="nav-a">
                    <a href="index.php?action=GestionAdmin">Gestionar Usuarios</a> 
                </div>
            <?php else: ?>
                <div class="nav-a">
                    <a href="index.php?action=inicio">INICIO</a>
                </div>
                <div class="nav-a">
                    <a href="index.php?action=mostrarCatalogo">PRODUCTOS</a> 
                </div>
            <?php endif; ?>
        </nav>

        <div class="header-actions">
            <?php if($rol_actual !=='admin'):?>
                <div class="search-bar">
                    <input type="text" placeholder="Buscar...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            <?php endif;?>
            
            <div class="icons">
                <?php if($rol_actual === 'admin'): ?>
                    <div class="dropdown">
                        <img src="<?=$foto_perfil ?>" alt="Perfil" class="img-perfil">

                        <div class="dropdown-content">
                            <a href="index.php?action=configuracion">Configuracion</a>
                            <a href="index.php?action=AnadirProducto">Añadir Producto </a>
                            <a href="index.php?action=GestionProductos">Gestionar Productos</a>
                        </div>
                    </div>
                    <a href="?action=logout" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></a>

                <?php elseif($rol_actual === 'cliente'): ?>
                    <div class="dropdown">
                        <img src="<?=$foto_perfil ?>" alt="Perfil" class="img-perfil">

                        <div class="dropdown-content">
                            <a href="index.php?action=configuracion">Configuracion</a>
                            <a href="index.php?action=pedidos">Pedidos</a>
                            <a href="index.php?action=valoraciones">Valoraciones</a>
                        </div>
                    </div>
                    <a href="?action=verCarrito"><i class="fas fa-shopping-cart"></i></a>
                    <a href="?action=logout" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></a>

                <?php else: ?>
                    <a href="?action=login" title="Iniciar Sesión"><i class="fas fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>