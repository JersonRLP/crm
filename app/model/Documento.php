<?php
require_once __DIR__ . "/../../config/Database.php";

class Documento
{
    private $conn;
    public function __construct()
    {
        $databaseCrm = new Database();
        $this->conn = $databaseCrm->getConnectionCrm();
    }

    public function newfile($id_documento, $nom_do, $tipo_do, $id_cliente)
    {
        try {
            $sql = "INSERT INTO documentos (id_documento, nom_do, tipo_do, id_cliente)
                    VALUES ( :id_documento, :nom_do, :tipo_do,:id_cliente)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_documento', $id_documento, PDO::PARAM_STR);
            $stmt->bindParam(':nom_do', $nom_do, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_do', $tipo_do, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->execute();
            return $id_documento ? $id_documento : false;
        } catch (PDOException $e) {
            error_log("Error al Registrar Usuario: " . $e->getMessage());
            return false;
        }
    }

    public function Buscarfile($id_documento)
    {
        $stmt = $this->conn->prepare("SELECT * from documentos where id_documento = :id_documento");
        $stmt->bindParam(':id_documento', $id_documento, PDO::PARAM_INT);
        $stmt->execute();
        $fileData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fileData ? $fileData : false;
    }


        public function BuscarFilexCli($id_cliente)
    {
        $stmt = $this->conn->prepare("SELECT * from documentos where id_cliente = :id_cliente");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $fileData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fileData ? $fileData : false;
    }

public function eliminarArchivo($id_documento)
{
    $file = $this->Buscarfile($id_documento);

    if (!$file) {
        error_log("Archivo con ID $id_documento no encontrado en BD.");
        return false;
    }

    // Construir ruta física del archivo
    $nombreArchivoFisico = $file['id_documento'] . '.' . $file['tipo_do'];
    $rutaArchivo = __DIR__ . '/../../uploads/' . $nombreArchivoFisico;

    // Eliminar archivo físico si existe
    if (file_exists($rutaArchivo)) {
        if (!unlink($rutaArchivo)) {
            error_log("❌ No se pudo eliminar el archivo físico '$rutaArchivo'");
            return ["error" => "No se pudo eliminar el archivo físico."];
        } else {
            error_log("✅ Archivo físico eliminado: '$rutaArchivo'");
        }
    } else {
        error_log("⚠️ Archivo '$rutaArchivo' no encontrado, se elimina solo la referencia en la BD.");
    }

    // Eliminar registro de la base de datos
    try {
        $stmt = $this->conn->prepare("DELETE FROM documentos WHERE id_documento = ?");
        $stmt->execute([$id_documento]);
        return true;
    } catch (PDOException $e) {
        error_log("Error al eliminar de la BD: " . $e->getMessage());
        return ["error" => "Error al eliminar de la BD: " . $e->getMessage()];
    }
}

}
