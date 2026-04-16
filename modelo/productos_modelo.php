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
                    c.PRENDA,d.DEPORTE, e.NOMBRE_EQUIPO, p.FECHA_ALTA, p.PRECIO
                FROM productos p
                INNER JOIN productos_tallas pt ON p.ID_PRODUCTO = pt.ID_PRODUCTO
                INNER JOIN tallas t ON pt.ID_TALLA = t.ID_TALLA
                LEFT JOIN categorias c ON p.ID_CAT = c.ID_CAT
                LEFT JOIN deportes d ON p.ID_DEPORTE = d.ID_DEPORTE
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

        public function añadirProductos($nombre, $id_equipo, $id_categoria,$id_deporte, $descripcion, $precio, $fecha_alta, $añoEdicion, $caracteristicas, $imagen, $imagen2, $tallas = []) {//Metodo para añadir productos
            try {
                $this->db->beginTransaction();

                // 1. INSERTAR PRODUCTO
                $sqlProd = "INSERT INTO productos(ID_EQUIPO, ID_CAT,ID_DEPORTE, NOMBRE, DESCRIPCION, PRECIO, FECHA_ALTA,AÑO_EDICION, CARACTERISTICAS)
                            VALUES(:equipo, :cat,:deporte, :nombre, :descripcion, :precio, :fecha, :anio ,:caracteristicas)";
                
                $stmtProd = $this->db->prepare($sqlProd);
                $stmtProd->bindParam(":equipo", $id_equipo, PDO::PARAM_INT);
                $stmtProd->bindParam(":cat", $id_categoria, PDO::PARAM_INT);
                $stmtProd->bindParam(":deporte", $id_deporte, PDO::PARAM_INT);
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
                //Borramos las tallas asociadas a un producto
                $sql="DELETE FROM productos_tallas 
                 WHERE ID_PRODUCTO=:id_producto
                 AND ID_TALLA=:id_talla";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":id_producto",$id_producto,PDO::PARAM_INT);//Parametro del ID del producto del stock que queremos eliminar
                $stmt->bindParam(":id_talla",$id_talla,PDO::PARAM_INT);//Parametro del ID de la talla del stock que queremos eliminar

                $stmt->execute();

                //Verificamos que ya no hay mas tallas disponibles de ese producto
                $sql="SELECT COUNT(*) FROM productos_tallas WHERE ID_PRODUCTO = :id_producto";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id_producto",$id_producto,PDO::PARAM_INT);
                $stmt->execute();

                $tallasRestantes=$stmt->fetchColumn();

                if($tallasRestantes==0){//Si ya no hay tallas en los asociadas a un producto
                    $sql="DELETE FROM productos WHERE ID_PRODUCTO=:id_producto";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":id_producto",$id_producto,PDO::PARAM_INT);
                    $stmt->execute();
                }

                $this->db->commit();//Guarda los cambios en la BSD
                return"Stock Eliminado";
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

         public function obtenerDeportes() {//Obtener todos los deportes
            try {
                $sql = "SELECT ID_DEPORTE, DEPORTE FROM deportes"; //
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al obtener categorías: " . $e->getMessage());
            }
        }

        public function obtenerDeportesPorCategoria($id_cat) {//Obtener todos los deportes asociados a una categoria
            try {
                $sql = "SELECT 
                        d.ID_DEPORTE, d.DEPORTE 
                        FROM deportes d
                        INNER JOIN categorias_deportes cd ON d.ID_DEPORTE=cd.ID_DEPORTE
                        WHERE cd.ID_CAT = :id_cat"; //
                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(':id_cat', $id_cat, PDO::PARAM_INT);

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

        public function editarProductos($id, $nombre, $precio, $id_cat,$id_deporte, $id_equipo,$año_edicion,$descripcion,$caracteristicas,$imagen1,$imagen2, $tallas = []){//Metodo para editar Producto Mediante ID
            try{
                $this->db->beginTransaction();//Empezamos una transaccion para asegurar que todo se guarde
                
                if(!empty($nombre)){//Comprobamos que el nombre no este vacio
                    $sql="UPDATE productos SET NOMBRE=:nombre WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':nombre'=>$nombre,':id'=>$id]);
                }

                if(!empty($precio)){//Comprobamos que el precio no este vacio
                    $sql="UPDATE productos SET PRECIO=:precio WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':precio'=>$precio,':id'=>$id]);
                }

                if(!empty($id_cat)){//Comprobamos que el id_categoria no este vacio
                    $sql="UPDATE productos SET ID_CAT=:id_cat WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':id_cat'=>$id_cat,':id'=>$id]);
                }

                if(!empty($id_deporte)){//Comprobamos que el id_categoria no este vacio
                    $sql="UPDATE productos SET ID_DEPORTE=:id_deporte WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':id_deporte'=>$id_deporte,':id'=>$id]);
                }

                if(!empty($id_equipo)){//Comprobamos que el id_equipo no este vacio
                    $sql="UPDATE productos SET ID_EQUIPO=:id_equipo WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':id_equipo'=>$id_equipo,':id'=>$id]);
                }

                if(!empty($año_edicion)){//Comprobamos que el id_equipo no este vacio
                    $sql="UPDATE productos SET AÑO_EDICION=:anio_edicion WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':anio_edicion'=>$año_edicion,':id'=>$id]);
                }

                if(!empty($descripcion)){//Comprobamos que el id_equipo no este vacio
                    $sql="UPDATE productos SET DESCRIPCION=:descripcion WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':descripcion'=>$descripcion,':id'=>$id]);
                }

                if(!empty($caracteristicas)){//Comprobamos que el id_equipo no este vacio
                    $sql="UPDATE productos SET CARACTERISTICAS=:caracteristicas WHERE ID_PRODUCTO=:id";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':caracteristicas'=>$caracteristicas,':id'=>$id]);
                }

                // Actualizar TALLAS
                if (!empty($tallas)) {
                    foreach ($tallas as $id_talla => $cantidad) {
                        $cantidad = (int)$cantidad; // Aseguramos que sea un número entero

                        // FILTRO CRÍTICO: Solo procedemos si la cantidad es mayor a 0
                        if ($cantidad > 0) {
                            
                            // 1. Verificamos si ya existe esta talla para este producto
                            $sql = "SELECT COUNT(*) FROM productos_tallas WHERE ID_PRODUCTO = :id_prod AND ID_TALLA = :id_talla";
                            $stmt = $this->db->prepare($sql);
                            $stmt->execute([':id_prod' => $id, ':id_talla' => $id_talla]);
                            $existe = $stmt->fetchColumn();

                            if ($existe > 0) {
                                // Si existe y la cantidad es > 0, actualizamos el stock
                                $sql = "UPDATE productos_tallas SET STOCK_ESPECIFICO = :stock
                                        WHERE ID_PRODUCTO = :id_prod AND ID_TALLA = :id_talla";
                                $this->db->prepare($sql)->execute([
                                    ':stock' => $cantidad,
                                    ':id_prod' => $id,
                                    ':id_talla' => $id_talla
                                ]);
                            } else {
                                // Si no existe y la cantidad es > 0, insertamos el nuevo registro
                                $sql = "INSERT INTO productos_tallas (ID_PRODUCTO, ID_TALLA, STOCK_ESPECIFICO) 
                                        VALUES (:id_prod, :id_talla, :stock)";
                                $this->db->prepare($sql)->execute([
                                    ':id_prod' => $id,
                                    ':id_talla' => $id_talla,
                                    ':stock' => $cantidad
                                ]);
                            }
                        }
                        // Si $cantidad es 0 o menor, el bucle salta a la siguiente talla sin hacer nada
                    }
                }

                if(!empty($imagen1) || !empty($imagen2)){//Si las imagenes que se pasaron no estan vacias
                    $sql="DELETE FROM imagenes WHERE ID_PRODUCTO=:id";//Se borraran las imagenes de la tabla imagenes
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([':id'=>$id]);

                    if(!empty($imagen1)){//Y se insertaran imagenes segun si solo se inserta una
                        $sql = "INSERT INTO imagenes (RUTA, ID_PRODUCTO) VALUES (:ruta, :id_prod)";
                        $this->db->prepare($sql)->execute([':ruta' => $imagen1, ':id_prod' => $id]);
                    }

                    if(!empty($imagen2)){//O se insertan varias
                        $sql = "INSERT INTO imagenes (RUTA, ID_PRODUCTO) VALUES (:ruta, :id_prod)";
                        $this->db->prepare($sql)->execute([':ruta' => $imagen2, ':id_prod' => $id]);
                    }
                
                }

                $this->db->commit();//Guarda los cambios en la BSD
                return"Producto Actualizado";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al Actualizar Producto".$e->getMessage());
            }
        }

        public function obtenerProductoPorId($id_producto) {
            try {
                // Seleccionamos los datos del producto
                // Incluimos ID_DEPORTE para que el select de la vista sepa qué opción marcar
                $sql = "SELECT * FROM productos WHERE ID_PRODUCTO = :id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $id_producto, PDO::PARAM_INT);
                $stmt->execute();
                
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    // También recuperamos las imágenes asociadas a este producto
                    // para poder mostrarlas en la vista de edición
                    $sqlImg = "SELECT RUTA FROM imagenes WHERE ID_PRODUCTO = :id ORDER BY ID_IMAGEN ASC";
                    $stmtImg = $this->db->prepare($sqlImg);
                    $stmtImg->bindValue(':id', $id_producto, PDO::PARAM_INT);
                    $stmtImg->execute();
                    
                    $imagenes = $stmtImg->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Añadimos las rutas de las imágenes al array del producto
                    // Usamos índices 0 y 1 para que coincidan con imagen1 e imagen2 del controlador
                    $producto['imagen1'] = isset($imagenes[0]) ? $imagenes[0]['RUTA'] : "";
                    $producto['imagen2'] = isset($imagenes[1]) ? $imagenes[1]['RUTA'] : "";
                }

                return $producto;
            } catch (PDOException $e) {
                die("Error al obtener el producto: " . $e->getMessage());
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

        public function añadirCategoria($prenda,$descripcion) {//Metodo para añadir categorias
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

                $this->db->commit();//Guarda los cambios en la BSD
                return $id_cat;
            } catch (PDOException $e) {
                if($this->db->inTransaction()){
                    $this->db->rollBack();//Se desacen los cambios
                }
                die("Error al añadir categoría: " . $e->getMessage());
            }
        }

        public function asignarDeporteCat($id_cat,$id_dep){//Metodo que asigna un deporte a una categoria
            try {
                $sql = "INSERT INTO categorias_deportes (ID_CAT, ID_DEPORTE) VALUES (:id_cat, :id_dep)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_cat', $id_cat,PDO::PARAM_INT);
                $stmt->bindParam(':id_dep', $id_dep,PDO::PARAM_INT);
                $stmt->execute();
                return "Deportes Asignados";
            } catch (PDOException $e) {
                die("Error al asignar una categoria a un deporte");
            }
        }

        public function limpiarDeportesCat($id_cat){//Metodo para limpiar relaciones antiguas entre una categoria y un deporte
            try {
                $sql = "DELETE FROM categorias_deportes WHERE ID_CAT = :id_cat";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id_cat", $id_cat, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                die("Error al limpiar deportes: " . $e->getMessage());
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

        public function editarCategoria($id_cat,$prenda,$descripcion){//Metodo para editar categoria
            try{
                $this->db->beginTransaction();
                
                if(!empty($prenda)){
                    $sql="UPDATE categorias SET PRENDA=:prenda WHERE ID_CAT=:id_cat";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":prenda"=>$prenda,":id_cat"=>$id_cat]);
                }

                if(!empty($descripcion)){
                    $sql="UPDATE categorias SET DESCRIPCION=:descripcion WHERE ID_CAT=:id_cat";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":descripcion"=>$descripcion,":id_cat"=>$id_cat]);
                }

                $this->db->commit();
                return "Categoria actualizada";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al editar categoria: ".$e->getMessage());
            }
        }

        public function categorias_deportes($id_cat){//Metodo para comprobar que una categoria esta asignada a un deporte
            try{
                $sql="SELECT ID_DEPORTE from categorias_deportes WHERE ID_CAT=:id_cat";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id_cat",$id_cat,PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                die("Error al ver las categorias asignadas a un deporte: ".$e->getMessage());
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

        public function editarED($id_equipo,$nombre_equipo,$escudo,$tipo){//Metodo para editar la entidad del equipo
            try{
                $this->db->beginTransaction();
                if(!empty($nombre_equipo)){
                    $sql="UPDATE entidad_deportiva SET NOMBRE_EQUIPO=:nombre WHERE ID_EQUIPO=:id_equipo";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":nombre"=>$nombre_equipo,":id_equipo"=>$id_equipo]);
                }

                if(!empty($escudo)){
                    $sql="UPDATE entidad_deportiva SET ESCUDO=:escudo WHERE ID_EQUIPO=:id_equipo";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":escudo"=>$escudo,":id_equipo"=>$id_equipo]);
                }

                if(!empty($tipo)){
                    $sql="UPDATE entidad_deportiva SET TIPO=:tipo WHERE ID_EQUIPO=:id_equipo";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":tipo"=>$tipo,":id_equipo"=>$id_equipo]);
                }

                $this->db->commit();
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al eliminar entidad deportiva".$e->getMessage());
            }
        }

        //Administracion de los Logos y Competiciones
        public function ListarTemporadas($inicio, $cantidad){//Consulta Conjunta de las tablas de competiciones, logos y competiciones_parches
            try{
                $sql = "SELECT c.ID_COMP,c.NOMBRE_COMP,e.ID_EQUIPO,e.NOMBRE_EQUIPO,p.ID_LOGO,p.PARCHE, t.PARCHE_ESPECIAL, c.TIPO_COMP, t.AÑO_EDICION 
                    FROM competiciones c 
                    INNER JOIN temporadas t ON c.ID_COMP = t.ID_COMP 
                    INNER JOIN parches p ON t.ID_LOGO = p.ID_LOGO
                    INNER JOIN entidad_deportiva e ON t.ID_EQUIPO = e.ID_EQUIPO
                    ORDER BY c.ID_COMP ASC
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
                $sql = "SELECT * FROM competiciones ORDER BY NOMBRE_COMP ASC";
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
                $sql = "SELECT * FROM entidad_deportiva ORDER BY NOMBRE_EQUIPO ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Error al listar competiciones: " . $e->getMessage());
            }
        }

        public function asignarEquipos($id_comp, $id_equipo, $id_logo, $anio, $parche_especial = null) {//Metodo para asignar Equipos a una temporada
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

                //Si hay algun producto asociado a un equipo al asignarle una temporada se añadira tambien la asociacion con la competicion de la temporada

                $sql="SELECT ID_PRODUCTO FROM productos WHERE ID_EQUIPO = :id_equipo";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(':id_equipo',$id_equipo,PDO::PARAM_INT);

                $stmt->execute();
                $productos=$stmt->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($productos)){
                    $sql="INSERT IGNORE INTO productos_competiciones (ID_PRODUCTO, ID_COMP)
                        VALUES(:id_prod,:id_comp)";//Se usa IGNORE si la asociacion con el producto y la competicion ya existe no la inserte dos veces
                    
                    $stmt=$this->db->prepare($sql);

                    foreach($productos as $p){
                        $stmt->execute([
                            ':id_prod' => $p['ID_PRODUCTO'],
                            ':id_comp' => $id_comp
                        ]);
                    }
                }

                $this->db->commit();
                return true;
            } catch(PDOException $e) {
                if ($this->db->inTransaction()) $this->db->rollBack();
                die("Error en AsignarEquipos: " . $e->getMessage());
                return false;
            }
        }

        public function editarTemporada($id_comp, $id_equipo, $old_logo, $id_logo, $anio_edicion, $parche_especial = null){//Metodo para editar el año de la edicion de la temporada
            try{
                $this->db->beginTransaction();
                
                $sql="UPDATE temporadas 
                    SET ID_LOGO=:id_logo, AÑO_EDICION=:anio_edicion, PARCHE_ESPECIAL=:parche_especial
                    WHERE ID_COMP= :id_comp AND ID_EQUIPO=:id_equipo AND ID_LOGO=:old_logo";

                $stmt=$this->db->prepare($sql);

                //Vincalacion de parametros
                $stmt->bindParam(":id_logo",$id_logo,PDO::PARAM_INT);
                $stmt->bindParam(":anio_edicion",$anio_edicion,PDO::PARAM_INT);
                $stmt->bindParam(':parche_especial', $parche_especial, PDO::PARAM_STR);
                $stmt->bindParam(":id_comp",$id_comp,PDO::PARAM_INT);
                $stmt->bindParam(":id_equipo",$id_equipo,PDO::PARAM_INT);
                $stmt->bindParam(":old_logo",$old_logo,PDO::PARAM_INT);
                
                $stmt->execute();

                $this->db->commit();
                return "Temporada Actualizada";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }

                die("Error al eliminar temporada: ".$e->getMessage());
            }
        }

        public function eliminarTemporada($id_comp,$id_logo,$id_equipo){//Metodo para eliminar una temporada
            try{
                $this->db->beginTransaction();
                $sql="DELETE FROM temporadas WHERE ID_COMP=:id_comp AND ID_LOGO=:id_logo AND ID_EQUIPO=:id_equipo";
                $stmt=$this->db->prepare($sql);

                $stmt->bindParam(":id_comp",$id_comp,PDO::PARAM_INT);
                $stmt->bindParam(":id_logo",$id_logo,PDO::PARAM_INT);
                $stmt->bindParam(":id_equipo",$id_equipo,PDO::PARAM_INT);

                $stmt->execute();

                $this->db->commit();
                return "Temporada eliminada";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al eliminar temporada: ".$e->getMessage());
            }
        }

        public function eliminarCompeticiones($id_comp){//Metodo para eliminar competiciones
            try{
                $this->db->beginTransaction();

                $sql="DELETE FROM competiciones WHERE ID_COMP=:id_comp";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id_comp",$id_comp,PDO::PARAM_INT);
                $stmt->execute();

                $this->db->commit();
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al eliminar competicion: ".$e->getMessage());
            }
        }

        public function eliminarLogos($id_logo){//Metodo para eliminar logos
            try{
                $this->db->beginTransaction();

                $sql="DELETE FROM parches WHERE ID_LOGO=:id_logo";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id_logo",$id_logo,PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() === 0) {
                    // Si llega aquí, es que el ID no existe en la tabla
                    // o no coincide con lo que mandaste.
                    $this->db->rollBack();
                    die("Error: No se encontró ningún parche con el ID: " . $id_logo);
                }

                $this->db->commit();
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al eliminar parche: ".$e->getMessage());
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

        public function editarTalla($id_talla,$talla){//Metodo para editar el nombre de una talla
            try{
                $this->db->beginTransaction();
                if(!empty($talla)){
                    $sql="UPDATE tallas SET TALLA=:talla WHERE ID_TALLA=:id_talla";
                    $stmt=$this->db->prepare($sql);
                    $stmt->execute([":id_talla"=>$id_talla,":talla"=>$talla]);
                }
                $this->db->commit();
                return "Talla Actualizada";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al actualizar la talla: ".$e->getMessage());
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