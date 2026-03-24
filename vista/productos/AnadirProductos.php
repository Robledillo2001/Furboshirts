<?php 
include __DIR__ . '/../header.php'; 
?>

<div class="login-container">
    <div class="formulario" style="max-width: 1000px;">
        <h2><i class="fas fa-tshirt"></i> Añadir Nuevo Producto</h2>
        
        <form action="index.php?action=AnadirProducto" method="POST" enctype="multipart/form-data">
            
            <div class="fila-form">
                <div class="input-group flex-2">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Camiseta Local 24/25" required>
                </div>
                <div class="input-group flex-1">
                    <label for="precio">Precio (€)</label>
                    <input type="number" id="precio" name="precio" step="0.01" placeholder="0.00" required>
                </div>
            </div>

            <div class="fila-form">
                <div class="input-group flex-1">
                    <label for="categoria">Categoría</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Seleccione Prenda...</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat['ID_CAT'] ?>"><?= $cat['PRENDA'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group flex-1">
                    <label for="equipo">Equipo / Selección</label>
                    <select name="equipo" id="equipo" required>
                        <option value="">Seleccione Entidad...</option>
                        <?php foreach($equipos as $equipo): ?>
                            <option value="<?= $equipo['ID_EQUIPO'] ?>"><?= $equipo['NOMBRE_EQUIPO'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group flex-0-5">
                    <label for="ano">Año edición</label>
                    <input type="number" id="ano" name="ano" placeholder="2024">
                </div>
            </div>

            <div class="input-group">
                <label for="descripcion">Descripción Corta</label>
                <textarea id="descripcion" name="descripcion" rows="2" placeholder="Breve resumen del producto..."></textarea>
            </div>

            <div class="input-group">
                <label for="caracteristicas">Características Técnicas</label>
                <textarea id="caracteristicas" name="caracteristicas" rows="4" placeholder="Material, tecnología, detalles del bordado..."></textarea>
            </div>

            <div class="tallas-seccion">
                <label class="tallas-titulo">Stock por Tallas</label>
                <div class="tallas-grid">
                    <?php foreach($tallas as $talla): ?>
                    <div class="talla-item">
                        <label for="talla_<?= $talla['ID_TALLA'] ?>"><?= $talla['TALLA'] ?></label>
                        <input type="number" name="tallas[<?= $talla['ID_TALLA'] ?>]" id="talla_<?= $talla['ID_TALLA'] ?>" min="0" value="0">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="fila-form">
                <div class="input-group flex-1">
                    <label for="imagen1">Imagen Principal</label>
                    <input type="file" id="imagen1" name="imagen1" accept="image/*" required>
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
                    <a href="index.php?action=GestionTemporadas"><button class="btn-login">Cancelar</button></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../footer.php'; 
?>