<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Administración</h3>
        <nav>
            <a href="index.php?action=GestionProductos" class="<?= ($_GET['action'] == 'GestionProductos') ? 'active' : '' ?>">
                <i class="fas fa-tshirt"></i> Productos
            </a>
            <a href="index.php?action=GestionCategorias" class="<?= ($_GET['action'] == 'GestionCategorias') ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Categorias
            </a>
            <a href="index.php?action=GestionTallas" class="<?= ($_GET['action'] == 'GestionTallas') ? 'active' : '' ?>">
                <i class="fas fa-ruler"></i> Tallas
            </a>
            <a href="index.php?action=GestionEquipos" class="<?= ($_GET['action'] == 'GestionEquipos') ? 'active' : '' ?>">
                <i class="fas fa-shield-alt"></i> Equipos
            </a>
            <a href="index.php?action=GestionSelecciones" class="<?= ($_GET['action'] == 'GestionSelecciones') ? 'active' : '' ?>">
                <i class="fas fa-globe"></i> Selecciones
            </a>
            <a href="index.php?action=GestionTemporadas" class="<?= ($_GET['action'] == 'GestionTemporadas') ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i> Años/Temporadas
            </a>
            <a href="index.php?action=GestionPedidos" class="<?= ($_GET['action'] == 'GestionPedidos') ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Historial de Pedidos
            </a>
            <hr>
            <a href="index.php?action=GestionTienda">
                <i class="fas fa-arrow-left"></i> Volver al Panel
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
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Dirección Envío</th>
                        <th>Método Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosPag)): ?>
                        <?php foreach ($pedidosPag as $p): ?>
                            <tr>
                                <td><?= $p['ID_PEDIDO'] ?></td>
                                <td><?= htmlspecialchars($p['NOMBRE_USUARIO']) ?></td>
                                <td><?= htmlspecialchars($p['FECHA']) ?></td>
                                <td><?= number_format($p['TOTAL'], 2) ?> €</td>
                                <td><?= htmlspecialchars($p['ESTADO']) ?></td>
                                <td><?= htmlspecialchars($p['DIRECCION_ENVIO']) ?></td>
                                <td><?= htmlspecialchars($p['METODO_PAGO']) ?></td>
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