<?php include __DIR__ . '/../header.php'; ?>

<div class="confirm-wrapper">
    <div class="confirm-card">
        <div class="confirm-icon-wrap">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="confirm-title">¡Pedido Confirmado!</h1>
        <p class="confirm-subtitle">Tu pedido ha sido registrado correctamente. Recibirás tu camiseta en 2-3 días naturales.</p>

        <div class="confirm-details">
            <div class="confirm-detail-row">
                <span><i class="fas fa-truck"></i> Envío estimado</span>
                <span>2-3 días naturales</span>
            </div>
            <div class="confirm-detail-row">
                <span><i class="fas fa-box"></i> Estado del pedido</span>
                <span class="confirm-badge">Pendiente</span>
            </div>
        </div>

        <div class="confirm-actions">
            <a href="index.php?action=mostrarCatalogo" class="btn-confirm-primary">
                <i class="fas fa-tshirt"></i> Seguir comprando
            </a>
            <a href="index.php?action=inicio" class="btn-confirm-secondary">
                <i class="fas fa-home"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<style>
.confirm-wrapper {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 20px;
    background: var(--gris-fondo);
}

.confirm-card {
    background: #fff;
    border-radius: 24px;
    padding: 56px 48px;
    max-width: 520px;
    width: 100%;
    text-align: center;
    box-shadow: var(--sombra-lg);
    border: 1px solid var(--gris-borde);
}

.confirm-icon-wrap {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, rgba(80,185,94,0.15), rgba(33,99,42,0.08));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 28px;
    font-size: 2.4rem;
    color: var(--verde-medio);
    border: 3px solid rgba(80,185,94,0.25);
}

.confirm-title {
    font-size: 2rem;
    font-weight: 900;
    color: var(--texto-oscuro);
    margin-bottom: 14px;
}

.confirm-subtitle {
    color: var(--texto-medio);
    font-size: 0.95rem;
    line-height: 1.7;
    max-width: 380px;
    margin: 0 auto 32px;
}

.confirm-details {
    background: var(--gris-fondo);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 32px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    border: 1px solid var(--gris-borde);
}

.confirm-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: var(--texto-medio);
}

.confirm-detail-row span:first-child {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.confirm-detail-row span:first-child i {
    color: var(--verde-medio);
}

.confirm-badge {
    background: rgba(33,99,42,0.1);
    color: var(--verde-medio);
    font-size: 0.78rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.confirm-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-confirm-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--verde-medio), var(--verde-hover));
    color: #fff;
    padding: 15px 32px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.95rem;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 16px rgba(33,99,42,0.3);
    transition: all 0.3s ease;
}

.btn-confirm-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(33,99,42,0.4);
}

.btn-confirm-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--texto-medio);
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    padding: 12px;
    border-radius: 12px;
    border: 1.5px solid var(--gris-borde);
    transition: all 0.3s ease;
}

.btn-confirm-secondary:hover {
    border-color: var(--verde-claro);
    color: var(--verde-medio);
    background: rgba(80,185,94,0.04);
}

@media (max-width: 580px) {
    .confirm-card { padding: 40px 24px; }
    .confirm-title { font-size: 1.6rem; }
}
</style>

<?php include __DIR__ . '/../footer.php'; ?>
