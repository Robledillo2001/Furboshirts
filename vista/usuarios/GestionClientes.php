<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Usuarios</h3>
        <nav>
            <a href="index.php?action=GestionAdmin" class="<?= ($_GET['action'] == 'GestionAdmin') ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i> Administradores
            </a>
            <a href="index.php?action=GestionClientes" class="<?= ($_GET['action'] == 'GestionClientes') ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Clientes
            </a>
            <hr>
            <a href="index.php?action=MenuAdmin">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </nav>
    </aside>

    <main class="contenido-gestion">
        <div class="container-tabla">
            <h2 class="titulo-seccion"><?= ($_GET['action'] == 'GestionAdmin') ? 'Gestión de Administradores' : 'Gestión de Clientes' ?></h2>
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NOMBRE</th>
                        <th>APELLIDOS</th>
                        <th>CORREO</th>
                        <th>USUARIO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Usamos una variable genérica $usuarios_vista para que el código sea el mismo
                    $usuarios_vista = isset($admins) ? $admins : $clientes;
                    
                    if (!empty($usuarios_vista)): 
                        foreach ($usuarios_vista as $user): ?>
                        <tr>
                            <td><img src="<?= $user['IMAGEN_USER'] ?>" class="img-user"></td>
                            <td><?= $user['NOMBRE'] ?></td>
                            <td><?= $user['APELLIDOS'] ?></td>
                            <td><?= $user['CORREO'] ?></td>
                            <td><strong><?= $user['NOMBRE_USUARIO'] ?></strong></td>
                            <td class="acciones">
                                <a href="?action=EditarUsuario&id=<?= $user['ID_USUARIO'] ?>&from=GestionClientes" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                <a href="?action=EliminarUsuario&id=<?= $user['ID_USUARIO'] ?>&from=GestionClientes" class="btn-icon delete" onclick="return confirm('¿Estás seguro?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; 
                    else: ?>
                        <tr><td colspan="6">No hay usuarios registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="paginacion">
            <?php 
            $action_actual = $_GET['action'];
            if ($paginaActual > 1): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $paginaActual - 1 ?>" class="btn-pag">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $i ?>" 
                   class="btn-pag <?= ($i == $paginaActual) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $paginaActual + 1 ?>" class="btn-pag">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../footer.php'; ?>