<?php include __DIR__ . '/../header.php'; ?>

<div class="container mt-5">
    <form action="index.php?action=agregarCarrito" method="POST">
        <input type="hidden" name="id_producto" value="<?= $producto['ID_PRODUCTO']; ?>">
        <input type="hidden" name="nombre_p" value="<?= $producto['NOMBRE']; ?>">
        <input type="hidden" name="precio" value="<?= $producto['PRECIO']; ?>">
        <input type="hidden" name="imagen" value="<?= $imagenes[0]; ?>">
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="text-center">
                    <img src="<?= $imagenes[0]; ?>" id="imgGrande" class="img-fluid rounded shadow-sm mb-3" style="max-height: 500px; width: 100%; object-fit: contain;">
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <?php foreach ($imagenes as $img): ?>
                        <img src="<?= $img; ?>" class="img-thumbnail-custom border" onclick="document.getElementById('imgGrande').src='<?= $img; ?>'">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h1 class="nombre fw-bold h2"><?= htmlspecialchars($producto['NOMBRE']); ?></h1>
                <h3><?= htmlspecialchars($producto['PRECIO']); ?> €</h3>

                <p class="text-muted mb-2"><?= htmlspecialchars($producto['DESCRIPCION']); ?></p>
                <p class="text-muted small mb-4"><i><?= htmlspecialchars($producto['CARACTERISTICAS']); ?></i></p>

                <div class="custom-section">
                    <label class="fw-bold mb-2">Seleccionar Talla</label>
                    <div class="selector-grid">
                        <?php if (!empty($tallas)): ?>
                            <?php foreach ($tallas as $nombre_talla => $id_talla): ?>
                                <div class="selector-item">
                                    <input type="radio" name="talla" id="talla_<?= $nombre_talla; ?>" value="<?= $nombre_talla; ?>" required>
                                    <label for="talla_<?= $nombre_talla; ?>">
                                        <div class="talla-card"><?= $nombre_talla; ?></div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-danger w-100 py-2 mb-0">Agotado temporalmente</div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($parches)): ?>
                        <hr>
                        <label class="fw-bold mb-2"><i class="fas fa-shield-alt text-primary"></i> Parches Oficiales</label>
                        <div class="selector-grid">
                            <div class="selector-item">
                                <input type="radio" name="parche_id" id="parche_0" value="0" checked>
                                <label for="parche_0">
                                    <div class="patch-card"><small>X<br>Sin Parche</small></div>
                                </label>
                            </div>
                            <?php foreach ($parches as $p): ?>
                                <div class="selector-item">
                                    <input type="radio" name="parche_id" id="p_<?= $p['nombre_comp']; ?>" value="<?= htmlspecialchars($p['nombre_comp']); ?>">
                                    <label for="p_<?= $p['nombre_comp']; ?>">
                                        <div class="patch-card">
                                            <img src="<?= $p['ruta_parche']; ?>" style="height: 25px;">

                                            <?php if(!empty($p['especial'])):?>
                                                <img src="<?= $p['especial']; ?>" style="height: 25px;">
                                            <?php endif; ?>
                                            <br>
                                            <small class="d-block"><?= htmlspecialchars($p['nombre_comp']); ?></small>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <hr>
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="fw-bold small mb-1">Nombre</label>
                            <input type="text" name="nombre_personalizado" class="form-control" placeholder="TU NOMBRE" maxlength="15" onkeyup="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-4">
                            <label class="fw-bold small mb-1">Dorsal</label>
                            <input type="number" name="numero_personalizado" class="form-control" placeholder="10" min="1" max="99">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100 mt-4 py-3 fw-bold" <?= empty($tallas) ? 'disabled' : ''; ?>>
                    <i class="fas fa-shopping-bag me-2"></i> AÑADIR AL CARRITO
                </button>
            </div>
        </div>
    </form>

    <hr class="my-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="custom-section">
                <h4 class="fw-bold mb-4 text-center">Reseñas del Producto</h4>
                
                <form action="index.php?action=guardarValoracion&id=<?= $producto['ID_PRODUCTO']; ?>&anio=<?= $_GET['anio']; ?>" method="POST" class="mb-5">
                    <p class="text-muted small">Tu sesión: <b><?= $_SESSION['nombre'] ?? 'No identificado'; ?></b></p>
                    
                    <div class="rating-css mb-3">
                        <div class="star-icon">
                            <?php for($i=5; $i>=1; $i--): ?>
                                <input type="radio" name="puntuacion" value="<?= $i; ?>" id="rating<?= $i; ?>" <?= $i==5 ? 'checked' : ''; ?>>
                                <label for="rating<?= $i; ?>" class="fas fa-star"></label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe aquí tu opinión sobre la calidad, talla..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fas fa-paper-plane me-2"></i> ENVIAR VALORACIÓN
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="assets/js/producto.js"></script>
<?php include __DIR__ . '/../footer.php'; ?>