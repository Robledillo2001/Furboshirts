<?php include __DIR__ . '/../header.php'; ?>
<div class="carrito-container">
    <h2><i class="fas fa-shopping-cart"></i> Carrito</h2>

    <div class="productos-section" id="contenedor-carrito">
        <h5>Productos(Arrastra para reordenar)</h5>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <?php foreach ($_SESSION['carrito'] as $indice => $item): ?>
                <div class="producto-linea" draggable="true" data-index="<?= $indice ?>">
                    <span>
                        <strong>Talla: <?= $item['talla']; ?></strong>
                        <?= $item['nombre_producto']; ?>
                        <?= $item['parche']; ?> 
                        <?= $item['nombre_personalizado']; ?> 
                        (<?=  $item['numero']; ?>)
                    </span>
                    <span>
                        <?= number_format($item['precio'], 2); ?>€
                    </span>
                    <span>
                        Cantidad: <?= $item['cantidad']; ?> 
                    </span>
                    <span>
                        <a href="index.php?action=eliminarDelCarrito&indice=<?= $indice; ?>" class="btn-icon delete"><i class="fas fa-trash"></i></a>
                    </span>
                    <span>
                        <img src="<?= $item['imagen'] ?>" alt="">
                    </span>
                </div>
        <?php endforeach; ?>
            <div class="enlaces-carrito">
                <a href="index.php?action=vaciarCarrito">Vaciar Carrito</a>
                <a href="index.php?action=procesarCompra">Procesar Compra</a>
            </div>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>
</div>

<script src="assets/js/carrito.js"></script>
<?php include __DIR__ . '/../footer.php'; ?>