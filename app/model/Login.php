<?php
require_once __DIR__ . "/../../config/Database.php";

class Login
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnectionCrm();
    }

        public function getUserByUsername($usuario)
    {
        $query = "SELECT * FROM usuario WHERE  usuario= :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}