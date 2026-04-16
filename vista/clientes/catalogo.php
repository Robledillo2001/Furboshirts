<?php include __DIR__ . '/../header.php'; ?> 

<div class="layout-gestion">
    <aside class="sidebar-gestion">
        <h3>Filtros de Búsqueda</h3>
        
        <form action="index.php" method="GET" id="filter-form">
            <input type="hidden" name="action" value="mostrarCatalogo">
            
            <?php if(isset($_GET['tipo'])): ?>
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($_GET['tipo']) ?>">
            <?php endif; ?>

            <div class="filter-group">
                <label for="id_deporte">Deporte</label>
                <select name="id_deporte" onchange="this.form.submit()">
                    <option value="">Todos los deportes</option>
                    <?php foreach($deportes as $dep): ?>
                        <option value="<?= $dep['ID_DEPORTE'] ?>" <?= (isset($_GET['id_deporte']) && $_GET['id_deporte'] == $dep['ID_DEPORTE']) ? 'selected' : '' ?>>
                            <?= $dep['DEPORTE'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="id_cat">Categoría</label>
                <select name="id_cat" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat['ID_CAT'] ?>" <?= (isset($_GET['id_cat']) && $_GET['id_cat'] == $cat['ID_CAT']) ? 'selected' : '' ?>>
                            <?= $cat['PRENDA'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="id_comp">Competición</label>
                <select name="id_comp" onchange="this.form.submit()">
                    <option value="">Todas las competiciones</option>
                    <?php foreach($competiciones as $comp): ?>
                        <option value="<?= $comp['ID_COMP'] ?>" <?= (isset($_GET['id_comp']) && $_GET['id_comp'] == $comp['ID_COMP']) ? 'selected' : '' ?>>
                            <?= $comp['NOMBRE_COMP'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <noscript>
                <button type="submit" class="btn-filtrar">Filtrar</button>
            </noscript>
            
            <a href="index.php?action=mostrarCatalogo" class="btn-reset">Limpiar Filtros</a>
        </form>
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

                            <div class="producto-rating mb-2">
                                <?php
                                    $media=round($p['MEDIA_VALORACION']??0);
                                    for($i=1;$i<=5;$i++){
                                        echo ($i<=$media)
                                        ? '<i class="fas fa-star" style="color: #21632A; font-size: 0.8rem;"></i>' 
                                        : '<i class="far fa-star" style="color: #50B95E; font-size: 0.8rem;"></i>';
                                    }
                                ?>
                                <span class="text-muted" style="font-size: 0.75rem;">(<?= number_format($p['MEDIA_VALORACION'] ?? 0, 1) ?>)</span>
                            </div>

                            <a href="index.php?action=VerDetalle&id=<?= $p['ID_PRODUCTO'] ?>&anio=<?=$p["AÑO_EDICION"]?>" class="btn-ver">Ver Producto</a>
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