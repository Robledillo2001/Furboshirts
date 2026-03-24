<?php
    //Archivos con cada uno de los controladores
    require_once "controlador/usuarios_controlador.php";
    require_once "controlador/productos_controlador.php";
    require_once "controlador/cliente_controlador.php";

    session_start();//Iniciar sesion para guardar datos necesarios en un array de sesion al hacer login

    //$acciones_publicas=["inicio","login","registrar"];//Rutas las cuales no hace falta estar logueado con un usuario para (Pensar algo para cuando implementemos los productos en la tienda)

    /*if (in_array($currentAction, $accionesProtegidas) && !isset($_SESSION['admin'])) {
            header("Location: index.php?action=inicio");
            exit();
    }*/

    $usuario=new usuarios_controlador;
    $productos=new productos_controlador;
    $cliente=new cliente_controlador;

   $controladores=[//Array con los controladores de que realiza cada accion
    "inicio"=>$cliente,
    "GestionProductos"=>$productos,
    "AnadirProducto"=>$productos,
    "EliminarStock"=>$productos,
    "GestionCategorias"=>$productos,
    "AnadirCategoria"=>$productos,
    "EliminarCategoria"=>$productos,
    "AnadirDeporte"=>$productos,
    "GestionTallas"=>$productos,
    "AnadirTallas"=>$productos,
    "EliminarTalla"=>$productos,
    "GestionEquipos"=>$productos,
    "AnadirEquipo"=>$productos,
    "GestionSelecciones"=>$productos,
    "AnadirSeleccion"=>$productos,
    "EliminarED"=>$productos,
    "GestionTemporadas"=>$productos,
    "AnadirCompeticiones"=>$productos,
    "EliminarCompeticion"=>$productos,
    "AnadirLogos"=>$productos,
    "EliminarLogo"=>$productos,
    "AsignarEquipos"=>$productos,
    "GestionPedidos"=>$productos,
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
    "CambiarIMGPerfil"=>$usuario,
   ];

    $action=$_GET['action']??"inicio";

    if(isset($controladores[$action])&&method_exists($controladores[$action],$action)){
        $controladores[$action]->$action();
    }else{
        echo "<h2>No se puede acceder a la Vista o no existe :(";
    }
?>
