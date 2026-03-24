<?php
    require_once "modelo/productos_modelo.php";
    class productos_controlador{

        private function checkAdmin(){//Metodo privado que se usaran en los metodos que use el admin para que el Cliente y el Visitante no puedan acceder
            if(!isset($_SESSION['ROL']) || $_SESSION['ROL'] !== "admin"){
                header("Location: index.php?action=inicio");//Y redirija al Inicio directamente
                exit();//Finaliza la ejecucion del script para que ocurra la redireccion
            }
        }

        public function GestionProductos(){//Metodo para mostrar los productos
            $this->checkAdmin();
        
            $modelo=new Productos();

            //Configuracion de la paginacion
            $productos = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$productos;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarProductos();
            $totalPaginas = ceil($totalProductos / $productos);

            //Obtenemos todos los Productos de la pagina
            $productosPag=$modelo->ListarProductos($inicio,$productos);

            //Cargar la vista con los Porductos
            require_once "vista/productos/GestionProductos.php";
        }

        public function AnadirProducto(){
            $modelo=new Productos();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Recoger datos de texto
                $nombre = $_POST['nombre'];
                $id_equipo = $_POST['equipo'];
                $id_categoria = $_POST['categoria'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $fecha_alta = $_POST['fecha_alta'];
                $caracteristicas = $_POST['caracteristicas'];
                $anoEdicion=$_POST['ano'];

                //Capturamos el array de tallas para añadirlo al stock
                $tallas=isset($_POST['tallas'])?$_POST['tallas']:[];

                //Procesar imágenes (Rutas)
                $rutaCarpeta = "assets/img/productos/";
                $imagen1 = "";
                $imagen2 = "";

                // Procesar Imagen 1 (Obligatoria)
                if (isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] == 0) {
                    $nombreImg1 = time() . "_" . $_FILES['imagen1']['name'];
                    move_uploaded_file($_FILES['imagen1']['tmp_name'], $rutaCarpeta . $nombreImg1);
                    $imagen1 = $rutaCarpeta . $nombreImg1;
                }

                // Procesar Imagen 2 (Opcional)
                if (isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] == 0) {
                    $nombreImg2 = time() . "_2_" . $_FILES['imagen2']['name'];
                    move_uploaded_file($_FILES['imagen2']['tmp_name'], $rutaCarpeta . $nombreImg2);
                    $imagen2 = $rutaCarpeta . $nombreImg2;
                }

                // Llamar al modelo para insertar Producto + Competición + Imágenes
                $resultado = $modelo->añadirProductos($nombre, $id_equipo, $id_categoria, $descripcion, $precio, $fecha_alta,$anoEdicion, $caracteristicas, $imagen1, $imagen2,$tallas);

                if ($resultado) {
                    header("Location: index.php?action=GestionProductos&msj=ok");
                    exit();
                }
            }

            // CARGA DE DATOS PARA LA VISTA (GET)
            // Necesitas estos métodos en tus modelos para llenar los select del formulario
            $categorias = $modelo->obtenerCategorias(); // SELECT * FROM categorias
            $equipos = $modelo->obtenerEquipos();      // SELECT * FROM entidad_deportiva
            $tallas = $modelo->obtenerTallas();
            require_once("vista/productos/AnadirProductos.php");
        }

        public function EliminarStock(){//Metodo para eliminar Stock de los productos
            $this->checkAdmin();

            if(isset($_GET['id_producto']) && isset($_GET['id_talla'])){
                $id_producto=(int)$_GET['id_producto'];
                $id_talla=(int)$_GET['id_talla'];

                $modelo=new Productos();
                $modelo->eliminarStock($id_producto,$id_talla);

                header('Location: index.php?action=GestionProductos');
                exit();
            }
        }

        public function GestionCategorias(){//Metodo para mostrar las categorias
            $this->checkAdmin();

            $modelo=new Productos();

            //Configuracion de la paginacion
            $categorias = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$categorias;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarCategorias();
            $totalPaginas = ceil($totalProductos / $categorias);

            //Obtenemos todos las Categorias de la pagina
            $categoriasPag=$modelo->ListarCategorias($inicio,$categorias);

            //Cargar la vista con las Categorias
            require_once "vista/productos/GestionCategorias.php";
        }

        public function AnadirCategoria(){//Metodo para añadir categorias
            $this->checkAdmin();

            $modelo=new Productos();
            $deportes=$modelo->ListarDeportes();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $prenda=$_POST['prenda'];
                $descripcion=$_POST['desc'];
                $deporte=$_POST['deporte'];


                $modelo->añadirCategoria($prenda,$descripcion,$deporte);

                header("Location: index.php?action=GestionCategorias");//Redirige a la gestion de categorias
                exit();//Finaliza la ejecucion del script para que ocurra la redireccion
            }
            require_once("vista/productos/AnadirCategorias.php");
        }

        public function EliminarCategoria() {//Metodo para eliminar categorias
            $this->checkAdmin();
            // Verificamos que el ID llegue por la URL
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id']; // Forzamos a entero por seguridad

                $modelo = new Productos();
                
                //Llamamos al método del modelo
                $modelo->eliminarCategoria($id);

                // Redirigimos a la tabla de tallas con un mensaje (opcional)
                header("Location: index.php?action=GestionCategorias&mensaje=eliminado");
                exit();
            }
        }

        public function anadirDeporte(){//Metodo para añadir Deporte
            $this->checkAdmin();
            $modelo=new Productos();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $deporte=$_POST['deporte'];

                $modelo->añadirDeportes($deporte);

                header("Location: index.php?action=GestionCategorias");
                exit();
            }
            require_once("vista/productos/AnadirDeportes.php");
        }

        public function GestionEquipos(){//Metodo para Ver equipos
            if(!isset($_SESSION['ROL']) || $_SESSION['ROL'] !== "admin"){
                header("Location: index.php?action=inicio");
                exit();
            }
            $modelo=new Productos();

            //Configuracion de la paginacion
            $equipos = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$equipos;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarEquipos();
            $totalPaginas = ceil($totalProductos / $equipos);

            //Obtenemos todos los Equipos de la pagina
            $equiposPag=$modelo->ListarEquipos($inicio,$equipos);

            //Cargar la vista con los Equipos
            require_once "vista/productos/GestionEquipos.php";
        }

        public function AnadirEquipo() {//Metodo para añadir equipos
            $this->checkAdmin();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Verificamos si realmente se subió un archivo y no hay errores
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    
                    $nombre = $_POST['nombre_equipo'];
                    $nombreImg = $_FILES['imagen']['name'];
                    $rutaTemporal = $_FILES['imagen']['tmp_name'];
                    
                    // Creamos la ruta
                    $carpetaDestino = "assets/img/equipos/";
                    
                    // Si la carpeta no existe, la creamos
                    if (!file_exists($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }

                    $rutaFinal = $carpetaDestino . time() . "_" . $nombreImg;

                    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                        $modelo = new Productos();
                        $modelo->añadirEquipo($nombre, $rutaFinal);
                        header("Location: index.php?action=GestionEquipos");
                        exit();
                    } else {
                        die("Error: No se pudo mover el archivo a la carpeta destino. Revisa permisos.");
                    }
                } else {
                    die("Error: No se ha seleccionado ninguna imagen o el archivo es demasiado grande.");
                }
            }
            require_once "vista/productos/AnadirEquipos.php";
        }

        public function GestionSelecciones(){//Metodo para añadir Selecciones
            $this->checkAdmin();

            $modelo=new Productos();

            //Configuracion de la paginacion
            $selecciones = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$selecciones;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarSelecciones();
            $totalPaginas = ceil($totalProductos / $selecciones);

            //Obtenemos todos las Selecciones de la pagina
            $seleccionesPag=$modelo->ListarSelecciones($inicio,$selecciones);

            //Cargar la vista con las Selecciones
            require_once "vista/productos/GestionSelecciones.php";
        }

        public function AnadirSeleccion() {//Metodo para añadir Selecciones
            $this->checkAdmin();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Verificamos si realmente se subió un archivo y no hay errores
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    
                    $nombre = $_POST['nombre_seleccion'];
                    $nombreImg = $_FILES['imagen']['name'];
                    $rutaTemporal = $_FILES['imagen']['tmp_name'];
                    
                    // Creamos la ruta
                    $carpetaDestino = "assets/img/equipos/";
                    
                    // Si la carpeta no existe, la creamos
                    if (!file_exists($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }

                    $rutaFinal = $carpetaDestino . time() . "_" . $nombreImg;

                    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                        $modelo = new Productos();
                        $modelo->añadirSeleccion($nombre, $rutaFinal);
                        header("Location: index.php?action=GestionSelecciones");
                        exit();
                    } else {
                        die("Error: No se pudo mover el archivo a la carpeta destino. Revisa permisos.");
                    }
                } else {
                    die("Error: No se ha seleccionado ninguna imagen o el archivo es demasiado grande.");
                }
            }
            require_once "vista/productos/AnadirSelecciones.php";
        }

        public function EliminarED() {//Metodo para eliminar una Entidad Deportiva sin inmportar que sea Equipo o Selecion
            $this->checkAdmin();

            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                $origen = $_GET['from'] ??''; // Usamos un parámetro extra para saber a dónde volver

                $modelo = new Productos();
                $modelo->eliminarEntidadDeportiva($id);

                // Redirección dinámica
                if ($origen === 'GestionSelecciones') {//Si se elimina la entidad desde GestionSelecciones se redirigira a la susodicha
                    header("Location: index.php?action=GestionSelecciones");
                } else {//Si no se redirigira a GestionEquipos
                    header("Location: index.php?action=GestionEquipos");
                }
                exit();
            }
        }

        public function GestionTemporadas(){//Metodo para gestionar Temporadas
            $this->checkAdmin();

            $modelo=new Productos();

            //Configuracion de la paginacion
            $temporadas = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$temporadas;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarTemporadas();
            $totalPaginas = ceil($totalProductos / $temporadas);

            //Obtenemos todos las COmpeticiones y Logos de la pagina
            $temporadasPag=$modelo->ListarTemporadas($inicio,$temporadas);

            //Cargar la vista con las COmpeticiones y Logos
            require_once "vista/productos/GestionTemporadas.php";
        }

        public function AnadirCompeticiones(){//Metodo para añadir Competiciones
            $this->checkAdmin();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nombre = $_POST['comp'];
                $tipo=$_POST['tipo'];

                $modelo=new Productos();
                $modelo->añadirCompeticion($nombre,$tipo);

                header("Location: index.php?action=AnadirLogos");
                exit();
            }
            require_once "vista/productos/AnadirCompeticiones.php";
        }

        public function AnadirLogos() {//Metdod para añadir Logos
            $this->checkAdmin();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // 1. Ver si $_FILES tiene algo
                if (empty($_FILES)) {
                    die("Error: El array \$_FILES está vacío. Esto suele ser porque falta el 'enctype' en el <form>.");
                }

                // 2. Ver si hay un error específico
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                    die("Error de PHP al subir: " . $_FILES['imagen']['error'] . 
                        " (Si es 4, es que no llegó el archivo. Si es 1, es tamaño).");
                }

                // 3. Si todo parece bien, intentar moverlo
                if (isset($_FILES['imagen'])) {
                    $nombreImg = $_FILES['imagen']['name'];
                    $rutaTemporal = $_FILES['imagen']['tmp_name'];
                    $carpetaDestino = "assets/img/parches/";

                    if (!file_exists($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }

                    $rutaFinal = $carpetaDestino . time() . "_" . $nombreImg;

                    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                        $modelo = new Productos();
                        $modelo->añadirParche($rutaFinal);
                        header("Location: index.php?action=AsignarEquipos");
                        exit();
                    } else {
                        die("Error: No se pudo mover el archivo. ¿Existe la carpeta assets/img/parches/?");
                    }
                }
            }
            require_once "vista/productos/AnadirLogos.php";
        }

        public function AsignarEquipos() {
            $this->checkAdmin();
            $modelo = new Productos();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id_comp = $_POST['id_competicion'];
                $id_logo = $_POST['id_logo'];
                $anio = $_POST['anio_edicion'];
                $id_equipo = ($_POST['id_equipo'] == 'todos') ? null : $_POST['id_equipo'];
                
                $parche_especial = null;

                // Lógica para el parche especial (Subida de archivo opcional)
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $carpetaDestino = "assets/img/parches/";
                    if (!file_exists($carpetaDestino)) {//Se crea la carpeta de destino si no existe
                        mkdir($carpetaDestino, 0777, true);
                    }
                    $nombreImg = time() . "_" . $_FILES['imagen']['name'];
                    $rutaFinal = $carpetaDestino . $nombreImg;

                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                        $parche_especial = $rutaFinal;
                    }
                }

                // Llamamos al método del modelo para asignar Equipos a competiciones y respectiva temporada
                $res = $modelo->asignarEquipos($id_comp, $id_equipo, $id_logo, $anio, $parche_especial);

                if ($res) {
                    header("Location: index.php?action=GestionTemporadas");
                    exit();
                } else {
                    die("Error al realizar la asignación en la tabla temporadas.");
                }
            }

            // Necesitamos los datos para llenar los select de la vista
            $competiciones = $modelo->listarCompeticiones();
            $logos = $modelo->listarParches();
            $equipos = $modelo->listarED();

            require_once "vista/productos/AsignarEquipos.php";
        }

        public function GestionTallas(){//Metodo para ver Tallas
            $this->checkAdmin();

            $modelo=new Productos();

            //Configuracion de la paginacion
            $tallas = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$tallas;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarTallas();
            $totalPaginas = ceil($totalProductos / $tallas);

            //Obtenemos todos las Tallas de la pagina
            $tallasPag=$modelo->ListarTallas($inicio,$tallas);

            //Cargar la vista con las Categorias
            require_once "vista/productos/GestionTallas.php";
        }

        public function AnadirTallas(){//Metodo para Añadir Tallas
            $this->checkAdmin();
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $talla=$_POST['talla'];

                $modelo=new Productos();

                $modelo->añadirTalla($talla);

                header("Location: index.php?action=GestionTallas");
                exit();
            }
            require_once("vista/productos/AnadirTallas.php");
        }

        public function EliminarTalla() {//Metodo para eliminar Tallas
            $this->checkAdmin();

            if (isset($_GET['id'])) {//Comprobamos que existe el id de la talla que vamos a eliminar
                $id = (int)$_GET['id'];
                
                $modelo = new Productos();//Llamamos al modelo y al metodo para eliminar
                $modelo->eliminarTalla($id);

                // Redirigir para limpiar la URL y actualizar la lista
                header("Location: index.php?action=GestionTallas");
                exit();
            }
        }

        public function GestionPedidos(){
            $this->checkAdmin();
            $modelo=new Productos();

            //Configuracion de la paginacion
            $pedidos = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$pedidos;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarPedidos();
            $totalPaginas = ceil($totalProductos / $pedidos);

            //Obtenemos todos las Tallas de la pagina
            $pedidosPag=$modelo->ListarPedidos($inicio,$pedidos);

            //Cargar la vista con las Categorias
            require_once "vista/productos/GestionPedidos.php";
        }
    }
?>