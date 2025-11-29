<?php
//Control conexion a la base de datos
/***
 * Objeto de configuracion para conectarse a MySQL por variables de entorno
 */

class Database {
    private $host;
    private $database_name;
    private $username;
    private $password;
    private $connection;

    public function __construct()
    {
        // obtener configuracion desde variables de entorno (docker)
        $this->host = $_ENV['DB_HOST'] ?? 'db';
        $this->database_name = $_ENV['DB_NAME'] ?? 'tienda_app';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? 'rootpassword';
    }

    /**
     * Obtener conexion a la base de datos
     */

    public function getConnection(){
        $this->connection = null;

        try {
            $dsn = "mysql:host=". $this->host . ";dbname=" . $this->database_name . ";charset=utf8";
            $this->connection = new PDO($dsn,$this->username,$this->username);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }catch(PDOException $exception) {
            error_log("Error de conexion: " . $exception->getMessage());
            throw new Exception("Error al conectar a la base de datos");
        }

        return $this->connection;
    }

}

?>