<?php
require_once __DIR__ . "/../../config/Database.php";

class Cita
{
    private $conn;
    public function __construct()
    {
        $databaseCrm = new Database();
        $this->conn = $databaseCrm->getConnectionCrm();
    }

    public function listar($idusuario)
    {
        $stmt = $this->conn->prepare("SELECT * FROM citas WHERE idusuario = :idusuario");
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function createCita($fecha_cita, $lugar_cita, $tipo_cita, $objetivo_cita,$estado_cita,$comentarios_cita,$id_cliente,$idusuario)
    {
        try {
            $sql = "INSERT INTO citas (fecha_cita, lugar_cita, tipo_cita, objetivo_cita, estado_cita, comentarios_cita, id_cliente, idusuario)
                    VALUES (:fecha_cita, :lugar_cita, :tipo_cita, :objetivo_cita,:estado_cita, :comentarios_cita , :id_cliente, :idusuario)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
            $stmt->bindParam(':lugar_cita', $lugar_cita, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_cita', $tipo_cita, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios_cita', $comentarios_cita, PDO::PARAM_STR);
            $stmt->bindParam(':objetivo_cita', $objetivo_cita, PDO::PARAM_STR);
            $stmt->bindParam(':estado_cita', $estado_cita, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->execute();
            $lastInsertId = $this->conn->lastInsertId();
            return $lastInsertId > 0 ? (int)$lastInsertId : false;
        } catch (PDOException $e) {
            error_log("Error al Registrar Usuario: " . $e->getMessage());
            return false;
        }
    }

        public function update($id_cita, $fecha_cita, $lugar_cita, $tipo_cita, $objetivo_cita, $estado_cita, $comentarios_cita)
    {
        try {
            $sql = "UPDATE citas SET fecha_cita = :fecha_cita, lugar_cita = :lugar_cita, tipo_cita = :tipo_cita, objetivo_cita = :objetivo_cita , estado_cita = :estado_cita, comentarios_cita = :comentarios_cita WHERE id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
            $stmt->bindParam(':lugar_cita', $lugar_cita, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_cita', $tipo_cita, PDO::PARAM_STR);
            $stmt->bindParam(':objetivo_cita', $objetivo_cita, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios_cita', $comentarios_cita, PDO::PARAM_STR);
            $stmt->bindParam(':estado_cita', $estado_cita, PDO::PARAM_STR);
            $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? $id_cita : false;
        } catch (PDOException $e) {
            error_log("Error al Actualizar Cita: " . $e->getMessage());
            return false;
        }
    }

}