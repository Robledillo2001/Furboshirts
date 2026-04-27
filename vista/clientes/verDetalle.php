<?php include __DIR__ . '/../header.php'; ?>

<div class="product-layout">

    <!-- Galería izquierda -->
    <div class="product-gallery">
        <img src="<?= $imagenes[0] ?? 'assets/img/no-image.jpg' ?>"
             id="imgGrande"
             class="product-main-image"
             alt="<?= htmlspecialchars($producto['NOMBRE']) ?>">

        <?php if (count($imagenes) > 1): ?>
        <div class="product-thumbnails">
            <?php foreach ($imagenes as $img): ?>
                <img src="<?= $img ?>"
                     class="img-thumbnail-custom"
                     onclick="cambiarImagen(this, '<?= $img ?>')">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info derecha -->
    <div class="product-info-column">
        <form action="index.php?action=agregarCarrito" method="POST">
            <input type="hidden" name="id_producto" value="<?= $producto['ID_PRODUCTO'] ?>">
            <input type="hidden" name="nombre_p"    value="<?= htmlspecialchars($producto['NOMBRE']) ?>">
            <input type="hidden" name="precio"       value="<?= $producto['PRECIO'] ?>">
            <input type="hidden" name="imagen"       value="<?= $imagenes[0] ?? '' ?>">

            <h1 class="product-name"><?= htmlspecialchars($producto['NOMBRE']) ?></h1>
            <p class="product-price-tag"><?= number_format($producto['PRECIO'], 2) ?> €</p>

            <?php if (!empty($producto['DESCRIPCION'])): ?>
                <p class="product-description"><?= htmlspecialchars($producto['DESCRIPCION']) ?></p>
            <?php endif; ?>

            <?php if (!empty($producto['CARACTERISTICAS'])): ?>
                <p class="product-features"><?= htmlspecialchars($producto['CARACTERISTICAS']) ?></p>
            <?php endif; ?>

            <!-- Tallas -->
            <div class="custom-section">
                <span class="custom-section-label"><i class="fas fa-ruler-horizontal"></i> Seleccionar Talla</span>
                <div class="selector-tallas">
                    <?php if (!empty($tallas)): ?>
                        <?php foreach ($tallas as $nombre_talla => $id_talla): ?>
                            <div class="selector-item">
                                <input type="radio" name="talla" id="talla_<?= $nombre_talla ?>" value="<?= $nombre_talla ?>" required>
                                <label for="talla_<?= $nombre_talla ?>">
                                    <div class="talla-card"><?= $nombre_talla ?></div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="width:100%; padding:10px; background:#fee2e2; border-radius:8px; color:#dc2626; font-size:0.88rem; font-weight:600;">
                            <i class="fas fa-exclamation-circle"></i> Agotado temporalmente
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Parches -->
            <?php if (!empty($parches) && $prenda === "Camiseta"): ?>
            <div class="custom-section">
                <span class="custom-section-label"><i class="fas fa-certificate"></i> Parches Oficiales</span>
                <div class="selector-parches">
                    <div class="selector-item">
                        <input type="radio" name="parche_id" id="parche_0" value="0" checked>
                        <label for="parche_0">
                            <div class="patch-card">
                                <small style="font-size:1.4rem; color:var(--gris-borde);">✕</small>
                                <small>Sin Parche</small>
                            </div>
                        </label>
                    </div>
                    <?php foreach ($parches as $p): ?>
                        <div class="selector-item">
                            <input type="radio" name="parche_id" id="p_<?= htmlspecialchars($p['nombre_comp']) ?>" value="<?= htmlspecialchars($p['nombre_comp']) ?>">
                            <label for="p_<?= htmlspecialchars($p['nombre_comp']) ?>">
                                <div class="patch-card">
                                    <?php if (!empty($p['especial'])): ?>
                                        <img src="<?= $p['especial'] ?>" alt="">
                                    <?php else: ?>
                                        <img src="<?= $p['ruta_parche'] ?>" alt="">
                                    <?php endif; ?>
                                    <small><?= htmlspecialchars($p['nombre_comp']) ?></small>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Personalización -->
            <?php if ($prenda === "Camiseta"): ?>
            <div class="custom-section">
                <span class="custom-section-label"><i class="fas fa-pen"></i> Personalización</span>
                <div style="display:grid; grid-template-columns:2fr 1fr; gap:14px;">
                    <div>
                        <label class="small">Nombre</label>
                        <input type="text" name="nombre_personalizado" class="form-control"
                               placeholder="TU NOMBRE" maxlength="15"
                               onkeyup="this.value = this.value.toUpperCase()">
                    </div>
                    <div>
                        <label class="small">Dorsal</label>
                        <input type="number" name="numero_personalizado" class="form-control"
                               placeholder="10" min="1" max="99">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn-dark w-100 mt-4" <?= empty($tallas) ? 'disabled' : '' ?>>
                <i class="fas fa-shopping-bag"></i> AÑADIR AL CARRITO
            </button>
        </form>

        <!-- Valoraciones -->
        <div class="custom-section" style="margin-top:32px;">
            <span class="custom-section-label"><i class="fas fa-star"></i> Dejar una Valoración</span>
            <p style="font-size:0.82rem; color:var(--texto-medio); margin:0 0 14px;">
                Sesión: <strong><?= htmlspecialchars($_SESSION['nombre'] ?? 'No identificado') ?></strong>
            </p>

            <form action="index.php?action=guardarValoracion&id=<?= $producto['ID_PRODUCTO'] ?>&anio=<?= htmlspecialchars($_GET['anio'] ?? '') ?>" method="POST">
                <div class="rating-css" style="margin-bottom:16px;">
                    <div class="star-icon">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="puntuacion" value="<?= $i ?>" id="rating<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                            <label for="rating<?= $i ?>" class="fas fa-star"></label>
                        <?php endfor; ?>
                    </div>
                </div>

                <textarea name="comentario" class="form-control"
                          style="border:1.5px solid var(--gris-borde); border-radius:10px; padding:12px; font-family:inherit; font-size:0.9rem; width:100%; resize:none; outline:none; box-sizing:border-box;"
                          rows="3"
                          placeholder="Comparte tu opinión sobre la calidad, talla..."></textarea>

                <button type="submit" class="btn-dark w-100"
                        style="margin-top:14px; padding:12px; font-size:0.9rem;">
                    <i class="fas fa-paper-plane"></i> Enviar Valoración
                </button>
            </form>
        </div>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <script>
                alert("<?= $_SESSION['mensaje'] ?>");
            </script>
            <?php unset($_SESSION['mensaje']); // Limpiamos el mensaje para que no salga otra vez ?>
        <?php endif; ?>

        <div class="product-reviews-list" style="margin-top:40px;">
            <h3 style="font-size:1.2rem; border-bottom:2px solid #eee; padding-bottom:10px;">Opiniones de clientes</h3>

            <?php if (empty($valoraciones)): ?>
                <p>Aún no hay comentarios.</p>
            <?php else: ?>
                <?php foreach ($valoraciones as $v): ?>
                    <div class="review-card" style="display:flex; gap:15px; margin-bottom:20px; background:#fff; padding:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
                        
                        <img src="<?= !empty($v['IMAGEN_USER']) ? $v['IMAGEN_USER'] : 'assets/img/default-avatar.png' ?>" 
                            style="width:50px; height:50px; border-radius:50%; object-fit:cover;">

                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between;">
                                <strong style="font-size:0.9rem;"><?= htmlspecialchars($v['NOMBRE_USUARIO']) ?></strong>
                                <div style="color:var(--verde-medio); font-size:0.8rem;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $v['PUNTUACION'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p style="font-size:0.85rem; color:#555; margin-top:5px;">
                                <?= nl2br(htmlspecialchars($v['COMENTARIOS'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
function cambiarImagen(el, src) {
    document.getElementById('imgGrande').src = src;
    document.querySelectorAll('.img-thumbnail-custom').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
}
</script>
<?php include __DIR__ . '/../footer.php'; ?>
