<?php
    require_once("../includes/database.php"); 

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['usuario_id'];
        $name= $_POST['nombre'];
        $usuario =  $_POST['usuario'];
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

            if(!empty($id)){
                //update
                try {
                    $database = new Database();
                    $db = $database->getConnection();

                    $sql = "UPDATE usuarios
                    SET nombre = :nombre, usuario = :usuario, correo =:correo, rol=:rol, estado=:estado" .
                     (!empty($pass) ? ", clave = :clave" : "" ) . "
                    WHERE id_usuario = :id";

                    $name = htmlspecialchars(strip_tags($name));
                    $usuario = htmlspecialchars(strip_tags($usuario));
                    $email = htmlspecialchars(strip_tags($email));
                    $rol = htmlspecialchars(strip_tags($rol));
                    $estado = htmlspecialchars(strip_tags($estado));
                    $pass_hash = htmlspecialchars(strip_tags($pass_hash));
                    $id = htmlspecialchars(strip_tags($id));

                    $stmt = $db->prepare($sql);
                    if(!empty($pass)){
                        $stmt->bindParam(':nombre',$name);
                        $stmt->bindParam(':usuario',$usuario);
                        $stmt->bindParam(':correo',$email);
                        $stmt->bindParam(':rol',$rol);
                        $stmt->bindParam(':estado',$estado);
                        $stmt->bindParam(':clave',$pass_hash);
                        $stmt->bindParam('id',$id);
                    }else{
                        $stmt->bindParam(':nombre',$name);
                        $stmt->bindParam(':usuario',$usuario);
                        $stmt->bindParam(':correo',$email);
                        $stmt->bindParam(':rol',$rol);
                        $stmt->bindParam(':estado',$estado);
                        $stmt->bindParam('id',$id);
                    }

                    if($stmt->execute()){
                        echo "Usuario actualizado correctamente";
                    }else{
                         echo "Error, usuario no actualizado";
                    }
                }catch(Exception $e){
                    error_log("Error al actualizar el usuario: " . $e->getMessage());
                    echo "Error al actualizar el usuario: " . $e->getMessage();
                }
                
                
            }else{
                //CREATE->INSERT de un usuario
                try{
                    $database = new Database();
                    $db = $database->getConnection();
                    $sql = 'INSERT INTO usuarios (nombre, usuario, clave, correo, rol, estado) 
                    VALUES (:nombre,:usuario,:clave,:correo,:rol,:estado)';

                    $name = htmlspecialchars(strip_tags($name));
                    $usuario = htmlspecialchars(strip_tags($usuario));
                    $email = htmlspecialchars(strip_tags($email));
                    $rol = htmlspecialchars(strip_tags($rol));
                    $estado = htmlspecialchars(strip_tags($estado));
                    $pass_hash = htmlspecialchars(strip_tags($pass_hash));

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(':nombre',$name);
                    $stmt->bindParam(':usuario',$usuario);
                    $stmt->bindParam(':correo',$email);
                    $stmt->bindParam(':rol',$rol);
                    $stmt->bindParam(':estado',$estado);
                    $stmt->bindParam(':clave',$pass_hash);

                    if($stmt->execute()){
                        echo "Usuario creado correctamente";
                    }else{
                        echo "Error, el usuario no se pudo crear.";
                    }

                }catch(Exception $e){
                    error_log("Error al crear usuario: " . $e->getMessage());
                    echo "Error al crear usuario: " . $e->getMessage();

                }
                
            }    
        }
        exit();
   }

   if(isset($_GET['eliminar'])){
        $id = $_GET['eliminar'];

        try{
            $database = new Database();
            $db = $database->getConnection();
            $sql= "DELETE FROM usuarios WHERE id_usuario = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam('id',$id);
            $stmt->execute();
            exit();

        }catch(Exception $e){
            error_log("Error al eliminar usuario: " . $e->getMessage());
        }
    
   }
?>