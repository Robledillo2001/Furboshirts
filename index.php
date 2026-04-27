<?php
    error_reporting(E_ERROR | E_PARSE);
    //Archivos con cada uno de los controladores
    require_once "controlador/usuarios_controlador.php";
    require_once "controlador/productos_controlador.php";
    require_once "controlador/cliente_controlador.php";

    session_start();

    $usuario=new usuarios_controlador;
    $productos=new productos_controlador;
    $cliente=new cliente_controlador;

   $controladores=[//Array con los controladores de que realiza cada accion
    "inicio"=>$cliente,
    "mostrarCatalogo"=>$cliente,
    "VerDetalle"=>$cliente,
    "guardarValoracion"=>$cliente,
    "verCarrito"=>$cliente,
    "procesarPedido"=>$cliente,
    "agregarCarrito"=>$cliente,
    "eliminarDelCarrito"=>$cliente,
    "vaciarCarrito"=>$cliente,
    "procesarCompra"=>$cliente,
    "pedidoConfirmado"=>$cliente,
    "actualizarCantidad"=>$cliente,
    "VerPedidos"=>$cliente,
    "Vervaloraciones"=>$cliente,
    "GestionProductos"=>$productos,
    "AnadirProducto"=>$productos,
    "EditarProducto"=>$productos,
    "EliminarStock"=>$productos,
    "GestionCategorias"=>$productos,
    "AnadirCategoria"=>$productos,
    "EditarCategoria"=>$productos,
    "EliminarCategoria"=>$productos,
    "AnadirDeporte"=>$productos,
    "GestionTallas"=>$productos,
    "AnadirTallas"=>$productos,
    "EditarTallas"=>$productos,
    "EliminarTalla"=>$productos,
    "GestionEquipos"=>$productos,
    "AnadirEquipo"=>$productos,
    "GestionSelecciones"=>$productos,
    "AnadirSeleccion"=>$productos,
    "EditarED"=>$productos,
    "EliminarED"=>$productos,
    "GestionTemporadas"=>$productos,
    "AnadirCompeticiones"=>$productos,
    "MostrarCompeticiones"=>$productos,
    "EliminarCompeticiones"=>$productos,
    "EditarCompeticiones"=>$productos,
    "EliminarTemporada"=>$productos,
    "EditarTemporada"=>$productos,
    "AnadirLogos"=>$productos,
    "MostrarLogos"=>$productos,
    "EditarLogos"=>$productos,
    "EliminarLogos"=>$productos,
    "AsignarEquipos"=>$productos,
    "GestionPedidos"=>$productos,
    "EditarPedidos"=>$productos,
    "EliminarPedidos"=>$productos,
    "MenuAdmin"=>$usuario,
    "GestionAdmin"=>$usuario,
    "AnadirAdmin"=>$usuario,
    "GestionClientes"=>$usuario,
    "EliminarUsuario"=>$usuario,
    "login"=>$usuario,
    "configuracion"=>$usuario,
    "registrar"=>$usuario,
    "logout"=>$usuario,
    "EditarPerfil"=>$usuario,
    "EditarUsuario"=>$usuario,
    "CambiarIMGPerfil"=>$usuario,
   ];

    $action=$_GET['action']??"inicio";

    if(isset($controladores[$action])&&method_exists($controladores[$action],$action)){
        $controladores[$action]->$action();
    }else{
        echo "<h2>No se puede acceder a la Vista o no existe :(";
    }
?>
