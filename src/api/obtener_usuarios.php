<?php
       require_once("../includes/database.php"); 

   
    try{
        $database = new Database();
        $db = $database->getConnection();
        $query = "SELECT id_usuario,nombre,usuario,correo,rol,estado FROM usuarios";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $resultado = $stmt->fetchAll();

        if($resultado && count($resultado)  >0){
            foreach($resultado as $usuario){
      
                 echo  "<tr>
                            <td>". htmlspecialchars($usuario['nombre'])  . "</td>
                            <td>" . htmlspecialchars($usuario['usuario'])  . "</td>
                            <td>" . htmlspecialchars($usuario['correo'])  . "</td>
                            <td>" . htmlspecialchars($usuario['rol'])  . "</td>
                            <td>" . htmlspecialchars($usuario['estado'])  . "</td>
                            <td>
                                <a href='#'
                                data-id='{$usuario['id_usuario']}'
                                data-nombre='{$usuario['nombre'] }'
                                data-usuario='{$usuario['usuario']}'
                                data-correo='{$usuario['correo']}'
                                data-rol='{$usuario['rol']}'
                                data-estado='{$usuario['estado']}'
                                class='btn btn-warning btn-sm btnEditar'>Editar</a>
                                <a href='#' data-id='{$usuario['id_usuario']}' class='btn btn-danger btn-sm btnEliminar'>Eliminar</a>
                            </td>
                        </tr>";
            }
         }

    }catch(Exception $e){
        error_log("Error al obtener usuarios: " . $e->getMessage());

    }

   




?>