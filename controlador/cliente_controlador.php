<?php
    require_once "modelo/cliente_modelo.php";
    class cliente_controlador{
        public function inicio(){
            require_once "vista/inicio.php";
        }

        public function mostrarCatalogo() {
            $modelo=new Cliente();
            // Obtenemos todos los productos con su primera imagen
            $productos = $modelo->obtenerCatalogo();
            
            // Cargamos la vista del catálogo
            require_once "vista/cliente/catalogo.php";
        }
    }
?>