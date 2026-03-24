            <footer class="main-footer">
                <div class="footer-container">
                    
                    <div class="footer-section">
                        <div class="footer-logo">
                            <a href="index.php?action=inicio">
                                <img src="assets/img/FurboshirtsFooter.png" alt="Furboshirts - Volver al inicio">
                            </a>
                        </div>
                        <div class="social-links">
                            <p>Redes Sociales</p>
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                   <div class="footer-section">
                        <h3>Enlaces Rápidos</h3>
                        <ul>
                            <?php if($rol_actual === 'admin'): ?>
                                <li><a href="index.php?action=MenuAdmin">GESTIÓN TIENDA</a></li>
                                <li><a href="index.php?action=GestionAdmin">GESTIÓN USUARIOS</a></li>
                            <?php else: ?>
                                <li><a href="index.php?action=inicio">INICIO</a></li>
                                <li><a href="index.php?action=Productos">PRODUCTOS</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Contacto</h3>
                        <p><i class="fas fa-envelope"></i> Correo: Lopezreinarobledillo@gmail.com</p>
                        <p><i class="fas fa-phone"></i> Tel: +123 456 789</p>
                        <p><i class="fas fa-map-marker-alt"></i> Dirección: Calle Falsa 69, Cuenca</p>
                    </div>

                </div>
                
                <div class="footer-bottom">
                    <p>&copy; 2026 Furboshirts - Todos los derechos reservados.</p>
                </div>
        </footer>
    </body>
</html>