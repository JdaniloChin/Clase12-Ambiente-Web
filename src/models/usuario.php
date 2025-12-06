<?php
/**
 * Modelo para la tabla de usuarios
 * Contiene todas las operaciones CRUD
 */

class Usuario{
    private $conn;
    private $table_name = "usuarios";

    //Atributos usuarios
    public $id_usuario;
    public $nombre;
    public $usuario;
    public $correo;
    public $clave;
    public $rol;
    public $estado;

    public function __construct($db){
        $this->conn =$db;
    }

    /**
     * Obtener todos los usuarios
     */

    public function obtenerTodos(){
        $query = "SELECT id_usuario,nombre,usuario,correo,rol,estado FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtener usuario por corrreo
     */

    public function obtenerPorCorreo($correo){
        $sql = "SELECT nombre, clave, rol FROM usuarios WHERE correo = :correo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":correo",$correo);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** 
     * Crear un nuevo usuario
     */

    public function crearUsuario(){
        $sql = 'INSERT INTO usuarios (nombre, usuario, clave, correo, rol, estado) 
                    VALUES (:nombre,:usuario,:clave,:correo,:rol,:estado)';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':usuario',$this->usuario);
        $stmt->bindParam(':correo',$this->correo);
        $stmt->bindParam(':rol',$this->rol);
        $stmt->bindParam(':estado',$this->estado);
        $stmt->bindParam(':clave',$this->clave);

        if($stmt->execute()){
            $this->id_usuario = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Actualizar Usuario
     */

    public function actualizarUsurio(){
        $sql = "UPDATE usuarios
                    SET nombre = :nombre, usuario = :usuario, correo =:correo, rol=:rol, estado=:estado" .
                     (!empty($this->clave) ? ", clave = :clave" : "" ) . "
                    WHERE id_usuario = :id";

        $stmt = $this->conn->prepare($sql);
        if(!empty($this->clave)){
            $stmt->bindParam(':nombre',$this->nombre);
            $stmt->bindParam(':usuario',$this->usuario);
            $stmt->bindParam(':correo',$this->correo);
            $stmt->bindParam(':rol',$this->rol);
            $stmt->bindParam(':estado',$this->estado);
            $stmt->bindParam(':clave',$this->clave);
            $stmt->bindParam('id',$this->id_usuario);
        }else{
            $stmt->bindParam(':nombre',$this->nombre);
            $stmt->bindParam(':usuario',$this->usuario);
            $stmt->bindParam(':correo',$this->correo);
            $stmt->bindParam(':rol',$this->rol);
            $stmt->bindParam(':estado',$this->estado);
            $stmt->bindParam('id',$this->id_usuario);
        }

         if($stmt->execute()){
            return true;
         }

         return false;
    }

    /**
     * Eliminar usuario
     */

    public function eliminarUsuario(){
        $sql= "DELETE FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam('id',$this->id_usuario);
        
        if($stmt->execute()){
            return true;
        }

        return false;
    }
}
?>