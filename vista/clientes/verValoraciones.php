<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Administración</h3>
        <nav>
            <a href="index.php?action=inicio" class="<?= ($_GET['action'] == 'GestionProductos') ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Volver a Inicio
            </a>
            <a href="index.php?action=VerPedidos" class="<?= ($_GET['action'] == 'GestionCategorias') ? 'active' : '' ?>">
                <i class="fas fa-box-open"></i> Mis Pedidos
            </a>
            <a href="index.php?action=Vervaloraciones" class="<?= ($_GET['action'] == 'GestionTallas') ? 'active' : '' ?>">
                <i class="fas fa-star"></i> Mis valoraciones
            </a>
        </nav>
    </aside>

    <main class="contenido-gestion">

        <div class="container-tabla">
            <div class="header-seccion">
                <h2 class="titulo-seccion">Historial de Pedidos</h2>
            </div>

            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Puntuacion</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($valoracionesPag)): ?>
                        <?php foreach ($valoracionesPag as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['NOMBRE']) ?></td>
                                <td style="color:var(--verde-medio); font-size:0.8rem;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $v['PUNTUACION'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </td>
                                <td><?= htmlspecialchars($v['COMENTARIOS']) ?></td>
                                <td>
                                    <a href="index.php?action=EditarValoracion&id=<?= $v['ID_VALORACION'] ?>&from=Vervaloraciones" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?action=EliminarValoracion&id=<?= $v['ID_VALORACION'] ?>&from=Vervaloraciones" class="btn-icon delete" onclick="return confirm('¿Eliminar este Pedido?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No hay pedidos registrados.</td></tr>
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