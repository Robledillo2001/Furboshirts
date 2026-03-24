<?php
    require_once "modelo/conexion.php";

    class Cliente{
        private $db;
        public function __construct(){//Contructor para conectar a la Base de Datos
            $this->db=Conexion::conexion();   
        }

        public function obtenerCatalogo(){//Metodo para obtener el catalogo
            try{
                $sql="SELECT p*,
                    (SELECT RUTA FROM imagenes WHERE ID_PRODUCTO = p.ID_PRODUCTO ORDER BY ID_IMAGEN ASC LIMIT 1) AS IMAGEN_PRINCIPAL
                    FROM productos p";
                $stmt=$this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al sacar catalogo: ".$e->getMessage());
            }
        } 
    }
?>