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

                if (!empty($filtros['id_deporte'])) {
                    $sql .= " AND p.ID_DEPORTE = :id_deporte";
                }

                if (!empty($filtros['ano_edicion'])) {
                    $sql .= " AND p.ANO_EDICION = :ano_edicion";
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

                if (!empty($filtros['id_deporte'])) {
                    $stmt->bindValue(':id_deporte', $filtros['id_deporte'], PDO::PARAM_INT);
                }

                if (!empty($filtros['ano_edicion'])) {
                    $stmt->bindValue(':ano_edicion', $filtros['ano_edicion'], PDO::PARAM_STR);
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
                $sql = "SELECT COUNT(DISTINCT p.ID_PRODUCTO)
                        FROM productos p
                        INNER JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO
                        LEFT JOIN productos_competiciones pc ON p.ID_PRODUCTO = pc.ID_PRODUCTO
                        WHERE 1=1";

                if (!empty($filtros['tipo'])) {
                    $sql .= " AND e.TIPO = :tipo";
                }
                if (!empty($filtros['id_comp'])) {
                    $sql .= " AND pc.ID_COMP = :id_comp";
                }
                if (!empty($filtros['id_equipo'])) {
                    $sql .= " AND p.ID_EQUIPO = :id_equipo";
                }
                if (!empty($filtros['id_cat'])) {
                    $sql .= " AND p.ID_CAT = :id_cat";
                }
                if (!empty($filtros['id_deporte'])) {
                    $sql .= " AND p.ID_DEPORTE = :id_deporte";
                }
                if (!empty($filtros['ano_edicion'])) {
                    $sql .= " AND p.ANO_EDICION = :ano_edicion";
                }

                $stmt = $this->db->prepare($sql);

                if (!empty($filtros['tipo'])) {
                    $stmt->bindValue(':tipo', $filtros['tipo'], PDO::PARAM_STR);
                }
                if (!empty($filtros['id_comp'])) {
                    $stmt->bindValue(':id_comp', $filtros['id_comp'], PDO::PARAM_INT);
                }
                if (!empty($filtros['id_equipo'])) {
                    $stmt->bindValue(':id_equipo', $filtros['id_equipo'], PDO::PARAM_INT);
                }
                if (!empty($filtros['id_cat'])) {
                    $stmt->bindValue(':id_cat', $filtros['id_cat'], PDO::PARAM_INT);
                }
                if (!empty($filtros['id_deporte'])) {
                    $stmt->bindValue(':id_deporte', $filtros['id_deporte'], PDO::PARAM_INT);
                }
                if (!empty($filtros['ano_edicion'])) {
                    $stmt->bindValue(':ano_edicion', $filtros['ano_edicion'], PDO::PARAM_STR);
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

        public function listarAnios(){
            try {
                $sql = "SELECT DISTINCT ANO_EDICION FROM productos ORDER BY ANO_EDICION DESC";
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                die("Error al listar años: " . $e->getMessage());
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
                                           AND p.ANO_EDICION = t_temp.ANO_EDICION
                                           AND pc.ID_COMP = t_temp.ID_COMP)
                LEFT JOIN parches pa ON t_temp.ID_LOGO = pa.ID_LOGO
                /* Unimos con tallas para saber disponibilidad */
                LEFT JOIN productos_tallas pt ON p.ID_PRODUCTO = pt.ID_PRODUCTO
                LEFT JOIN tallas tal ON pt.ID_TALLA = tal.ID_TALLA
                WHERE p.ID_PRODUCTO = :id
                AND p.ANO_EDICION = :anio
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

        public function comprobarCaTegoria($id){//Metodo para comprobar la categoria
            try{
                $sql="SELECT PRENDA FROM categorias WHERE ID_CAT =:id";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id",$id,PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchColumn();
            }catch(PDOException $e){
                die("Error al comprobar la categoria: ".$e->getMessage());
            }
        }

        public function registrarCompra($id_user,$fecha,$total,$estado,$direccion,$pago,$carrito){
            try{
                $this->db->beginTransaction();
                //Insertamos los pedidos
                $sql="INSERT INTO pedidos (ID_USUARIO,FECHA,TOTAL,ESTADO,DIRECCION_ENVIO,METODO_PAGO)
                        VALUES(:id_u,:fecha,:total,:estado,:direccion,:pago)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id_u', $id_user, PDO::PARAM_INT);
                $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindValue(':total', $total, PDO::PARAM_STR);
                $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
                $stmt->bindValue(':direccion', $direccion, PDO::PARAM_STR);
                $stmt->bindValue(':pago', $pago, PDO::PARAM_STR);
                
                $stmt->execute();
                $id_pedido=$this->db->lastInsertId();

                //Insertamos el detalle de cada pedido
                $sql="INSERT INTO detalles_pedido (ID_PEDIDO, ID_PRODUCTO, TALLA, PARCHE, CANTIDAD, PRECIO_UNITARIO, DORSAL, NOMBRE_PERSONALIZADO)
                       VALUES (:id_pedido, :id_prod, :talla, :parche, :cantidad, :precio, :dorsal, :nombre_p)";
                $stmt = $this->db->prepare($sql);

                foreach($carrito as $c){//Recorremos todo el carrito para añadirlo al detalle del pedido
                    $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
                    $stmt->bindValue(':id_prod', $c['id'], PDO::PARAM_INT);
                    $stmt->bindValue(':talla', $c['talla'], PDO::PARAM_STR);
                    $stmt->bindValue(':parche', $c['parche'] ?? 'Sin Parche', PDO::PARAM_STR);
                    $stmt->bindValue(':cantidad', $c['cantidad'] ?? 1, PDO::PARAM_INT);
                    $stmt->bindValue(':precio', $c['precio'], PDO::PARAM_STR);

                    $stmt->bindValue(':dorsal',    $c['numero'] ?? null, PDO::PARAM_STR);
                    $stmt->bindValue(':nombre_p',  $c['nombre_personalizado'] ?? null, PDO::PARAM_STR);

                    $stmt->execute();
                }

                $this->db->commit();
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al Guardar el pedido : ".$e->getMessage());
            }
        }

        public function insertarValoracion($id_prod,$id_user,$puntos,$comentario){//Metodo para añadir valoracion de un producto
            try{
                $this->db->beginTransaction();
                $sql="INSERT INTO valoracion (ID_USER,ID_PRODUCTO, PUNTUACION, COMENTARIOS)
                        VALUES(:id_u,:id_p,:puntos,:comen)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id_u', $id_user, PDO::PARAM_INT);
                $stmt->bindValue(':id_p', $id_prod, PDO::PARAM_INT);
                $stmt->bindValue(':puntos', $puntos, PDO::PARAM_INT);
                $stmt->bindValue(':comen', $comentario, PDO::PARAM_STR);
                $stmt->execute();

                $this->db->commit();

                return "¡Valoración guardada con éxito!";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al guardar valoracion: ".$e->getMessage());
            }
        }

        public function yaHaValorado($id_user, $id_prod) {//Metodo para comprobar que el usuario ya ha valorado
            $sql = "SELECT COUNT(*) FROM valoracion WHERE ID_USER = :id_u AND ID_PRODUCTO = :id_p";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_u' => $id_user, ':id_p' => $id_prod]);
            return $stmt->fetchColumn() > 0; // Devuelve true si ya existe
        }

        public function obtenerValoracionesPorProductos($id_prod){//Metodo para ver las valoraciones de los clientes
            try{
                $sql="SELECT v.*, u.NOMBRE_USUARIO, u.IMAGEN_USER
                    FROM valoracion v
                    JOIN usuarios u ON v.ID_USER=u.ID_USUARIO
                    WHERE v.ID_PRODUCTO=:id_p
                    ORDER BY v.ID_VALORACION DESC";
                $stmt=$this->db->prepare($sql);
                $stmt->execute([':id_p' => $id_prod]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al mostrar las valoraciones: ".$e->getMessage());
            }
        }

        public function mostrarPedidos($inicio,$cantidad,$id_user){//Metodo para mostrar todos los pedidos del usuario
            try{
                $sql="SELECT * FROM pedidos p
                    WHERE ID_USUARIO=:id_u
                    LIMIT :inicio, :cantidad";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":id_u",$id_user,PDO::PARAM_INT);
                $stmt->bindParam(":inicio",$inicio,PDO::PARAM_INT);
                $stmt->bindParam(":cantidad",$cantidad,PDO::PARAM_INT);

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al mostrar los Pedidos: ".$e->getMessage());
            }
        }

        public function ContarPedidos($id_user){//Cuenta el total de los pedidos
            try{
                $sql = "SELECT COUNT(*) as total FROM pedidos WHERE ID_USUARIO=:id_u";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(["id_u"=>$id_user]);

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Pedidos".$e->getMessage());
            }
        }

        public function mostrarValoraciones($inicio,$cantidad,$id_user){//Metodo para mostrar todas las valoraciones del usuario
            try{
                $sql="SELECT v.*, p.NOMBRE
                    FROM valoracion v 
                    JOIN productos p
                    ON v.ID_PRODUCTO=p.ID_PRODUCTO
                    WHERE v.ID_USER=:id_u
                    LIMIT :inicio, :cantidad";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":id_u",$id_user,PDO::PARAM_INT);
                $stmt->bindParam(":inicio",$inicio,PDO::PARAM_INT);
                $stmt->bindParam(":cantidad",$cantidad,PDO::PARAM_INT);

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al mostrar las valoraciones: ".$e->getMessage());
            }
        }

        public function ContarValoraciones($id_user){//Cuenta el total de los pedidos
            try{
                $sql = "SELECT COUNT(*) as total FROM valoracion WHERE ID_USER=:id_u";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(["id_u"=>$id_user]);

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Pedidos".$e->getMessage());
            }
        }
        
    }
?>