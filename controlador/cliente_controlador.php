<?php
    require_once "modelo/cliente_modelo.php";

    //Acceso a los archivos de FPDF
    require_once "fpdf/fpdf.php";
    
    //Excepciones del PHPMAILER
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Incluir las clases de PHPMailer
    require 'PHPMailer/PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer/PHPMailer.php';
    require 'PHPMailer/PHPMailer/SMTP.php';

    class cliente_controlador{
        public function inicio(){
            $this->comprobarRol();//Metodo para comprobar si el rol del user es un cliente
            require_once "vista/inicio.php";
        }

        private function comprobarCliente(){//Metodo privado que comprueba si el usuario esta regisrado y es un cliente
            if(!isset($_SESSION['id'])){//Comprobamos que las sesiones funcionan
                //Buscamos con el id de producto y el año de edicion
                if(isset($_SESSION['id_producto'],$_SESSION['anio_edi'])){
                    $id = $_SESSION['id_producto'];
                    $anio = $_SESSION['anio_edi']; 

                    if($id){
                        //Reconstruimos la URL de la VISTA (VerDetalle) y no la del PROCESO (agregarCarrito)
                        $urlRetorno = "index.php?action=VerDetalle&id=" . $id;
                        
                        if($anio){
                            $urlRetorno .= "&anio=" . $anio;
                        }
                        $_SESSION['url']=$urlRetorno;//Capturamos la url actual
                        unset($_SESSION['id_producto']);
                        unset($_SESSION['anio_edi']);
                    }else{
                        //Fallback: Si no hay ID de producto o año de edicion, guardamos la URL tal cual
                        $_SESSION['url'] = "index.php?" . $_SERVER['QUERY_STRING'];
                    }
                }
                
                $_SESSION['error_val']="¡Debes iniciar sesion antes de dejar tu valoracion o agregar productos al carrito!";
                header("Location: index.php?action=login");
                exit();
            }
            
            $this->comprobarRol();//Comprobamos que el rol es el de cliente o administrador
        }

        private function comprobarRol(){//Metodo que comprueba si el rol es de cliente 
            if(isset($_SESSION['ROL'])){
                if($_SESSION['ROL']!=='cliente'){//Si no es un cliente se redirige al menu de administrador
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
                'nombre'=>$_GET['nombre'] ?? null,
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
                //Prueba para guaradar el id de prodcuto y año de edicion en sesiones
                $_SESSION['id_producto']=$id;
                $_SESSION['anio_edi']=$anio;

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
                $valoraciones = $modelo->obtenerValoracionesPorProductos($id);
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
                $envio = 4.50;
                $total = $subtotal + $envio;
                $fecha = date('Y-m-d H:i:s');
                $estado = 'Pendiente';
                $id_user = $_SESSION['id'];

                $id_pedido=$modelo->registrarCompra($id_user, $fecha, $total, $estado, $direccion, $metodo_pago, $_SESSION['carrito']);

                if($id_pedido){//Si saca el id de pedido
                    $this->enviarCorreoPDF($id_pedido,$fecha,$total,$direccion);//Enviamos los datos del pedido para generar un correo y un PDF
                }
                //Eliminamos los datos en sesion del carrito y redirigimos a la confirmacion del pedido
                unset($_SESSION['carrito']);
                header("Location: index.php?action=pedidoConfirmado");
                exit();
            }

            require_once "vista/clientes/procesarCompra.php";
        }

        private function enviarCorreoPDF($id_pedido,$fecha,$total,$direccion,){//Funcion que enviará un correo y un PDF
            $modelo = new Cliente();
            $detalles = $modelo->obtenerDetallesPedido($id_pedido);

            $pdf = new FPDF();
            $pdf->AddPage();

            //LOGO (Arriba a la izquierda)
            if (file_exists("assets/img/FurboshirtsLogo.png")) {
                $pdf->Image("assets/img/FurboshirtsLogo.png", 10, 10, 45);
            }

            //TÍTULO DE FACTURA (Alineado a la derecha del logo)
            $pdf->SetY(15);
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->SetTextColor(26, 82, 36); // Verde oscuro
            $pdf->Cell(0, 10, utf8_decode("FACTURA OFICIAL"), 0, 1, 'R');
            $pdf->Ln(15); // Espacio después del encabezado

            //DATOS DEL PEDIDO (Cuadrados)
            $pdf->SetTextColor(30, 35, 48);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(100, 7, utf8_decode("INFORMACIÓN DEL PEDIDO:"), 0, 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 7, utf8_decode("Nº Pedido: #$id_pedido"), 0, 1, 'R');

            $pdf->Cell(100, 6, "Fecha: $fecha", 0, 0);
            $pdf->Cell(0, 6, "Estado: Pagado", 0, 1, 'R');

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode("Dirección de Envío:"), 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(0, 5, utf8_decode($direccion), 0, 'L');
            $pdf->Ln(10);

            //CABECERA DE TABLA (Verde Oscuro corregido)
            $pdf->SetFillColor(26, 82, 36); // Verde oscuro (#1a5224)
            $pdf->SetTextColor(255, 255, 255); // Texto Blanco
            $pdf->SetDrawColor(200, 200, 200); // Bordes gris suave
            $pdf->SetFont('Arial', 'B', 10);

            // Anchos ajustados para sumar 190mm (ancho total de página A4 menos márgenes)
            $pdf->Cell(75, 8, 'Producto', 1, 0, 'C', true);
            $pdf->Cell(20, 8, 'Talla', 1, 0, 'C', true);
            $pdf->Cell(30, 8, 'Parche', 1, 0, 'C', true);
            $pdf->Cell(15, 8, 'Cant.', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'Precio Un.', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'Subtotal', 1, 1, 'C', true);

            //CUERPO DE LA TABLA
            $pdf->SetTextColor(30, 35, 48);
            $pdf->SetFont('Arial', '', 9);
            $fill = false;

            foreach($detalles as $det) {
                $subtotal = $det['CANTIDAD'] * $det['PRECIO_UNITARIO'];
                
                // Color de fondo alterno para filas (opcional, muy profesional)
                $pdf->SetFillColor(245, 245, 245); 
                
                $pdf->Cell(75, 7, utf8_decode($det['NOMBRE_PRODUCTO']), 1, 0, 'L', $fill);
                $pdf->Cell(20, 7, $det['TALLA'], 1, 0, 'C', $fill);
                $pdf->Cell(30, 7, utf8_decode($det['PARCHE']), 1, 0, 'C', $fill);
                $pdf->Cell(15, 7, $det['CANTIDAD'], 1, 0, 'C', $fill);
                $pdf->Cell(25, 7, number_format($det['PRECIO_UNITARIO'], 2) . " EUR", 1, 0, 'R', $fill);
                $pdf->Cell(25, 7, number_format($subtotal, 2) . " EUR", 1, 1, 'R', $fill);
                
                $fill = !$fill; 
            }

            // TOTAL FINAL
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(26, 82, 36);
            $pdf->Cell(165, 10, "TOTAL FINAL (IVA inc.): ", 0, 0, 'R');
            
            $pdf->SetFillColor(252, 246, 237); // Crema suave
            $pdf->Cell(25, 10, number_format($total, 2) . " EUR", 0, 1, 'R', true);

            // PIE DE PÁGINA
            $pdf->SetY(-30);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->SetTextColor(128, 148, 166);
            $pdf->Cell(0, 10, utf8_decode("Gracias por comprar en Furboshirts. Este documento sirve como comprobante de compra."), 0, 0, 'C');

            // --- GUARDADO Y ENVÍO (Tu lógica actual) ---
            $nombreArchivo = "factura_" . $id_pedido . ".pdf";
            $carpetaDestino = "assets/facturas/";
            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
            $rutaFisica = $carpetaDestino . $nombreArchivo;
            $pdf->Output('F', $rutaFisica);

            $modelo->registrarFacturas($id_pedido, $rutaFisica);

            //Envio del correo con PHPMailer
            $mail=new PHPMailer(true);

            try{
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = 'lopezreinarobledilloruben@gmail.com'; 
                $mail->Password   = 'qqwp zfzv agys nqfa'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('lopezreinarobledilloruben@gmail.com', 'Tienda Deportiva');
                $mail->addAddress($_SESSION['correo']); 

                // Adjuntar el archivo físico que guardamos en el paso 3
                $mail->addAttachment($rutaFisica);

                $mail->isHTML(true);
                $mail->Subject = utf8_decode("Confirmación de Pedido #$id_pedido");
                $mail->Body    = "<h1>¡Gracias por tu compra!</h1><p>Adjunto encontrarás la factura oficial de tu pedido.</p>";

                $mail->send();
            }catch(Exception $e){
                die("Error al enviar el correo".$e->getMessage());
            }
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

                if ($modelo->yaHaValorado($id_usuario, $id_producto)) {
                    $_SESSION['mensaje'] = "Ya has valorado este producto anteriormente.";
                    header("Location: index.php?action=verDetalle&id=$id_producto&anio=$anio");
                    exit();
                }

                $resultado=$modelo->insertarValoracion($id_producto, $id_usuario, $puntuacion, $comentario);

                if ($resultado) {
                    $_SESSION['mensaje'] = $resultado;
                } else {
                    $_SESSION['mensaje'] = "Error al guardar la valoración.";
                }

                // Redirigir de vuelta al producto
                header("Location: index.php?action=VerDetalle&id=$id_producto&anio=$anio");
                exit();
            }
            //Fallback: Si alguien entra aquí mal, lo mandamos al catálogo
            header("Location: index.php?action=mostrarCatalogo");
        }

        public function VerPedidos(){//Metodo para ver los Pedidos de los clientes
            $this->comprobarCliente();//Comprobamos que el usario este registrado al meter una valoracion

            $modelo=new Cliente();

            //Configuracion de la paginacion
            $pedidos = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$pedidos;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarPedidos($_SESSION['id']);
            $totalPaginas = ceil($totalProductos / $pedidos);

            //Obtenemos todos las Tallas de la pagina
            $pedidosPag=$modelo->mostrarPedidos($inicio,$pedidos,$_SESSION['id']);

            require_once "vista/clientes/verPedidos.php";
        }

        public function Vervaloraciones(){//Metodo para ver las valoraciones de los clientes
            $this->comprobarCliente();//Comprobamos que el usario este registrado al meter una valoracion

            $modelo=new Cliente();

            //Configuracion de la paginacion
            $valoraciones = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$valoraciones;

            //Obtener los datos del modelo
            $totalProductos = $modelo->ContarValoraciones($_SESSION['id']);
            $totalPaginas = ceil($totalProductos / $valoraciones);

            //Obtenemos todos las Tallas de la pagina
            $valoracionesPag=$modelo->mostrarValoraciones($inicio,$valoraciones,$_SESSION['id']);

            require_once "vista/clientes/verValoraciones.php";
        }
    }
?>