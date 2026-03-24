<?php
    require_once "modelo/conexion.php";

    class Productos{
        private $db;
        public function __construct(){//Contructor para conectar a la Base de Datos
            $this->db=Conexion::conexion();   
        }
        //Administracion de los Productos
        public function ListarProductos($inicio, $cantidad){
            try{ 
                //Se usa una consulta conjunta de 5 Tablas ya que los productos contienen en su tabla las claves foraneas del ID_EQUIPO Y CATEGORIA Y EL ID DE PRODCUTO ESTA EN OTRA TABLA JUNTO CON EL ID DE TALLAS
                $sql="SELECT p.ID_PRODUCTO,pt.ID_TALLA,p.NOMBRE, pt.STOCK_ESPECIFICO AS STOCK, t.TALLA,
                    c.PRENDA, e.NOMBRE_EQUIPO, p.FECHA_ALTA, p.PRECIO
                FROM productos p
                INNER JOIN productos_tallas pt ON p.ID_PRODUCTO = pt.ID_PRODUCTO
                INNER JOIN tallas t ON pt.ID_TALLA = t.ID_TALLA
                LEFT JOIN categorias c ON p.ID_CAT = c.ID_CAT
                LEFT JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO 
                LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                 //Se usara el inicio de la pagina en la que nos encontremos y la cantidad de datos por pagina
                $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Productos".$e->getMessage());
            }
        }
        public function ContarProductos(){//Contamos Los productos Totales
            try{
                $sql = "SELECT COUNT(*) as total 
                FROM productos p
                INNER JOIN productos_tallas pt ON p.ID_PRODUCTO = pt.ID_PRODUCTO
                INNER JOIN tallas t ON pt.ID_TALLA = t.ID_TALLA
                LEFT JOIN categorias c ON p.ID_CAT = c.ID_CAT
                LEFT JOIN entidad_deportiva e ON p.ID_EQUIPO = e.ID_EQUIPO";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Productos".$e->getMessage());
            }
        }

        public function añadirProductos($nombre, $id_equipo, $id_categoria, $descripcion, $precio, $fecha_alta, $añoEdicion, $caracteristicas, $imagen, $imagen2, $tallas = []) {//Metodo para añadir productos
            try {
                $this->db->beginTransaction();

                // 1. INSERTAR PRODUCTO
                $sqlProd = "INSERT INTO productos(ID_EQUIPO, ID_CAT, NOMBRE, DESCRIPCION, PRECIO, FECHA_ALTA,AÑO_EDICION, CARACTERISTICAS)
                            VALUES(:equipo, :cat, :nombre, :descripcion, :precio, :fecha, :anio ,:caracteristicas)";
                
                $stmtProd = $this->db->prepare($sqlProd);
                $stmtProd->bindParam(":equipo", $id_equipo, PDO::PARAM_INT);
                $stmtProd->bindParam(":cat", $id_categoria, PDO::PARAM_INT);
                $stmtProd->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmtProd->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
                $stmtProd->bindParam(":precio", $precio); // Los decimales se pasan sin tipo específico
                $stmtProd->bindParam(":fecha", $fecha_alta);
                $stmtProd->bindParam(":anio", $añoEdicion,PDO::PARAM_STR);
                $stmtProd->bindParam(":caracteristicas", $caracteristicas, PDO::PARAM_STR);
                $stmtProd->execute();

                $id_producto = $this->db->lastInsertId();

                // INSERTAR TALLAS
                if (!empty($tallas)) {
                    $sqlTallas = "INSERT INTO productos_tallas (ID_PRODUCTO, ID_TALLA, STOCK_ESPECIFICO) 
                                VALUES (:id_prod, :id_talla, :stock)";
                    $stmtTallas = $this->db->prepare($sqlTallas);

                    foreach ($tallas as $id_talla => $cantidad) {//Se recorre un foreach por si hay mas de una talla que se le vaya a meter al Stock
                        if ($cantidad > 0) {
                            // Importante: vinculamos las variables del bucle
                            $stmtTallas->bindParam(":id_prod", $id_producto, PDO::PARAM_INT);
                            $stmtTallas->bindParam(":id_talla", $id_talla, PDO::PARAM_INT);
                            $stmtTallas->bindParam(":stock", $cantidad, PDO::PARAM_INT);
                            $stmtTallas->execute();
                        }
                    }
                }

                //RELACIÓN CON COMPETICION
                $sqlComp = "SELECT ID_COMP FROM temporadas WHERE ID_EQUIPO = :id_equipo";
                $stmtComp = $this->db->prepare($sqlComp);
                $stmtComp->bindParam(":id_equipo", $id_equipo, PDO::PARAM_INT);
                $stmtComp->execute();
                $compResult = $stmtComp->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($compResult)) {//Si saca los Id de competiciones
                    $sqlRel = "INSERT INTO productos_competiciones (ID_PRODUCTO, ID_COMP) VALUES (:prod, :comp)";
                    $stmtRel = $this->db->prepare($sqlRel);
                    foreach($compResult as $fila){//Se hace un foreach para añadir los id de competiciones si hay mas de uno
                        $id_comp=$fila['ID_COMP'];
                        $stmtRel->bindParam(":prod",$id_producto,PDO::PARAM_INT);
                        $stmtRel->bindParam(":comp",$id_comp,PDO::PARAM_INT);
                        $stmtRel->execute();
                    }
                }

                //INSERTAR IMÁGENES
                $sqlImg = "INSERT INTO imagenes(RUTA, ID_PRODUCTO) VALUES (:ruta, :prod_img)";
                $stmtImg = $this->db->prepare($sqlImg);

                // Imagen 1
                $stmtImg->bindParam(":ruta", $imagen, PDO::PARAM_STR);
                $stmtImg->bindParam(":prod_img", $id_producto, PDO::PARAM_INT);
                $stmtImg->execute();

                // Imagen 2 (Si existe)
                if (!empty($imagen2) && $imagen2 !== "") {
                    $stmtImg->bindParam(":ruta", $imagen2, PDO::PARAM_STR);
                    $stmtImg->bindParam(":prod_img", $id_producto, PDO::PARAM_INT);
                    $stmtImg->execute();
                }

                $this->db->commit();
                return "Productos Insertados";

            } catch (PDOException $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                die("Error al insertar Productos: " . $e->getMessage());
            }
        }

        public function eliminarStock($id_producto, $id_talla){//Metodo para eliminar Productos Mediante ID
            try{
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                
                $sql="DELETE FROM productos_tallas 
                 WHERE ID_PRODUCTO=:id_producto
                 AND ID_TALLA=:id_talla";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":id_producto",$id_producto,PDO::PARAM_INT);//Parametro del ID del producto del stock que queremos eliminar
                $stmt->bindParam(":id_talla",$id_talla,PDO::PARAM_INT);//Parametro del ID de la talla del stock que queremos eliminar

                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return"Producto Eliminado";
            }catch(PDOException $e){
               if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al Eliminar Producto".$e->getMessage());
            }
        }


        public function obtenerCategorias() {//Obtener todas las categorías de prendas
            try {
                $sql = "SELECT ID_CAT, PRENDA FROM categorias"; //
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al obtener categorías: " . $e->getMessage());
            }
        }

        public function obtenerEquipos() {//Obtener todos los equipos y selecciones
            try {
                // La tabla se llama entidad_deportiva según tu SQL
                $sql = "SELECT ID_EQUIPO, NOMBRE_EQUIPO FROM entidad_deportiva ORDER BY NOMBRE_EQUIPO ASC"; 
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al obtener equipos: " . $e->getMessage());
            }
        }

        public function obtenerTallas() {//Obtener todas las tallas disponibles para la cuadrícula
            try {
                $sql = "SELECT ID_TALLA, TALLA FROM tallas"; //
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al obtener tallas: " . $e->getMessage());
            }
        }

        public function editarProductos($id){//Metodo para eliminar Producto Mediante ID
            try{
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql="";
                $stmt=$this->db->prepare($sql);

                //$stmt->bindParam(":id_producto",$id_producto);

                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return"Producto Actualizado";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al Actualizar Producto".$e->getMessage());
            }
        }
        //Administracion de las Categorias
        public function ListarCategorias($inicio, $cantidad){//Consulta para mostrar las categorias
            try{
                //Consulta conjunta entre categorias, deportes y su lista conjunta usamsos Group Concat y un seperador(,) para indicar que deportes contiene cada categoria
                $sql = "SELECT c.ID_CAT, c.PRENDA, c.DESCRIPCION, 
                       GROUP_CONCAT(d.DEPORTE SEPARATOR ', ') AS DEPORTE 
                FROM categorias c
                LEFT JOIN categorias_deportes cd ON c.ID_CAT = cd.ID_CAT
                LEFT JOIN deportes d ON cd.ID_DEPORTE = d.ID_DEPORTE
                GROUP BY c.ID_CAT
                LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                //Se usara el inicio de la pagina en la que nos encontremos y la cantidad de datos por pagina
                $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', (int)$cantidad, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Categorias".$e->getMessage());
            }
        }

        public function ContarCategorias(){//Consulta para contar las categorias
            try{
                $sql = "SELECT COUNT(*) as total FROM categorias";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Categorias".$e->getMessage());
            }
        }

        public function añadirCategoria($prenda,$descripcion,$id_deporte) {//Metodo para añadir categorias
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde

                $sql="SELECT ID_CAT FROM categorias WHERE PRENDA = :prenda";//Comprobamos que el nombre de las prendas no se repita
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":prenda", $prenda);

                $stmt->execute();

                $existe=$stmt->fetch(PDO::FETCH_ASSOC);

                if($existe){
                    // Si ya existe, guardamos su ID para usarlo luego
                    $id_cat = $existe['ID_CAT'];
                }else{
                    $sql = "INSERT INTO categorias (PRENDA,DESCRIPCION) VALUES (:prenda,:descripcion)";
                    $stmt = $this->db->prepare($sql);

                    $stmt->bindParam(":prenda", $prenda,PDO::PARAM_STR);
                    $stmt->bindParam(":descripcion", $descripcion,PDO::PARAM_STR);

                    $stmt->execute();

                    $id_cat = $this->db->lastInsertId();//Sacamos el ID de la categoria que insertamos antes para insertar imagenes
                }

                

                if(!empty($id_cat)){//Insertamos la relacion entre las catgorias y los deportes
                    $sql="INSERT IGNORE INTO categorias_deportes (ID_CAT, ID_DEPORTE) VALUES (:id_cat, :id_deporte)";//Añadimos IGNORE para evitar el error de clave duplicada
                    $stmt = $this->db->prepare($sql);

                    $stmt->bindParam(":id_cat",$id_cat,PDO::PARAM_INT);
                    $stmt->bindParam(":id_deporte",$id_deporte,PDO::PARAM_INT);
                    $stmt->execute();
                }

                $this->db->commit();//Guarda los cambios en la BSD
                return "Categoría añadida con éxito";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir categoría: " . $e->getMessage());
            }
        }

        public function eliminarCategoria($id) {
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "DELETE FROM categorias WHERE ID_CAT = :id_cat";
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(":id_cat", $id, PDO::PARAM_INT);

                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return "Categoría eliminada";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al eliminar categoría: " . $e->getMessage());
            }
        }
        //Administracion de los Deportes
        public function añadirDeportes($nombre_deporte){//Metodo para añadir Deportes
            try{
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql="INSERT INTO deportes (DEPORTE) VALUES (:deporte)";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":deporte",$nombre_deporte,PDO::PARAM_STR);

                $stmt->execute();

                $this->db->commit();//Guarda los cambios en la BSD
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir deporte: " . $e->getMessage());
            }
        }

        public function ListarDeportes(){//Meetodo para Listar deportes
            try{
                $sql="SELECT * FROM deportes";

                $stmt=$this->db->prepare($sql);

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al añadir deportes: " . $e->getMessage());
            }
        }

        //Administracion de los Equipos
        public function ListarEquipos($inicio, $cantidad){
            try{
                $tipo='Equipo';
                $sql = "SELECT * FROM entidad_deportiva WHERE TIPO = :tipo LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->bindValue(':tipo',$tipo,PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Eqiopos".$e->getMessage());
            }
        }
        
        public function ContarEquipos(){
            try{
                $tipo='Equipo';
                $sql = "SELECT COUNT(*) as total FROM entidad_deportiva WHERE TIPO = :tipo";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':tipo',$tipo);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Equipos".$e->getMessage());
            }
        }

        public function añadirEquipo($nombre, $imagen,$tipo = 'Equipo') {//Metodo para añadir Equipo
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "INSERT INTO entidad_deportiva (NOMBRE_EQUIPO, ESCUDO, TIPO) VALUES (:nombre, :imagen, :tipo)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombre", $nombre,PDO::PARAM_STR);
                $stmt->bindParam(":imagen", $imagen,PDO::PARAM_STR);
                $stmt->bindParam(":tipo", $tipo,PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return "Equipo añadido con éxito";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir equipo: " . $e->getMessage());
            }
        }
        //Administracion de las Selecciones
        public function ListarSelecciones($inicio, $cantidad){
            try{
                $tipo='Seleccion';
                $sql = "SELECT * FROM entidad_deportiva WHERE TIPO = :tipo LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', (int)$cantidad, PDO::PARAM_INT);
                $stmt->bindValue(':tipo',$tipo);
                $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Selecciones".$e->getMessage());
            }
        }

        public function ContarSelecciones(){
            try{
                $tipo='Seleccion';
                $sql = "SELECT COUNT(*) as total FROM entidad_deportiva WHERE TIPO = :tipo";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':tipo',$tipo);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Selecciones".$e->getMessage());
            }
        }

        public function añadirSeleccion($nombre, $imagen,$tipo = 'Seleccion') {//Metodo para añadir seleccion
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "INSERT INTO entidad_deportiva (NOMBRE_EQUIPO, ESCUDO, TIPO) VALUES (:nombre, :imagen, :tipo)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombre", $nombre,PDO::PARAM_STR);
                $stmt->bindParam(":imagen", $imagen,PDO::PARAM_STR);
                $stmt->bindParam(":tipo", $tipo,PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return "Selección añadida con éxito";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir selección: " . $e->getMessage());
            }
        }

        //Metodo para eliminar una Entidad Deportiva
        public function eliminarEntidadDeportiva($id) {
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "DELETE FROM entidad_deportiva WHERE ID_EQUIPO = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->db->commit();//Guarda los cambios en la BSD
                return "Entidad eliminada con éxito";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al eliminar la entidad: " . $e->getMessage());
            }
        }
        //Administracion de los Logos y Competiciones



        public function ListarTemporadas($inicio, $cantidad){//Consulta Conjunta de las tablas de competiciones, logos y competiciones_parches
            try{
                $sql = "SELECT c.NOMBRE_COMP,e.NOMBRE_EQUIPO, p.PARCHE, t.PARCHE_ESPECIAL, c.TIPO_COMP, t.AÑO_EDICION 
                    FROM competiciones c 
                    INNER JOIN temporadas t ON c.ID_COMP = t.ID_COMP 
                    INNER JOIN parches p ON t.ID_LOGO = p.ID_LOGO
                    INNER JOIN entidad_deportiva e ON t.ID_EQUIPO = e.ID_EQUIPO
                    LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Temporadas".$e->getMessage());
            }
        }

        public function ContarTemporadas(){//Contar las competiciones y logos 
            // Contamos cuántas relaciones existen entre competiciones y parches
            try{
                $sql = "SELECT COUNT(*) as total 
                        FROM competiciones c 
                        INNER JOIN temporadas t ON c.ID_COMP = t.ID_COMP 
                        INNER JOIN parches p ON t.ID_LOGO = p.ID_LOGO";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Temporadas".$e->getMessage());
            }
        }
        public function ListarCompeticiones() {//Listar Competiciones
            try {
                $sql = "SELECT * FROM competiciones";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        public function añadirCompeticion($nombre, $tipo) {//Método para Añadir competicion
            try {
                $this->db->beginTransaction();
                $sql = "INSERT INTO competiciones (NOMBRE_COMP, TIPO_COMP) VALUES (:nombre, :tipo)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombre", $nombre,PDO::PARAM_STR);
                $stmt->bindParam(":tipo", $tipo,PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();
                return "Competición añadida";
            } catch(PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al añadir competición: " . $e->getMessage());
            }
        }
        
        public function ListarParches() {// Método para listar todos los parches disponibles en la base de datos
            try {
                $sql = "SELECT * FROM parches";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Error al listar parches: " . $e->getMessage());
            }
        }

        public function añadirParche($nombre_parche) {// Método para añadir un nuevo parche (Logo)
            try {
                $this->db->beginTransaction();
                $sql = "INSERT INTO parches (PARCHE) VALUES (:parche)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":parche", $nombre_parche,PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();
                return "Parche añadido correctamente";
            } catch(PDOException $e) {
                $this->db->rollBack();
                die("Error al añadir el parche: " . $e->getMessage());
            }
        }

        public function ListarED(){//Metodo que muestra el resto de las entidades deportivas
            try {
                $sql = "SELECT * FROM entidad_deportiva";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        public function asignarEquipos($id_comp, $id_equipo, $id_logo, $anio, $parche_especial = null) {
            try {
                $this->db->beginTransaction();
                
                // Usamos ON DUPLICATE KEY para que si el ID_COMP e ID_EQUIPO ya existen, se actualice el año y el parche
                $sql = "INSERT INTO temporadas (ID_COMP, ID_EQUIPO, ID_LOGO, AÑO_EDICION, PARCHE_ESPECIAL) 
                        VALUES (:id_comp, :id_equipo, :id_logo, :anio, :parche_especial)
                        ON DUPLICATE KEY UPDATE 
                            ID_LOGO = VALUES(ID_LOGO),
                            AÑO_EDICION = VALUES(AÑO_EDICION),
                            PARCHE_ESPECIAL = VALUES(PARCHE_ESPECIAL)";

                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(':id_comp', $id_comp, PDO::PARAM_INT);
                $stmt->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT); 
                $stmt->bindParam(':id_logo', $id_logo, PDO::PARAM_INT);
                $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
                $stmt->bindParam(':parche_especial', $parche_especial, PDO::PARAM_STR);
                
                $stmt->execute();
                $this->db->commit();
                return true;
            } catch(PDOException $e) {
                if ($this->db->inTransaction()) $this->db->rollBack();
                die("Error en AsignarEquipos: " . $e->getMessage());
                return false;
            }
        }

        //Administracion de las Tallas
        public function ListarTallas($inicio, $cantidad){//Muestra las tallas que hay
            try{
                $sql = "SELECT * FROM tallas LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Tallas".$e->getMessage());
            }
        }

        public function ContarTallas(){//Cuenta el total de las tallas
            try{
                $sql = "SELECT COUNT(*) as total FROM tallas";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Tallas".$e->getMessage());
            }
        }

        public function añadirTalla($talla) {//Añade una talla
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "INSERT INTO tallas (TALLA) VALUES (:talla)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":talla", $talla,PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();
                return "Talla añadida con éxito";
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir talla: " . $e->getMessage());
            }
        }

        public function eliminarTalla($id) {//Borra la talla
            try {
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                $sql = "DELETE FROM tallas WHERE ID_TALLA = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                return "Talla eliminada";
            } catch (PDOException $e) {
                die("Error al eliminar talla: " . $e->getMessage());
            }
        }
        //Administracion de los Pedidos
        public function ListarPedidos($inicio, $cantidad){//Muestra todos los pedidos de los usuarios
            try{
                $sql = "SELECT p.*,u.NOMBRE_USUARIO
                FROM pedidos p INNER JOIN usuarios u ON p.ID_USUARIO=u.ID_USUARIO
                LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al Listar Pedidos".$e->getMessage());
            }
        }

        public function ContarPedidos(){//Cuenta el total de los pedidos
            try{
                $sql = "SELECT COUNT(*) as total FROM pedidos";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $res=$stmt->fetch(PDO::FETCH_ASSOC);
                return $res['total'];
            }catch(PDOException $e){
                die("Error al Contar Pedidos".$e->getMessage());
            }
        }
    }
?>