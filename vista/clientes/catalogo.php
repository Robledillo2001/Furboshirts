<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Competiciones</h3>
        <nav>
            <?php foreach($competiciones as $comp): ?>
                <a href="index.php?action=mostrarCatalogo&id_comp=<?= $comp['ID_COMP'] ?>">
                    <?= $comp['NOMBRE_COMP'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="contenido-gestion">
        <div class="filtros-catalogo">
            <a href="index.php?action=mostrarCatalogo" class="btn-filtro">Todos</a>
            
            <a href="index.php?action=mostrarCatalogo&tipo=Equipo" class="btn-filtro">Equipos</a>
            <a href="index.php?action=mostrarCatalogo&tipo=Seleccion" class="btn-filtro">Selecciones</a>
        </div>

        <div class="grid-productos">
            <?php if(!empty($productos)):?>
                <?php foreach ($productos as $p): ?>
                    <div class="producto-card">
                        <div class="producto-imagen">
                            <img src="<?= $p['IMAGEN_PRINCIPAL'] ?: 'assets/img/no-image.jpg' ?>" alt="<?= $p['NOMBRE'] ?>">
                        </div>
                        <div class="producto-info">
                            <h4 class="producto-titulo"><?= $p['NOMBRE'] ?></h4>
                            <p class="producto-precio"><?= number_format($p['PRECIO'], 2) ?> €</p>
                            <a href="index.php?action=VerDetalle&id=<?= $p['ID_PRODUCTO'] ?>" class="btn-ver">Ver Producto</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-productos">
                    <i class="fas fa-tshirt" style="font-size: 3rem; color: #21632a; margin-bottom: 15px;"></i>
                    <h3>No se han encontrado productos</h3>
                    <p>Lo sentimos, no hay artículos que coincidan con tu selección actual.</p>
                    <a href="index.php?action=mostrarCatalogo" class="btn-filtro">Ver todo el catálogo</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="paginacion">
            <?php 
            $action_actual = $_GET['action'];
            $tipo_url = isset($_GET['tipo']) ? "&tipo=" . urlencode($_GET['tipo']) : "";
            // Si añades filtros por ID, recuerda añadirlos también aquí:
            $comp_url = isset($_GET['id_comp']) ? "&id_comp=" . $_GET['id_comp'] : "";

            if ($paginaActual > 1): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $paginaActual - 1 ?><?= $tipo_url ?><?= $comp_url ?>" class="btn-pag">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $i ?><?= $tipo_url ?><?= $comp_url ?>" 
                   class="btn-pag <?= ($i == $paginaActual) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="index.php?action=<?= $action_actual ?>&pagina=<?= $paginaActual + 1 ?><?= $tipo_url ?><?= $comp_url ?>" class="btn-pag">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../footer.php'; ?>