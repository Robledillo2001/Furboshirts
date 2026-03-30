<?php
    require_once "modelo/conexion.php";//Se requiere el archivo de conexion

    class Usuarios{//Clase de Usuarios del Modelo
        private $db;
        public function __construct(){//Contructor para conectar a la Base de Datos
            $this->db=Conexion::conexion();   
        }

        public function editarPerfil($id_usuario,$nombre,$apellidos,$nombreUser,$correo,$passwd){//Metodo para cambiar datos del Usuario
            try{
                $this->db->beginTransaction();

                if(!empty($nombre)){
                    $sql="UPDATE usuarios SET NOMBRE=:nombre WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                }

                if(!empty($apellidos)){
                    $sql="UPDATE usuarios SET APELLIDOs=:apellidos WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                }

                if(!empty($nombreUser)){
                    $sql="UPDATE usuarios SET NOMBRE_USUARIO=:nombreUser WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":nombreUser",$nombreUser,PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario",$id_usuario,PDO::PARAM_INT);
                    $stmt->execute();
                }

                if(!empty($correo)){
                    $sql="UPDATE usuarios SET CORREO=:correo WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":correo",$correo,PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario",$id_usuario,PDO::PARAM_INT);
                    $stmt->execute();
                }

                if(!empty($passwd)){
                    $sql="UPDATE usuarios SET PASSWD=:passwd WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":passwd",$passwd,PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario",$id_usuario,PDO::PARAM_INT);
                    $stmt->execute();
                }
                $this->db->commit();
                return "Datos cambiados exitosamente";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al editar Perfil: ".$e->getMessage());
            }
        }

        public function cambiarIMGPerfil($id_usuario,$imagen){//Metodo para cambiar la imagen del Usuario
            try{
                $this->db->beginTransaction();
                $sql="UPDATE usuarios SET IMAGEN_USER=:imagen WHERE ID_USUARIO=:id_usuario";
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":imagen",$imagen,PDO::PARAM_STR);
                $stmt->bindParam(":id_usuario",$id_usuario,PDO::PARAM_INT);
                $stmt->execute();
                $this->db->commit();
                return "Imagen de perfil cambiada exitosamente";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al cambiar imagen de Perfil: ".$e->getMessage());
            }
        }

        public function login($input, $contraseña) {
            try {
                $sql = "SELECT * FROM usuarios WHERE NOMBRE_USUARIO = :usuario OR CORREO = :usuario";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":usuario", $input, PDO::PARAM_STR);
                $stmt->execute();

                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    if (password_verify($contraseña, $usuario['PASSWD'])) {
                        return $usuario;
                    }
                }
                return false;
            } catch (PDOException $e) {
                die("Error en login: " . $e->getMessage());
            }
        }

        public function registrar($nombre, $apellidos, $correo, $passwd, $nombreUser) {
            try {
                // Comprobamos si ya existe
                $sql = "SELECT ID_USUARIO FROM usuarios WHERE NOMBRE_USUARIO = :nombreUser OR CORREO = :correo";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombreUser", $nombreUser);
                $stmt->bindParam(":correo", $correo);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return "El usuario ya está registrado";
                }

                $imagen = 'assets/img/user.png';
                $rol = "cliente";

                $this->db->beginTransaction();

                $sql = "INSERT INTO usuarios (NOMBRE, APELLIDOS, CORREO, PASSWD, ROL, IMAGEN_USER, NOMBRE_USUARIO)
                        VALUES (:nombre, :apellidos, :correo, :passwd, :rol, :imagen, :nombreUser)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombre", $nombre);
                $stmt->bindParam(":apellidos", $apellidos);
                $stmt->bindParam(":correo", $correo);
                $stmt->bindParam(":passwd", $passwd);
                $stmt->bindParam(":rol", $rol);
                $stmt->bindParam(":imagen", $imagen);
                $stmt->bindParam(":nombreUser", $nombreUser);

                $stmt->execute();
                $this->db->commit();
                return "Registro Exitoso";
            } catch (PDOException $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                die("Error en registro: " . $e->getMessage());
            }
        }

        public function mostrarAdministradores($inicio, $cantidad) {
            try {
                $rol = 'admin';
                $sql = "SELECT * FROM usuarios WHERE ROL=:rol LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                
                $stmt->bindParam(":rol", $rol);
                $stmt->bindValue(":inicio", (int)$inicio, PDO::PARAM_INT);
                $stmt->bindValue(":cantidad", (int)$cantidad, PDO::PARAM_INT);

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al mostrar admins: " . $e->getMessage());
            }
        }

        public function contarAdmins() {
            try {
                $rol = 'admin';
                $sql = "SELECT COUNT(*) as total FROM usuarios WHERE ROL=:rol";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":rol", $rol);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultado ? $resultado['total'] : 0;
            } catch (PDOException $e) {
                die("Error al contar admins: " . $e->getMessage());
            }
        }

        public function añadirAdmins($nombre, $apellidos, $correo, $passwd, $nombreUser){//Es igual que el formulario de registro pero para añadri admins
            try {
                // Comprobamos si ya existe
                $sql = "SELECT ID_USUARIO FROM usuarios WHERE NOMBRE_USUARIO = :nombreUser OR CORREO = :correo";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombreUser", $nombreUser);
                $stmt->bindParam(":correo", $correo);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return "El usuario ya está registrado";
                }

                $imagen = 'assets/img/user.png';
                $rol = "admin";
                $this->db->beginTransaction();

                $sql = "INSERT INTO usuarios (NOMBRE, APELLIDOS, CORREO, PASSWD, ROL, IMAGEN_USER, NOMBRE_USUARIO)
                        VALUES (:nombre, :apellidos, :correo, :passwd, :rol, :imagen, :nombreUser)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":nombre", $nombre);
                $stmt->bindParam(":apellidos", $apellidos);
                $stmt->bindParam(":correo", $correo);
                $stmt->bindParam(":passwd", $passwd);
                $stmt->bindParam(":rol", $rol);
                $stmt->bindParam(":imagen", $imagen);
                $stmt->bindParam(":nombreUser", $nombreUser);

                $stmt->execute();
                $this->db->commit();
                return "Registro Exitoso";
            } catch (PDOException $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                die("Error en Añadir Admin: " . $e->getMessage());
            }
        } 

        public function mostrarClientes($inicio, $cantidad) {
            try {
                $rol = 'cliente';
                $sql = "SELECT * FROM usuarios WHERE ROL=:rol LIMIT :inicio, :cantidad";
                $stmt = $this->db->prepare($sql);
                
                $stmt->bindParam(":rol", $rol);
                $stmt->bindValue(":inicio", (int)$inicio, PDO::PARAM_INT);
                $stmt->bindValue(":cantidad", (int)$cantidad, PDO::PARAM_INT);

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error al mostrar clientes: " . $e->getMessage());
            }
        }

        public function contarClientes() {
            try {
                $rol = 'cliente';
                $sql = "SELECT COUNT(*) as total FROM usuarios WHERE ROL=:rol";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":rol", $rol);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultado ? $resultado['total'] : 0;
            } catch (PDOException $e) {
                die("Error al contar clientes: " . $e->getMessage());
            }
        }

        public function eliminarUsuarios($id){
            try {
                $this->db->beginTransaction();
                $sql = "DELETE FROM usuarios WHERE ID_USUARIO=:id_usuario";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id_usuario", $id);
                $stmt->execute();
                $this->db->commit();
                return "Usuario eliminado";
            } catch (PDOException $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                die("Error al eliminar Usuarios: " . $e->getMessage());
            }
        }

        public function editarUsuarios($id_usuario,$rol){//Metodo para editar usuarios
            try{
                $this->db->beginTransaction();

                if(!empty($rol)){//Comprobamos que el rol esta vacio para actualizarlo
                    $sql="UPDATE usuarios SET ROL=:rol WHERE ID_USUARIO=:id_usuario";
                    $stmt=$this->db->prepare($sql);
                    $stmt->bindParam(":rol", $rol, PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                }

                
                $this->db->commit();
                return "Datos cambiados exitosamente";
            }catch(PDOException $e){
                if($this->db->inTransaction()){
                    $this->db->rollBack();
                }
                die("Error al editar Perfil: ".$e->getMessage());
            }
        }
    }
?>