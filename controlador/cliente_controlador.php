<?php
    require_once "modelo/cliente_modelo.php";
    class cliente_controlador{
        public function inicio(){
            $this->comprobarRol();//Metodo para comprobar si el rol del user es un cliente
            require_once "vista/inicio.php";
        }

        private function comprobarCliente(){//Metodo privado que comprueba si el usuario esta regisrado y es un cliente
            if(!isset($_SESSION['id'])){
                $_SESSION['error_val']="¡Debes iniciar sesion antes de dejar tu valoracion o agregar productos al carrito!";
                header("Location: index.php?action=login");
                exit();
            }
            
            $this->comprobarRol();
        }

        private function comprobarRol(){
            if(isset($_SESSION['ROL'])){
                if($_SESSION['ROL']!=='cliente'){
                    header("Location: index.php?action=MenuAdmin");
                    exit();
                }
            }
        }

        public function mostrarCatalogo() {
            //$this->comprobarRol();//Metodo para comprobar si el rol del user es un cliente
            //Instanciamos el modelo Cliente que creamos antes
            $modelo = new Cliente();

            // Configuración de la paginación
            $productosPorPagina = 10; 

            // Capturamos el tipo de la URL (ej: catalogo&tipo=Equipo)
            $filtros=[
                'tipo'=>$_GET['tipo']??null,
                'id_comp'=>$_GET['id_comp']??null,
                'id_equipo'=>$_GET['id_equipo']??null,
                'id_cat'=>$_GET['id_cat']??null,
                'id_deporte'=>$_GET['id_deporte']??null,
                'ano_edicion'=>$_GET['ano_edicion']??null,
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
            $categorias = $modelo->listarCategorias();
            $deportes = $modelo->listarDeportes();
            $anios = $modelo->listarAnios();

            //Cargar la vista del catálogo para el cliente
            require_once "vista/clientes/catalogo.php";
        }

        public function VerDetalle() {
            $this->comprobarRol();//Metodo para comprobar si el rol del user es un cliente
            // Instanciar el modelo
            $modelo = new Cliente();

            // Validar que recibimos los parámetros necesarios por URL
            if (isset($_GET['id']) && isset($_GET['anio'])) {
                $id = intval($_GET['id']);
                $anio = $_GET['anio'];

                // Llamada al modelo con la nueva consulta (incluye tallas y valoraciones)
                $resultado = $modelo->verDetalle($id, $anio);

                //Si no hay resultados, redirigir al catálogo
                if (!$resultado) {
                    header("Location: index.php?action=mostrarCatalogo");
                    exit();
                }

                //Inicializar contenedores para limpiar los datos duplicados del JOIN
                $producto = $resultado[0]; // Datos básicos del producto
                $imagenes = [];
                $parches = [];
                $tallas = [];
                $valoracion_promedio = $producto['VALORACION_PROMEDIO'] ?? 0; // Extraer la media
                $prenda=$modelo->comprobarCaTegoria($producto['ID_CAT']);//Llamamos a la categoria para comprobar el tipo de prenda

                //Procesar el array de resultados
                foreach ($resultado as $fila) {
                    //Guardar imágenes únicas
                    if (!empty($fila['RUTA_IMAGEN']) && !in_array($fila['RUTA_IMAGEN'], $imagenes)) {
                        $imagenes[] = $fila['RUTA_IMAGEN'];
                    }
                    
                    //Guardar parches únicos (Solo si el equipo tiene competición asignada este año)
                    if (!empty($fila['NOMBRE_COMP']) && !empty($fila['RUTA_PARCHE'])) {
                        if (!isset($parches[$fila['NOMBRE_COMP']])) {
                            $parches[$fila['NOMBRE_COMP']] = [
                                'nombre_comp' => $fila['NOMBRE_COMP'],
                                'ruta_parche' => $fila['RUTA_PARCHE'],
                                'especial'    => $fila['PARCHE_ESPECIAL']
                            ];
                        }
                    }
                    
                    //Guardar tallas y comprobamos que su stock sea mayor a 0
                    if (!empty($fila['NOMBRE_TALLA'])) {
                        if($fila['STOCK_TALLA']>0){
                            $tallas[$fila['NOMBRE_TALLA']] = (int)$fila['ID_TALLA'];//Guardamos el nombre de la talla con el id de la misma comom valor
                        }
                    }

                }

                //Cargar la vista pasándole todas las variables procesadas
                require_once "vista/clientes/verDetalle.php";

            } else {
                // Si faltan parámetros en la URL, devolvemos al usuario al catálogo
                header("Location: index.php?action=mostrarCatalogo");
                exit();
            }
        }

        public function agregarCarrito(){//Metodo que comprueba que se meten productos al carrito

            $this->comprobarCliente();//Comprobamos que el usario este registrado al meter productos al carrito

            if($_SERVER['REQUEST_METHOD']=='POST'){
                $id=$_POST['id_producto'];
                $nombre_producto=$_POST['nombre_p'];
                $precio=$_POST['precio'];
                $imagen=$_POST['imagen'];
                $talla=$_POST['talla'];
                $parche=$_POST['parche_id'];
                $nombre_personalizado=trim($_POST['nombre_personalizado']?? "")?:"Sin nombre";
                $numero=trim($_POST['numero_personalizado']?? "")?:"S/N";

                //Creamos el un array con los elementos del prodcuto para meterlo al carrito
                $producto=[
                    'id'=>$id,
                    'nombre_producto'=>$nombre_producto,
                    'talla'=>$talla,
                    'nombre_personalizado'=>$nombre_personalizado,
                    'parche'=>$parche,
                    'numero'=>$numero,
                    'precio'=>$precio,
                    'imagen'=>$imagen,
                    'cantidad'=>1
                ];

                if(!isset($_SESSION['carrito'])){//Creamos una sesion para meter el producto a comprar en el carrito
                    $_SESSION['carrito']=[];
                }

                $_SESSION['carrito'][]=$producto;
                header("Location: index.php?action=verCarrito");
                exit();
            }
        }

        public function eliminarDelCarrito() {//Metodo para eliminar una posicion del carrito
            $indice = $_GET['indice']; // El número de posición en el array

            if (isset($_SESSION['carrito'][$indice])) {
                unset($_SESSION['carrito'][$indice]);
                // Reindexamos el array para que no queden huecos (0, 1, 3...)
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }

            header("Location: index.php?action=verCarrito");
            exit();
        }

        public function verCarrito(){//Metodo para eliminar el carrito completo
            $this->comprobarCliente();//Comprobamos que el usario este registrado ver el carrito
            require_once"vista/clientes/verCarrito.php";
        }

        public function actualizarCantidad(){//Metodo que se usa para cambiar la cantidad de un producto especifico
            $this->comprobarCliente();//Comprobamos que el usario este registrado ver el carrito
            if (isset($_GET['indice']) && isset($_GET['cantidad'])) {
                $indice = intval($_GET['indice']);
                $cantidad = intval($_GET['cantidad']);

                if (isset($_SESSION['carrito'][$indice]) && $cantidad > 0) {
                    $_SESSION['carrito'][$indice]['cantidad'] = $cantidad;
                }
            }
            // Redirigimos de vuelta al carrito para que se actualicen los totales
            header("Location: index.php?action=verCarrito");
            exit();
        }

        public function vaciarCarrito(){
            $this->comprobarCliente();//Comprobamos que el usario este registrado al vaciar el carrito
            if(isset($_SESSION['carrito'])){
                unset ($_SESSION['carrito']);
            }
            header("Location: index.php?action=verCarrito");
        }

        public function procesarCompra(){
            $this->comprobarCliente();
            $modelo = new Cliente();
            $subtotal = 0;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $direccion = trim($_POST['direccion'] ?? '');
                $metodo_pago = $_POST['metodo_pago'] ?? 'Tarjeta';

                if (empty($direccion) || empty($_SESSION['carrito'])) {
                    header("Location: index.php?action=verCarrito");
                    exit();
                }

                foreach ($_SESSION['carrito'] as $i) {
                    $subtotal += $i['precio'] * ($i['cantidad'] ?? 1);
                }
                $envio = 5.00;
                $total = $subtotal + $envio;
                $fecha = date('Y-m-d H:i:s');
                $estado = 'Pendiente';
                $id_user = $_SESSION['id'];

                $modelo->registrarCompra($id_user, $fecha, $total, $estado, $direccion, $metodo_pago, $_SESSION['carrito']);

                unset($_SESSION['carrito']);
                header("Location: index.php?action=pedidoConfirmado");
                exit();
            }

            require_once "vista/clientes/procesarCompra.php";
        }

        public function pedidoConfirmado(){
            $this->comprobarCliente();
            require_once "vista/clientes/pedidoConfirmado.php";
        }

        public function guardarValoracion(){//Metodo para guardar las valoraciones de los clientes de cada producto
            $modelo = new Cliente();

            $this->comprobarCliente();//Comprobamos que el usario este registrado al meter una valoracion

            if($_SERVER['REQUEST_METHOD']=='POST' &&isset($_GET['id'],$_GET['anio'])){
                $id_producto = $_GET['id'];
                $anio=$_GET['anio'];
                $puntuacion  = $_POST['puntuacion']??null;
                $comentario  = $_POST['comentario']??'';
                $id_usuario  = $_SESSION['id']; // Usamos tu variable de sesión

                $modelo->insertarValoracion($id_producto, $id_usuario, $puntuacion, $comentario);
                // Redirigir de vuelta al producto
                header("Location: index.php?action=verDetalle&id=$id_producto&anio=$anio");
            }
            //Fallback: Si alguien entra aquí mal, lo mandamos al catálogo
            header("Location: index.php?action=mostrarCatalogo");
        }
    }
?>