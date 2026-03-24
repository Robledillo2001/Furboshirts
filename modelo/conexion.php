<?php
    class Conexion{
        public static function conexion(){
            $conexion=new PDO("mysql:host=localhost;dbname=claveXP;charset=utf8","root","");
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        }
    }
?>