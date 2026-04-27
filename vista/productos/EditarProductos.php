<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="login-container">
    <div class="formulario" style="max-width: 1000px;">
        <h2><i class="fas fa-tshirt"></i> Editar Producto</h2>
        
        <form action="index.php?action=EditarProducto&id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
            
            <div class="fila-form">
                <div class="input-group flex-2">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Camiseta Local 24/25" value="<?= $producto['NOMBRE'] ?>">
                </div>
                <div class="input-group flex-1">
                    <label for="precio">Precio (€)</label>
                    <input type="number" id="precio" name="precio" step="0.01" placeholder="0.00" value="<?= $producto['PRECIO'] ?>">
                </div>
            </div>

            <div class="fila-form">
                <div class="input-group flex-1">
                    <label for="categoria">Categoría</label>
                    <select name="categoria" id="categoria" >
                        <option value="">Seleccione Prenda...</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat['ID_CAT'] ?>" <?= $cat['ID_CAT'] == $producto['ID_CAT'] ? 'selected' : '' ?>><?= $cat['PRENDA'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group flex-1">
                    <label for="deporte">Deportes</label>
                    <select name="deporte" id="deporte">
                        <option value="">Seleccione Deporte</option>
                        <?php foreach($deportes as $d): ?>
                            <option value="<?= $d['ID_DEPORTE'] ?>" <?= $d['ID_DEPORTE'] == $producto['ID_DEPORTE'] ? 'selected' : '' ?>><?= $d['DEPORTE'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group flex-1">
                    <label for="equipo">Equipo / Selección</label>
                    <select name="equipo" id="equipo" >
                        <option value="">Seleccione Entidad...</option>
                        <?php foreach($equipos as $equipo): ?>
                            <option value="<?= $equipo['ID_EQUIPO'] ?>" <?= $equipo['ID_EQUIPO'] == $producto['ID_EQUIPO'] ? 'selected' : '' ?>><?= $equipo['NOMBRE_EQUIPO'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group flex-0-5">
                    <label for="ano">Año edición</label>
                    <input type="number" id="anio" name="anio" placeholder="2024" value="<?= $producto['ANO_EDICION'] ?>">
                </div>
            </div>

            <div class="input-group">
                <label for="desc">Descripción Corta</label>
                <textarea id="desc" name="desc" rows="2" placeholder="Breve resumen del producto..."><?= $producto['DESCRIPCION'] ?></textarea>
            </div>

            <div class="input-group">
                <label for="caracteristicas">Características Técnicas</label>
                <textarea id="caracteristicas" name="caracteristicas" rows="4" placeholder="Material, tecnología, detalles del bordado..."><?= $producto['CARACTERISTICAS'] ?></textarea>
            </div>

            <div class="tallas-seccion">
                <label class="tallas-titulo">Stock por Tallas</label>
                <div class="tallas-grid">
                    <?php foreach($tallas as $talla): 
                        // Buscamos si esta talla tiene stock guardado para este producto
                        $id_talla = $talla['ID_TALLA'];
                        $cantidadStock = isset($stocksActuales[$id_talla]) ? $stocksActuales[$id_talla] : 0;
                    ?>
                    <div class="talla-item">
                        <label for="talla_<?= $id_talla ?>"><?= $talla['TALLA'] ?></label>
                        <input type="number" 
                            name="tallas[<?= $id_talla ?>]" 
                            id="talla_<?= $id_talla ?>" 
                            min="0" 
                            value="<?= $cantidadStock ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="fila-form">
                <?php foreach ($imagenes as $img): ?>
                    <div class="img-container">
                        <img src="<?= $img['RUTA'] ?>" width="80">
                    </div>
                <?php endforeach; ?>
                <div class="input-group flex-1">
                    <label for="imagen1">Imagen Principal</label>
                    <input type="file" id="imagen1" name="imagen1" accept="image/*">
                </div>
                <div class="input-group flex-1">
                    <label for="imagen2">Imagen Secundaria (Opcional)</label>
                    <input type="file" id="imagen2" name="imagen2" accept="image/*">
                </div>
            </div>

            <input type="hidden" name="fecha_alta" value="<?= date('Y-m-d') ?>">

            <div class="btn">
                <div class="acciones-form">
                    <button type="submit" class="btn-login">Guardar</button>
                    <a href="index.php?action=GestionProductos">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>