<?php
    require_once "modelo/conexion.php";

    class Cliente{
        private $db;
        public function __construct(){//Contructor para conectar a la Base de Datos
            $this->db=Conexion::conexion();   
        }

        public function obtenerCatalogo($inicio, $cantidad, $filtros=[]) {
            try {
                $sql = "SELECT DISTINCT p.*,
                        (SELECT RUTA FROM imagenes WHERE ID_PRODUCTO = p.ID_PRODUCTO ORDER BY ID_IMAGEN ASC LIMIT 1) AS IMAGEN_PRINCIPAL 
                        FROM productos p 
                        INNER JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO
                        LEFT JOIN productos_competiciones pc ON p.ID_PRODUCTO=pc.ID_PRODUCTO
                        LEFT JOIN competiciones c ON pc.ID_COMP=c.ID_COMP
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
                
                $sql .= " LIMIT :inicio, :cantidad";
                
                $stmt = $this->db->prepare($sql);
                
                if (!empty($filtros['tipo'])) {//Ponemos parametros si hay un tipo de equipo
                    $stmt->bindValue(':tipo', $filtros['tipo'], PDO::PARAM_STR);
                }

                if (!empty($filtros['id_comp'])) {//Ponemos parametros si hay se pide una competencia especifica
                    $stmt->bindValue(':id_comp', $filtros['id_comp'], PDO::PARAM_INT);
                }

                if (!empty($filtros['id_equipo'])) {//Si se especifica por Entidad Deportiva
                    $stmt->bindValue(':id_equipo', $filtros['id_equipo'], PDO::PARAM_INT);
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
        
    }
?>