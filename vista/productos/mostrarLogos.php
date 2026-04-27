<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Administración</h3>
        <nav>
            <a href="index.php?action=MostrarCompeticiones" class="<?= ($_GET['action'] == 'MostrarCompeticiones') ? 'active' : '' ?>">
                <i class="fas fa-trophy"></i> Competiciones
            </a>
            <a href="index.php?action=MostrarLogos" class="<?= ($_GET['action'] == 'MostrarLogos') ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Logos
            </a>
            <hr>
            <a href="index.php?action=GestionTemporadas">
                <i class="fas fa-arrow-left"></i> Volver a Temporadas
            </a>
        </nav>
    </aside>

    <main class="contenido-gestion">
        <div class="container-tabla">
            <div class="header-seccion">
                <h2 class="titulo-seccion">Logos</h2>
                <div class="botones-header">
                    <a href="index.php?action=AnadirCompeticiones" class="btn-anadir">
                        <i class="fas fas fa-trophy"></i> Añadir Competiciones
                    </a>
                    <a href="index.php?action=AnadirLogos" class="btn-anadir">
                        <i class="fas fas fa-tag"></i> Añadir Logos
                    </a>
                </div>
            </div>
            
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>PARCHE</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Usamos la variable que definiste en el controlador para esta sección
                    if (!empty($logosPag)): ?>
                        <?php foreach ($logosPag as $l): ?>
                            <tr>
                                <td><strong><?= $l['ID_LOGO'] ?></strong></td>
                                <td><img src="<?= htmlspecialchars($l['PARCHE']) ?>" alt=""></td>
                                <td>
                                    <a href="index.php?action=EditarLogos&id=<?=$l['ID_LOGO']?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?action=EliminarLogos&id=<?=$l['ID_LOGO']?>" class="btn-icon delete" onclick="return confirm('¿Eliminar esta Temporada?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No hay Competiciones Agregadas.</td></tr>
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