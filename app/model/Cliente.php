<?php
require_once __DIR__ . "/../../config/Database.php";

class Cliente
{
    private $conn;
    public function __construct()
    {
        $databaseCrm = new Database();
        $this->conn = $databaseCrm->getConnectionCrm();
    }

    public function listar($idusuario)
    {
        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE estado_cli != 'cerrado' AND idusuario = :idusuario");
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


        public function createCliente($nom_cli, $empresa, $correo_cli, $telefono_cli,$estado_cli,$idusuario)
    {
        try {
            $sql = "INSERT INTO clientes (nom_cli, empresa, correo_cli, telefono_cli, estado_cli, idusuario)
                    VALUES ( :nom_cli, :empresa, :correo_cli,:telefono_cli ,:estado_cli , :idusuario)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom_cli', $nom_cli, PDO::PARAM_STR);
            $stmt->bindParam(':empresa', $empresa, PDO::PARAM_STR);
            $stmt->bindParam(':correo_cli', $correo_cli, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_cli', $telefono_cli, PDO::PARAM_STR);
            $stmt->bindParam(':estado_cli', $estado_cli, PDO::PARAM_STR);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_STR);
            $stmt->execute();
            $lastInsertId = $this->conn->lastInsertId();
            return $lastInsertId > 0 ? (int)$lastInsertId : false;
        } catch (PDOException $e) {
            error_log("Error al Registrar Usuario: " . $e->getMessage());
            return false;
        }
    }

        public function update($id_cliente, $nom_cli, $empresa, $correo_cli, $telefono_cli, $estado_cli)
    {
        try {
            $sql = "UPDATE clientes SET nom_cli = :nom_cli, empresa = :empresa, correo_cli = :correo_cli, telefono_cli = :telefono_cli , estado_cli = :estado_cli WHERE id_cliente = :id_cliente";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->bindParam(':nom_cli', $nom_cli, PDO::PARAM_STR);
            $stmt->bindParam(':empresa', $empresa, PDO::PARAM_STR);
            $stmt->bindParam(':correo_cli', $correo_cli, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_cli', $telefono_cli, PDO::PARAM_STR);
            $stmt->bindParam(':estado_cli', $estado_cli, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? $id_cliente : false;
        } catch (PDOException $e) {
            error_log("Error al Actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function getClienteById($id_cliente)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE id_cliente = :id_cliente");
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cliente ? $cliente : false;
        } catch (PDOException $e) {
            error_log("Error DB al obtener cliente por ID: " . $e->getMessage());
            return "Error DB: " . $e->getMessage(); // Retorna el mensaje de error si hay una excepción
        }
    }

}