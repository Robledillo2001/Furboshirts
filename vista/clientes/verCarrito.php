<?php include __DIR__ . '/../header.php'; ?>

<div class="carrito-wrapper">
    <div class="carrito-header">
        <h1><i class="fas fa-shopping-bag"></i> Tu Carrito</h1>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <span class="carrito-count"><?= count($_SESSION['carrito']) ?> producto<?= count($_SESSION['carrito']) > 1 ? 's' : '' ?></span>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <div class="carrito-layout">
            <!-- Productos -->
            <div class="carrito-items" id="contenedor-carrito">
                <?php
                    $total = 0;
                    foreach ($_SESSION['carrito'] as $indice => $item):
                        $subtotalLinea = $item['precio'] * $item['cantidad'];
                        $total += $subtotalLinea;
                ?>
                <div class="cart-item" draggable="true" data-index="<?= $indice ?>">
                    <div class="cart-item-img">
                        <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre_producto']) ?>">
                    </div>
                    <div class="cart-item-info">
                        <h4><?= htmlspecialchars($item['nombre_producto']) ?></h4>
                        <div class="cart-item-meta">
                            <span class="cart-badge"><i class="fas fa-ruler-horizontal"></i> <?= htmlspecialchars($item['talla']) ?></span>
                            <?php if ($item['parche'] && $item['parche'] != '0'): ?>
                                <span class="cart-badge cart-badge--patch"><i class="fas fa-certificate"></i> <?= htmlspecialchars($item['parche']) ?></span>
                            <?php endif; ?>
                            <?php if ($item['nombre_personalizado'] !== 'Sin nombre'): ?>
                                <span class="cart-badge cart-badge--name"><i class="fas fa-user"></i> <?= htmlspecialchars($item['nombre_personalizado']) ?> #<?= htmlspecialchars($item['numero']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="cart-item-qty">
                        <input type="number"
                               value="<?= $item['cantidad'] ?>"
                               min="1" max="99"
                               onchange="actualizarCantidad(<?= $indice ?>, this.value)"
                               class="qty-input">
                    </div>
                    <div class="cart-item-price">
                        <?= number_format($subtotalLinea, 2) ?> €
                    </div>
                    <a href="index.php?action=eliminarDelCarrito&indice=<?= $indice ?>" class="cart-item-remove" title="Eliminar">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumen -->
            <div class="carrito-resumen">
                <h3>Resumen del pedido</h3>

                <div class="resumen-row">
                    <span>Subtotal</span>
                    <span><?= number_format($total, 2) ?> €</span>
                </div>
                <div class="resumen-row">
                    <span>Envío</span>
                    <span>5.00 €</span>
                </div>
                <div class="resumen-divider"></div>
                <div class="resumen-row resumen-total">
                    <span>Total estimado</span>
                    <span><?= number_format($total + 5, 2) ?> €</span>
                </div>

                <a href="index.php?action=procesarCompra" class="btn-checkout">
                    <i class="fas fa-lock"></i> Proceder al pago
                </a>

                <div class="carrito-links">
                    <a href="index.php?action=vaciarCarrito" class="link-vaciar">
                        <i class="fas fa-trash-alt"></i> Vaciar carrito
                    </a>
                    <a href="index.php?action=mostrarCatalogo" class="link-seguir">
                        <i class="fas fa-arrow-left"></i> Seguir comprando
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="carrito-empty">
            <div class="carrito-empty-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2>Tu carrito está vacío</h2>
            <p>Parece que aún no has añadido ningún producto.<br>¡Explora nuestro catálogo y encuentra tu camiseta!</p>
            <a href="index.php?action=mostrarCatalogo" class="btn-go-shop">
                <i class="fas fa-tshirt"></i> Ver catálogo
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="assets/js/carrito.js?V=2"></script>
<?php include __DIR__ . '/../footer.php'; ?>
