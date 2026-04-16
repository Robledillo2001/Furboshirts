<?php
    require_once "modelo/conexion.php";

    class Cliente{
        private $db;
        public function __construct(){//Contructor para conectar a la Base de Datos
            $this->db=Conexion::conexion();   
        }


        //Metodos para mostrar el catalogo
        public function obtenerCatalogo($inicio, $cantidad, $filtros=[]) {
            try {
                $sql = "SELECT DISTINCT p.*,
                        c.PRENDA, 
                        d.DEPORTE,
                        (SELECT RUTA FROM imagenes WHERE ID_PRODUCTO = p.ID_PRODUCTO ORDER BY ID_IMAGEN ASC LIMIT 1) AS IMAGEN_PRINCIPAL,
                        (SELECT AVG(puntuacion)FROM valoracion WHERE ID_PRODUCTO=p.ID_PRODUCTO) AS MEDIA_VALORACION
                        FROM productos p 
                        INNER JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO
                        LEFT JOIN categorias c ON p.ID_CAT = c.ID_CAT
                        LEFT JOIN deportes d ON p.ID_DEPORTE = d.ID_DEPORTE
                        LEFT JOIN productos_competiciones pc ON p.ID_PRODUCTO=pc.ID_PRODUCTO
                        WHERE 1=1";//Consulta que muestra los productos disponibles del catalogo
                
                // Si se especifica un tipo (Equipo o Seleccion), añadimos el filtro
                if (!empty($filtros['tipo'])) {
                    $sql .= " AND e.TIPO = :tipo";
                }
                //Si se especifica competiciones de cada competición
                if (!empty($filtros['id_comp'])) {
                    $sql .= " AND pc.ID_COMP = :id_comp";
                }
                //Si se especifica por Entidad Deportiva
                if (!empty($filtros['id_equipo'])) {
                    $sql .= " AND p.ID_EQUIPO = :id_equipo";
                }

                //Si se especifica por Entidad Deportiva
                if (!empty($filtros['id_cat'])) {
                    $sql .= " AND p.ID_CAT = :id_cat";
                }

                //Si se especifica por Entidad Deportiva
                if (!empty($filtros['id_deporte'])) {
                    $sql .= " AND p.ID_DEPORTE = :id_deporte";
                }

                
                $sql .= " LIMIT :inicio, :cantidad";
                
                $stmt = $this->db->prepare($sql);
                //Lectura de parametros
                if (!empty($filtros['tipo'])) {//Ponemos parametros si hay un tipo de equipo
                    $stmt->bindValue(':tipo', $filtros['tipo'], PDO::PARAM_STR);
                }

                if (!empty($filtros['id_comp'])) {//Ponemos parametros si hay se pide una competencia especifica
                    $stmt->bindValue(':id_comp', $filtros['id_comp'], PDO::PARAM_INT);
                }

                if (!empty($filtros['id_equipo'])) {//Si se especifica por Entidad Deportiva
                    $stmt->bindValue(':id_equipo', $filtros['id_equipo'], PDO::PARAM_INT);
                }

                if (!empty($filtros['id_cat'])) {//Si se especifica una categoria especifica
                    $stmt->bindValue(':id_cat', $filtros['id_cat'], PDO::PARAM_INT);
                }

                if (!empty($filtros['id_deporte'])) {//Si se especifica un deporte
                    $stmt->bindValue(':id_deporte', $filtros['id_deporte'], PDO::PARAM_INT);
                }

                //Parametros para el inicio y la cantidad de cada pagina
                $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', (int)$cantidad, PDO::PARAM_INT);
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al sacar catalogo: " . $e->getMessage());
            }
        }

        public function contarTotalProductos($filtros = []) {
            try {
                // Base de la consulta con los JOINs necesarios para M:N
                $sql = "SELECT COUNT(DISTINCT p.ID_PRODUCTO) 
                        FROM productos p
                        INNER JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO
                        LEFT JOIN productos_competiciones pc ON p.ID_PRODUCTO = pc.ID_PRODUCTO
                        WHERE 1=1";

                // Construcción dinámica de la consulta (Tokens)
                if (!empty($filtros['tipo'])) {
                    $sql .= " AND e.TIPO = :tipo";
                }
                if (!empty($filtros['id_comp'])) {
                    $sql .= " AND pc.ID_COMP = :id_comp";
                }
                if (!empty($filtros['id_equipo'])) {
                    $sql .= " AND p.ID_EQUIPO = :id_equipo";
                }

                $stmt = $this->db->prepare($sql);

                // Vinculación de parámetros (Binds) 
                if (!empty($filtros['tipo'])) {
                    $stmt->bindValue(':tipo', $filtros['tipo'], PDO::PARAM_STR);
                }
                if (!empty($filtros['id_comp'])) {
                    $stmt->bindValue(':id_comp', $filtros['id_comp'], PDO::PARAM_INT);
                }
                if (!empty($filtros['id_equipo'])) {
                    $stmt->bindValue(':id_equipo', $filtros['id_equipo'], PDO::PARAM_INT);
                }

                $stmt->execute();
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                die("Error al contar productos: " . $e->getMessage());
            }
        }
        // Método para listar todas las competiciones disponibles
        public function listarCompeticiones() {
            try {
                $sql = "SELECT * FROM competiciones ORDER BY NOMBRE_COMP ASC";
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        // Método para listar entidades filtrando por TIPO (Equipo o Seleccion)
        public function listarEntidadesPorTipo($tipo) {
            try {
                $sql = "SELECT * FROM entidad_deportiva WHERE TIPO = :tipo ORDER BY NOMBRE_EQUIPO ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al listar entidades: " . $e->getMessage());
            }
        }

        //Metodo para listar las categorias
        public function listarCategorias(){
            try {
                $sql = "SELECT * FROM categorias ORDER BY PRENDA ASC";
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        //Metodo para listar los Deportes
        public function listarDeportes(){
            try {
                $sql = "SELECT * FROM deportes ORDER BY DEPORTE ASC";
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        //Ficha Tecnica de Producto
        public function verDetalle($id,$anio){//Metodo para ver el detalle de un producto
            try{
                /*Consulta Select para mostrar las imagenes disponibles de un producto 
                y los parches disponibles del equipo segun el año de edicion de la camiseta*/
                $sql="SELECT p.*, 
                       i.RUTA AS RUTA_IMAGEN, 
                       pa.PARCHE AS RUTA_PARCHE,
                       t_temp.PARCHE_ESPECIAL,
                       c.NOMBRE_COMP,
                       tal.ID_TALLA,
                       tal.TALLA AS NOMBRE_TALLA,
                       pt.STOCK_ESPECIFICO AS STOCK_TALLA,
                       (SELECT AVG(PUNTUACION) FROM valoracion WHERE ID_PRODUCTO = p.ID_PRODUCTO) AS VALORACION_PROMEDIO
                FROM productos p
                LEFT JOIN imagenes i ON p.ID_PRODUCTO = i.ID_PRODUCTO
                LEFT JOIN productos_competiciones pc ON p.ID_PRODUCTO = pc.ID_PRODUCTO
                LEFT JOIN competiciones c ON pc.ID_COMP = c.ID_COMP
                LEFT JOIN temporadas t_temp ON (p.ID_EQUIPO = t_temp.ID_EQUIPO 
                                           AND p.AÑO_EDICION = t_temp.AÑO_EDICION 
                                           AND pc.ID_COMP = t_temp.ID_COMP)
                LEFT JOIN parches pa ON t_temp.ID_LOGO = pa.ID_LOGO
                /* Unimos con tallas para saber disponibilidad */
                LEFT JOIN productos_tallas pt ON p.ID_PRODUCTO = pt.ID_PRODUCTO
                LEFT JOIN tallas tal ON pt.ID_TALLA = tal.ID_TALLA
                WHERE p.ID_PRODUCTO = :id
                AND p.AÑO_EDICION = :anio
                ORDER BY i.ID_IMAGEN ASC, tal.ID_TALLA ASC";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id",$id,PDO::PARAM_INT);
                 $stmt->bindParam(":anio",$anio,PDO::PARAM_STR);//SE GUARDO EN LA BSD COMO VARCHAR(4)

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al mostrar el Producto: ".$e->getMessage());
            }
        }

        public function insertarValoracion($id_prod,$id_user,$puntos,$comentario){//Metodo para añadir valoracion de un producto
            try{
                $sql="INSERT INTO valoracion (ID_PRODCUTO, ID_USUARIO, PUNTUACION, COMENTARIOS)
                        VALUES(:id_p,:id_u,:puntos,:comen)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id_p', $id_prod, PDO::PARAM_INT);
                $stmt->bindValue(':id_u', $id_user, PDO::PARAM_INT);
                $stmt->bindValue(':puntos', $puntos, PDO::PARAM_INT);
                $stmt->bindValue(':comen', $comentario, PDO::PARAM_STR);
                return $stmt->execute();
            }catch(PDOException $e){
                die("Error al guardar valoracion: ".$e->getMessage());
            }
        }
        
    }
?>