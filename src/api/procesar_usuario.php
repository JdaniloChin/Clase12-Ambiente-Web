<?php
    require_once("../includes/database.php"); 
    require_once("../models/usuario.php");

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['usuario_id'];
        $name= $_POST['nombre'];
        $user =  $_POST['usuario'];
        $email =  $_POST['email'];
        $pass =  $_POST['password'];
        $confirm =  $_POST['confirm'];
        $rol =  $_POST['rol'];
        $estado =  $_POST['estado'];



        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo  "Email invalido";
        }elseif($pass !== $confirm){
            echo "Contraseñas no coinciden ";
        }else {
            $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

            
                try {
                    $database = new Database();
                    $db = $database->getConnection();

                    $usuario = new Usuario($db);

                    $usuario->nombre = htmlspecialchars(strip_tags($name));
                    $usuario->usuario = htmlspecialchars(strip_tags($user));
                    $usuario->correo = htmlspecialchars(strip_tags($email));
                    $usuario->rol = htmlspecialchars(strip_tags($rol));
                    $usuario->estado = htmlspecialchars(strip_tags($estado));
                    $usuario->clave = htmlspecialchars(strip_tags($pass_hash));
                    $usuario->id_usuario = htmlspecialchars(strip_tags($id));
                    

                    if(!empty($id)){
                        if($usuario->actualizarUsurio()){
                            echo "Usuario actualizado correctamente";
                        }else{
                            echo "Error, usuario no actualizado";
                        }
                    }else{
                        if($usuario->crearUsuario()){
                            echo "Usuario creado correctamente";
                        }else{
                         echo "Error, el usuario no se pudo crear.";
                        }

                    }
                    
                }catch(Exception $e){
                    error_log("Error de conexion con la base de datos: " . $e->getMessage());
                    echo "Error de conexion con la base de datos: " . $e->getMessage();
                }
        }
        exit();
   }

   if(isset($_GET['eliminar'])){
        $id = $_GET['eliminar'];

        try{
            $database = new Database();
            $db = $database->getConnection();
           
            $usuario = new Usuario($db);
            $usuario->id_usuario = $id;

            if($usuario->eliminarUsuario()){
                echo "Usuario " . $id . " eliminado correctamente";
            }else{
                echo "Error al eliminar el usuario: " .$id;
            }
            exit();

        }catch(Exception $e){
            error_log("Error al eliminar usuario: " . $e->getMessage());
        }
    
   }
?>