<?php
    require_once "modelo/cliente_modelo.php";
    class cliente_controlador{
        public function inicio(){
            require_once "vista/inicio.php";
        }

        public function MostrarCatalogo() {
            //Instanciamos el modelo Cliente que creamos antes
            $modelo = new Cliente();

            // Configuración de la paginación
            $productosPorPagina = 8; 

            // Capturamos el tipo de la URL (ej: catalogo&tipo=Equipo)
            $filtros=[
                'tipo'=>$_GET['tipo']??null,
                'id_comp'=>$_GET['id_comp']??null,
                'id_equipo'=>$_GET['id_equipo']??null,
            ];
            
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if ($paginaActual < 1) $paginaActual = 1;

            $inicio = ($paginaActual - 1) * $productosPorPagina;

            //Obtener los datos usando los nuevos métodos del modelo Cliente
            $totalProductos = $modelo->contarTotalProductos($filtros);
            $totalPaginas = ceil($totalProductos / $productosPorPagina);

            // Obtenemos los productos para la página actual
            $productos = $modelo->obtenerCatalogo($inicio, $productosPorPagina,$filtros);

            // Cargar listas para los menús desplegables
            $competiciones = $modelo->listarCompeticiones();
            $listaEquipos = $modelo->listarEntidadesPorTipo('Equipo');
            $listaSelecciones = $modelo->listarEntidadesPorTipo('Seleccion');

            //Cargar la vista del catálogo para el cliente
            require_once "vista/clientes/catalogo.php";
        }
    }
?>