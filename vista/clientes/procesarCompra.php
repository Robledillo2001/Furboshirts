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
        $envio = 5.00;
        $total = $subtotal + $envio;
    ?>

    <form action="index.php?action=procesarCompra" method="POST" id="form-checkout">
        <div class="checkout-layout">

            <!-- Columna izquierda: envío + pago -->
            <div class="checkout-left">
                <div class="checkout-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Dirección de Entrega</h3>
                    <div class="resumen-envio">
                        <label for="direccion-api"><i class="fas fa-home"></i> Dirección completa</label>
                        <input type="text" name="direccion" id="direccion-api" required placeholder="Calle, número, ciudad, código postal...">
                    </div>
                    <div class="mapa-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Mapa de Geoapify</span>
                    </div>
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

<style>
/* Modal overlay */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes slideUp { from { transform: translateY(24px); opacity:0; } to { transform:translateY(0); opacity:1; } }

.modal-card {
    background: #fff;
    border-radius: 24px;
    padding: 48px 40px;
    max-width: 460px;
    width: 100%;
    text-align: center;
    box-shadow: 0 24px 80px rgba(0,0,0,0.2);
    animation: slideUp 0.25s ease;
}

.modal-icon {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, rgba(80,185,94,0.15), rgba(33,99,42,0.08));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.8rem;
    color: var(--verde-medio);
    border: 2px solid rgba(80,185,94,0.25);
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--texto-oscuro);
    margin-bottom: 10px;
}

.modal-subtitle {
    color: var(--texto-medio);
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 24px;
}

.modal-summary {
    background: var(--gris-fondo);
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 28px;
    border: 1px solid var(--gris-borde);
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.modal-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.88rem;
    color: var(--texto-medio);
    gap: 12px;
}

.modal-row span:first-child {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}

.modal-row span:first-child i { color: var(--verde-medio); }

.modal-val {
    font-weight: 600;
    color: var(--texto-oscuro);
    text-align: right;
    word-break: break-word;
}

.modal-row--total { padding-top: 10px; border-top: 1px solid var(--gris-borde); }
.modal-total { color: var(--verde-medio) !important; font-size: 1.05rem; font-weight: 900 !important; }

.modal-actions {
    display: flex;
    gap: 12px;
}

.modal-btn-cancel {
    flex: 1;
    padding: 13px;
    border: 1.5px solid var(--gris-borde);
    border-radius: 12px;
    background: #fff;
    color: var(--texto-medio);
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.modal-btn-cancel:hover {
    border-color: var(--verde-claro);
    color: var(--texto-oscuro);
}

.modal-btn-confirm {
    flex: 1;
    padding: 13px;
    background: linear-gradient(135deg, var(--verde-medio), var(--verde-hover));
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(33,99,42,0.3);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.modal-btn-confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(33,99,42,0.4);
}
</style>

<script>
function abrirModal() {
    const direccion = document.getElementById('direccion-api').value.trim();
    if (!direccion) {
        document.getElementById('direccion-api').focus();
        document.getElementById('direccion-api').style.borderColor = '#e53e3e';
        document.getElementById('direccion-api').style.boxShadow = '0 0 0 3px rgba(229,62,62,0.18)';
        return;
    }
    const pago = document.querySelector('input[name="metodo_pago"]:checked').value;
    document.getElementById('modal-direccion').textContent = direccion;
    document.getElementById('modal-pago').textContent = pago;
    document.getElementById('modal-confirm').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-confirm').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('direccion-api').addEventListener('input', function() {
    this.style.borderColor = '';
    this.style.boxShadow = '';
});

document.getElementById('modal-confirm').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>

<?php include __DIR__ . '/../footer.php'; ?>
