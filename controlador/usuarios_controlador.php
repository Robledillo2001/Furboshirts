<?php
    require_once "modelo/usuario_modelo.php";
    class usuarios_controlador{
        private function checkAdmin(){//Metodo privado que se usaran en los metodos que use el admin para que el Cliente y el Visitante no puedan acceder
            if(!isset($_SESSION['ROL']) || $_SESSION['ROL'] !== "admin"){
                header("Location: index.php?action=inicio");//Y redirija al Inicio directamente
                exit();//Finaliza la ejecucion del script para que ocurra la redireccion
            }
        }

        public function configuracion(){
            require_once "vista/configuracion.php";
        }

        public function GestionAdmin(){//Metodo para ver a todos los admins
            $this->checkAdmin();

            $modelo=new Usuarios();

            //Configuracion de la paginacion
            $users=5;
            $paginaActual=isset($_GET['pagina'])? (int)$_GET['pagina']:1;
            if($paginaActual<1)$paginaActual=1;

            $inicio=($paginaActual-1)*$users;

            //Obtener los datos del modelo
            $totalAdmins=$modelo->contarAdmins();
            $totalPaginas=ceil($totalAdmins/$users);

            //Obtenemos todos los Administradores de la pagina
            $admins=$modelo->mostrarAdministradores($inicio,$users);

            //Cargar la vista con los admis
            require_once "vista/usuarios/GestionAdmin.php";
        }

        public function anadirAdmin(){//Metodo para añadir administradores
            $this->checkAdmin();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//Recogida de datos del formulario
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $correo = $_POST['correo'];
                $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT); 
                $nombreUser = $_POST['nombreUser'];

                //Llamada al metodo del modelo
                $modelo = new Usuarios();
                $modelo->añadirAdmins($nombre, $apellidos, $correo, $passwd, $nombreUser);

                // Redirige al Gstor de admins al registrar administrador
                header("Location: index.php?action=GestionAdmin");
                exit();//Finaliza la ejecucion del script para que ocurra la redireccion
            } else {
                require_once "vista/usuarios/AnadirAdmin.php";
            }
        }

        public function GestionClientes(){//Metodo para ver todos los clientes
            $this->checkAdmin();

            $modelo=new Usuarios();

            //Configuracion de la paginacion
            $users = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            if($paginaActual < 1) $paginaActual = 1;

            $inicio=($paginaActual-1)*$users;

            //Obtener los datos del modelo
            $totalClientes = $modelo->contarClientes();
            $totalPaginas = ceil($totalClientes / $users);

            //Obtenemos todos los Clientes de la pagina
            $clientes=$modelo->mostrarClientes($inicio,$users);

            //Cargar la vista con los admis
            require_once "vista/usuarios/GestionClientes.php";
        }
        //Menu para el admin
        
        public function MenuAdmin(){//Menu del administrador
            $this->checkAdmin();
            require_once "vista/MenuAdmin.php";
        }

        public function EliminarUsuario(){
            $this->checkAdmin();
            if(isset($_GET['id'])){
                $id=(int)$_GET['id'];
                $origen = $_GET['from']?? '';

                $modelo=new Usuarios();
                $modelo->eliminarUsuarios($id);

                // Redirección dinámica
                if ($origen === 'GestionAdmin') {//Si se elimina el usuario desde GestionAdmin se redirigira a Gestion Admin
                    header("Location: index.php?action=GestionAdmin");
                } else {//Si no se redirigira a GestionEquipos
                    header("Location: index.php?action=GestionClientes");
                }
                exit();
            }
        }

        public function EditarPerfil(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $id=$_SESSION['id'];
                $nombre=$_POST['nombre']??"";
                $apellidos=$_POST['apellidos']??"";
                $nombreUser=$_POST['nombreUser'] ?? "";
                $correo=$_POST['correo'] ?? "";
                $passwd=$_POST['passwd']?? "";
                $passwd2=$_POST['passwd2'] ?? "";
                $passwdHash="";

                $modelo=new Usuarios();

                if(!empty($passwd)&&!empty($passwd2)){
                    if($passwd===$passwd2){
                        $passwdHash=password_hash($passwd,PASSWORD_DEFAULT) ?? "";
                    }else{
                        header("Location: index.php?action=configuracion&error=pass");
                        exit();
                    }
                }
                $modelo->editarPerfil($id,$nombre,$apellidos,$nombreUser,$correo,$passwdHash);

                $_SESSION['nombre'] = (!empty($nombreUser)) ? $nombreUser : $_SESSION['nombre'];
                $_SESSION['nombre_real']=(!empty($nombre)) ? $nombre : $_SESSION['nombre_real'];
                $_SESSION['apellidos']=(!empty($apellidos)) ? $apellidos : $_SESSION['apellidos'];
                $_SESSION['correo']=(!empty($correo)) ? $correo : $_SESSION['correo'];


                header("Location: index.php?action=configuracion&success=1");
                exit();
            }
            require_once "vista/usuarios/EditarPerfil.php";
        }

        public function EditarUsuario(){//Metodo para editar Usuarios
            $this->checkAdmin();
            if($_SERVER['REQUEST_METHOD']=='POST' && isset($_GET['id'])){
                $id=$_GET['id'];
                $rol=$_POST['rol']?? "";
                $origen = $_GET['from']?? '';//Origen segun el tipo de usuario al que se le quiera cabiar el rol

                $modelo=new Usuarios();

                $modelo->editarUsuarios($id,$rol);

                // Redirección dinámica
                if ($origen === 'GestionAdmin') {//Si se elimina el usuario desde GestionAdmin se redirigira a Gestion Admin
                    header("Location: index.php?action=GestionAdmin");
                } else {//Si no se redirigira a GestionEquipos
                    header("Location: index.php?action=GestionClientes");
                }
                exit();
            }
            require_once "vista/usuarios/EditarUsuarios.php";
        }

        public function CambiarIMgPerfil(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                 if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK){
                    $id=$_SESSION['id'];
                    $nombreImg=$_FILES['imagen']['name'];

                    $rutaTemporal = $_FILES['imagen']['tmp_name'];
                    
                    // Creamos la ruta
                    $carpetaDestino = "assets/img/users/";
                    // Si la carpeta no existe, la creamos
                    if (!file_exists($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }

                    $rutaFinal = $carpetaDestino . time() . "_" . $nombreImg;

                    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                        $modelo = new Usuarios();
                        $modelo->CambiarIMgPerfil($id,$rutaFinal);
                        $_SESSION['IMAGEN']=$rutaFinal;
                        header("Location: index.php?action=configuracion&success=1");
                        exit();
                    } else {
                        header("Location: index.php?action=configuracion&error=pass");
                        exit();
                    }
                } else {
                    header("Location: index.php?action=configuracion&error=pass");
                    exit();
                }
            }
            require_once "vista/usuarios/CambiarIMGPerfil.php";
        }

        public function login(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $usuarioInput = $_POST['usuario'];
                $passwd = $_POST['passwd'];
                $recordar=$_POST['recordar']?$_POST['recordar']: null;

                $modelo = new Usuarios();
                $datosUsuario = $modelo->login($usuarioInput, $passwd);

                if($datosUsuario){
                    // GUARDAR TODO EN LA SESIÓN (Importante para el header y editar los datos del usuario en un futuro)
                    $_SESSION['id'] = $datosUsuario['ID_USUARIO'];
                    $_SESSION['nombre'] = $datosUsuario['NOMBRE_USUARIO'];
                    $_SESSION['nombre_real']=$datosUsuario['NOMBRE'];
                    $_SESSION['apellidos']=$datosUsuario['APELLIDOS'];
                    $_SESSION['correo']=$datosUsuario['CORREO'];
                    $_SESSION['ROL'] = $datosUsuario['ROL']; // Sin esto, el header no cambia
                    $_SESSION['IMAGEN'] = $datosUsuario['IMAGEN_USER'];
                    //Si se le dio a la opcion de recordar se guardara una cookie que dure 30 dias
                    if($recordar){
                        setcookie($_SESSION['id'],$_SESSION['nombre'],time()+(30*24*60*60),"/");//Creamos una cookie que dure 30 días
                    }else{
                        setcookie($_SESSION['id'],$_SESSION['nombre'],time()-3600, "/");//Si no marco la casilla, borramos la cookie (poniendo pasado)
                    }

                    if($datosUsuario['ROL']!=='admin'){//Si no es un admin se redirigira al inicio de la pagina
                        if(isset($_SESSION['url'])){//Si encuentra una sesion con una url al agregar productos al carrito o hacer una valoracion
                            $destino=$_SESSION['url'];
                            unset($_SESSION['URL_PENDIENTE']); // Limpiamos para que no se repita
                            header("Location: " . $destino);
                            exit(); 
                        }else{
                            header("Location: index.php?action=inicio");
                            exit(); 
                        }
                         
                    }else{//Si es admin se redirigira a su propio menu
                        header("Location: index.php?action=MenuAdmin");
                        exit(); //Finaliza la ejecucion del script para que ocurra la redireccion
                    }
                } else {//Saltara error si el login es incorrecto
                    $error = "Usuario o contraseña incorrectos";
                    require_once "vista/usuarios/login.php";
                }
            } else {
                require_once "vista/usuarios/login.php";
            }
        }

        public function registrar(){//Metodo para registrar un usuario
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//Recogida de datos del formulario
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $correo = $_POST['correo'];
                $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT); 
                $nombreUser = $_POST['nombreUser'];

                //Llamada al metodo del modelo
                $modelo = new Usuarios();
                $modelo->registrar($nombre, $apellidos, $correo, $passwd, $nombreUser);

                // Redirige al login al registrar usuario
                header("Location: index.php?action=login");
                exit();//Finaliza la ejecucion del script para que ocurra la redireccion
            } else {
                require_once "vista/usuarios/registrar.php";
            }
        }

        public function logout(){
            session_start();//Reanudar sesion existente para manipular los datos $_SESSION
            session_unset();//Elimina todas las variables de la sesion actual
            session_destroy();//Destruye todas las sesiones

            header("Location: index.php?action=login");//Redirije al login
            exit();//Finaliza la ejecucion del script para que ocurra la redireccion
        }
    }
?>