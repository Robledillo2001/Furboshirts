<?php include __DIR__ . '/../header.php'; ?>

<div class="carrito-wrapper">
    <div class="carrito-header">
        <h1><i class="fas fa-lock"></i> Finalizar Pedido</h1>
    </div>

    <?php if (empty($_SESSION['carrito'])): ?>
        <div class="carrito-empty">
            <div class="carrito-empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <h2>Tu carrito está vacío</h2>
            <p>Agrega productos antes de continuar con el pago.</p>
            <a href="index.php?action=mostrarCatalogo" class="btn-go-shop"><i class="fas fa-tshirt"></i> Ver Catálogo</a>
        </div>
    <?php else: ?>

    <?php
        $subtotal = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $subtotal += $item['precio'] * ($item['cantidad'] ?? 1);
        }
        $envio = 0.00;

        if($subtotal<40){
            $envio = 4.50;
        }elseif($subtotal<100){
            $envio = 3.00;
        }elseif($subtotal>=100){
            $envio = 1.20;
        }
        $total = $subtotal + $envio;
    ?>

    <form action="index.php?action=procesarCompra" method="POST" id="form-checkout">
        <div class="checkout-layout">

            <!-- Columna izquierda: envío + pago -->
            <div class="checkout-left">
                <div class="checkout-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Dirección de Entrega</h3>
                    <div class="input-group-ubicacion" style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="direccion-api" name="direccion" 
                            placeholder="Calle, número, ciudad..." required 
                            style="flex: 1; padding: 12px; border-radius: 8px; border: 1px solid var(--gris-borde);">
                        
                        <button type="button" onclick="validarDireccion()" class="btn-validar" 
                                style="background: var(--verde-medio); color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-search-location"></i> Validar
                        </button>
                    </div>
                    
                    <div id="status-ubicacion" style="font-size: 0.85rem; margin-bottom: 10px; display: none;"></div>
                    <div id="map" style="height: 250px; width: 100%; margin-top: 10px; border-radius: 8px; display: none; border: 1px solid var(--gris-borde);"></div>

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                </div>

                <div class="checkout-section">
                    <h3><i class="fas fa-credit-card"></i> Método de Pago</h3>
                    <div class="metodos">
                        <label class="metodo-option">
                            <input type="radio" name="metodo_pago" value="Tarjeta" checked>
                            <span><i class="fas fa-credit-card"></i> Tarjeta de Crédito</span>
                        </label>
                        <label class="metodo-option">
                            <input type="radio" name="metodo_pago" value="Transferencia">
                            <span><i class="fas fa-university"></i> Transferencia Bancaria</span>
                        </label>
                        <label class="metodo-option">
                            <input type="radio" name="metodo_pago" value="PayPal">
                            <span><i class="fab fa-paypal"></i> PayPal</span>
                        </label>
                        <label>
                            <span>Codigo Metodo de Pago</span>
                            <input type="text" name="codigo" id="codigo">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: resumen -->
            <div class="carrito-resumen">
                <h3><i class="fas fa-receipt"></i> Resumen del Pedido</h3>

                <?php foreach ($_SESSION['carrito'] as $item):
                    $precio_linea = $item['precio'] * ($item['cantidad'] ?? 1);
                ?>
                <div class="checkout-item">
                    <div class="checkout-item-img">
                        <img src="<?= htmlspecialchars($item['imagen'] ?? 'assets/img/no-image.jpg') ?>" alt="">
                    </div>
                    <div class="checkout-item-info">
                        <p class="checkout-item-name"><?= htmlspecialchars($item['nombre_producto'] ?? '') ?></p>
                        <div class="cart-item-meta">
                            <span class="cart-badge"><i class="fas fa-ruler"></i> <?= htmlspecialchars($item['talla'] ?? '') ?></span>
                            <?php if (!empty($item['parche']) && $item['parche'] !== 'Sin Parche'): ?>
                                <span class="cart-badge cart-badge--patch"><i class="fas fa-shield-alt"></i> Con parche</span>
                            <?php endif; ?>
                            <?php if (!empty($item['nombre_personalizado']) && $item['nombre_personalizado'] !== 'Sin nombre'): ?>
                                <span class="cart-badge cart-badge--name"><i class="fas fa-pen"></i> <?= htmlspecialchars($item['nombre_personalizado']) ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="checkout-item-qty">x<?= (int)($item['cantidad'] ?? 1) ?></p>
                    </div>
                    <p class="checkout-item-price"><?= number_format($precio_linea, 2) ?>€</p>
                </div>
                <?php endforeach; ?>

                <div class="resumen-divider"></div>

                <div class="resumen-row">
                    <span>Subtotal</span>
                    <span><?= number_format($subtotal, 2) ?>€</span>
                </div>
                <div class="resumen-row">
                    <span>Envío</span>
                    <span><?= number_format($envio, 2) ?>€</span>
                </div>
                <div class="resumen-divider"></div>
                <div class="resumen-row resumen-total">
                    <span>Total</span>
                    <span id="total-display"><?= number_format($total, 2) ?>€</span>
                </div>

                <button type="button" class="btn-checkout" onclick="abrirModal()">
                    <i class="fas fa-lock"></i> Confirmar y Pagar
                </button>

                <div class="carrito-links">
                    <a href="index.php?action=verCarrito" class="link-seguir">
                        <i class="fas fa-arrow-left"></i> Volver al carrito
                    </a>
                </div>
            </div>

        </div>
    </form>

    <!-- Modal de confirmación -->
    <div id="modal-confirm" class="modal-overlay" style="display:none;">
        <div class="modal-card">
            <div class="modal-icon"><i class="fas fa-shopping-bag"></i></div>
            <h2 class="modal-title">¿Confirmar pedido?</h2>
            <p class="modal-subtitle">Estás a punto de realizar tu pedido. Revisa los datos antes de continuar.</p>

            <div class="modal-summary">
                <div class="modal-row">
                    <span><i class="fas fa-map-marker-alt"></i> Dirección</span>
                    <span id="modal-direccion" class="modal-val">—</span>
                </div>
                <div class="modal-row">
                    <span><i class="fas fa-credit-card"></i> Pago</span>
                    <span id="modal-pago" class="modal-val">—</span>
                </div>
                <div class="modal-row modal-row--total">
                    <span><i class="fas fa-tag"></i> Total</span>
                    <span class="modal-val modal-total"><?= number_format($total, 2) ?>€</span>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="cerrarModal()">
                    <i class="fas fa-arrow-left"></i> Revisar datos
                </button>
                <button type="button" class="modal-btn-confirm" onclick="document.getElementById('form-checkout').submit()">
                    <i class="fas fa-check"></i> Sí, confirmar
                </button>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<script src="assets/js/carrito.js"></script>

<?php include __DIR__ . '/../footer.php'; ?>
