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
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Dirección Envío</th>
                        <th>Método Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosPag)): ?>
                        <?php foreach ($pedidosPag as $p): ?>
                            <tr>
                                <td><?= $p['ID_PEDIDO'] ?></td>
                                <td><?= htmlspecialchars($p['FECHA']) ?></td>
                                <td><?= number_format($p['TOTAL'], 2) ?> €</td>
                                <td><?= htmlspecialchars($p['ESTADO']) ?></td>
                                <td><?= htmlspecialchars($p['DIRECCION_ENVIO']) ?></td>
                                <td><?= htmlspecialchars($p['METODO_PAGO']) ?></td>
                                <td>
                                    <a href="index.php?action=EliminarPedido&id=<?= $p['ID_PEDIDO'] ?>&from=VerPedidos" class="btn-icon delete" onclick="return confirm('¿Eliminar este Pedido?')"><i class="fas fa-trash"></i></a>
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