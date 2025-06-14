<?php
require_once __DIR__ . "/../../config/Database.php";

class Usuario
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnectionCrm();
    }

        public function listar()
    {
        $stmt = $this->conn->query("SELECT idusuario, nombres, usuario, password, telefono, tipo, area, cargo, correo, estado  from usuario where  estado =1 and area = 'comercial';");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createUsuario($nombres, $usuario, $password, $telefono,$cargo,$correo)
    {
        try {
            $sql = "INSERT INTO usuario (nombres, usuario, password, telefono, tipo, area, cargo, correo, estado)
                    VALUES (:nombres, :usuario, :password, :telefono,'VENT','comercial',:cargo, :correo ,1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':cargo', $cargo, PDO::PARAM_STR);
            $stmt->execute();
            $lastInsertId = $this->conn->lastInsertId();
            return $lastInsertId > 0 ? (int)$lastInsertId : false;
        } catch (PDOException $e) {
            error_log("Error al Registrar Usuario: " . $e->getMessage());
            return false;
        }
    }

        public function update($idusuario, $nombres, $usuario, $password, $telefono, $cargo, $correo , $estado)
    {
        try {
            $sql = "UPDATE usuario SET nombres = :nombres, usuario = :usuario, password = :password, telefono = :telefono , cargo = :cargo, correo = :correo, estado = :estado WHERE idusuario = :idusuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':cargo', $cargo, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? $idusuario : false;
        } catch (PDOException $e) {
            error_log("Error al Actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

        public function BuscarxId($id){
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ? and estado = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        public function existeUsuario($usuario) {
        $stmt = $this->conn->prepare("SELECT count(*) from usuario where usuario = ? and estado =1 ");
        $stmt->execute([$usuario]);
        return $stmt->fetchColumn() > 0;
    }
        public function existeTelefono($usuario) {
        $stmt = $this->conn->prepare("SELECT count(*) from usuario where telefono = ? and estado =1 ");
        $stmt->execute([$usuario]);
        return $stmt->fetchColumn() > 0;
    }

        public function existeCorreo($usuario) {
        $stmt = $this->conn->prepare("SELECT count(*) from usuario where correo = ? and estado =1 ");
        $stmt->execute([$usuario]);
        return $stmt->fetchColumn() > 0;
    }
}