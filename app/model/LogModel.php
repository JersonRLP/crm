<?php
require_once __DIR__ . "/../../config/Database.php";

class LogModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnectionCrm();
    }

    public function registrarLog($idusuario, $accion, $descripcion, $id_objeto_afectado, $tabla_afectada)
    {
        try {
            $sql = "INSERT INTO logs (idusuario, accion, descripcion, id_objeto_afectado, tabla_afectada, fecha_log)
                    VALUES (:idusuario, :accion, :descripcion, :id_objeto_afectado, :tabla_afectada, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->bindParam(':accion', $accion, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':id_objeto_afectado', $id_objeto_afectado, PDO::PARAM_INT);
            $stmt->bindParam(':tabla_afectada', $tabla_afectada, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error al registrar log: " . $e->getMessage());
            return false;
        }
    }
}
