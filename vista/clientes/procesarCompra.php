<?php include __DIR__ . '/../header.php'; ?>

<form action="index.php?action=procesarCompra" method="POST">
    <div class="carrito-container">
        <div class="envio-pago-section">
            <h5>DATOS ENVÍO y PAGO</h5>
            
            <label>Dirección de Entrega</label>
            <input type="text" name="direccion" id="direccion-api" required placeholder="Escribe tu dirección...">
            
            <div id="mapa-previsualizacion" style="width:100%; height:150px; background:#eee; margin-top:10px;">
                <p style="text-align:center; padding-top:60px;">MAPA (Geoapify)</p>
            </div>

            <label class="mt-3">Método de Pago</label>
            <div class="metodos">
                <input type="radio" name="metodo_pago" value="Tarjeta" checked> Tarjeta de Crédito
                <input type="radio" name="metodo_pago" value="Transferencia"> Transferencia
                <input type="radio" name="metodo_pago" value="PayPal"> PayPal
            </div>
        </div>

        <hr>

        <div class="resumen-section">
            <h5>Resumen Final</h5>
            <?php 
                $subtotal = 0;
                if(!empty($_SESSION['carrito'])) {
                    foreach($_SESSION['carrito'] as $i) { $subtotal += $i['precio']; }
                }
                $envio = 5.00; 
            ?>
            <p>Subtotal: <?= number_format($subtotal, 2); ?>€</p>
            <p>Envío: <?= number_format($envio, 2); ?>€</p>
            <h4>Total: <?= number_format($subtotal + $envio, 2); ?>€</h4>
        </div>

        <button type="submit" class="btn-finalizar">Confirmar y Finalizar Pedido</button>
    </div>
</form>

<script src="assets/js/carrito.js"></script>
<?php include __DIR__ . '/../footer.php'; ?>