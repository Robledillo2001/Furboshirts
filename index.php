<?php
    require_once "controlador/usuario_controlador";
    require_once "controlador/producto_controlador";
    require_once "controlador/carrito_controlador";
    require_once "controlador/pedido_controlador";

    session_start();

    $controladores=[];

    $action=$_GET['action']??"inicio";

    if(isset($controladores[$action])&&method_exists($controladores[$action],$action)){
        $controladores[$action]->$action();
    }else{
        echo "<h2>No se puede acceder a la Vista o no existe :(";
    }
?>