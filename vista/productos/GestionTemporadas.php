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
                <h2 class="titulo-seccion">Competiciones y Logos</h2>
                <div class="botones-header">
                    <a href="index.php?action=AnadirCompeticiones" class="btn-anadir">
                        <i class="fas fa-trophy"></i> Asignar Competiciones
                    </a>
                </div>
            </div>
            
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Competición</th>
                        <th>Parche Estándar</th>
                        <th>Parche Especial</th>
                        <th>Tipo</th>
                        <th>Año/Edición</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Usamos la variable que definiste en el controlador para esta sección
                    if (!empty($temporadasPag)): ?>
                        <?php foreach ($temporadasPag as $c): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($c['NOMBRE_EQUIPO']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($c['NOMBRE_COMP']) ?></strong></td>
                                <td><img src="<?= htmlspecialchars($c['PARCHE']) ?>" alt=""></td>
                                <td>
                                    <?php if (!empty($c['PARCHE_ESPECIAL'])): ?>
                                        <img src="<?= htmlspecialchars($c['PARCHE_ESPECIAL']) ?>" alt="Parche Especial" style="width: 50px;">
                                    <?php else: ?>
                                        <span style="color: #888; font-style: italic;">Sin parche especial</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge"><?= htmlspecialchars($c['TIPO_COMP']) ?></span></td>
                                <td><?= htmlspecialchars($c['AÑO_EDICION']) ?></td>
                                <td>
                                    <a href="#" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn-icon delete" onclick="return confirm('¿Eliminar esta relación?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No hay datos de temporadas registrados.</td></tr>
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