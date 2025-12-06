<?php
       require_once("../includes/database.php"); 
       require_once("../models/usuario.php");

   
    try{
        $database = new Database();
        $db = $database->getConnection();
        
        $usuario =  new Usuario($db);

        $resultado = $usuario->obtenerTodos();

        header('Content-Type: application/json');

        if($resultado && count($resultado)  >0){
            echo json_encode($resultado);
         }else {
            echo json_encode([]);
         }

    }catch(Exception $e){
        error_log("Error al obtener usuarios: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error'=>"Error al obtener usuarios: " . $e->getMessage()]);
    }

   




?>