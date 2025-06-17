<?php
require_once  __DIR__ . ("/../model/Cita.php");
require_once  __DIR__ . ("/../model/LogModel.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CitaController
{
    private $CitaModel;
    private $LogModel;
    public function __construct()
    {
        $this->CitaModel = new Cita();
        $this->LogModel = new LogModel();
    }

    public function listAll()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['idusuario'])) {

            echo json_encode(["error" => "No tienes permisos para acceder a esta información."]);
            return;
        }

        $idusuario = $_SESSION['idusuario'];
        $listado = $this->CitaModel->listar($idusuario);

        if (empty($listado)) {
            // La lista está vacía, enviamos un mensaje indicándolo
            echo json_encode(['message' => 'No se encontraron Citas.', 'data' => []]);
        } else {
            // La lista tiene datos, los enviamos
            echo json_encode($listado);
        }
    }

    public function newCita()
    {
        header('Content-Type: application/json; charset=utf-8');

        $inputData = $_POST;

        // En caso de JSON
        if (empty($inputData)) {
            $jsonInput = file_get_contents("php://input");
            $inputData = json_decode($jsonInput, true);
        }
        if (!isset($_SESSION['idusuario'])) {

            echo json_encode(["error" => "No tienes permisos para acceder a esta información."]);
            return;
        }
        // Sanitizar
        $fecha_cita = htmlspecialchars(trim($inputData['fecha_cita'] ?? ''));
        $lugar_cita = htmlspecialchars(trim($inputData['lugar_cita'] ?? ''));
        $tipo_cita = htmlspecialchars(trim($inputData['tipo_cita'] ?? ''));
        $objetivo_cita = htmlspecialchars(trim($inputData['objetivo_cita'] ?? ''));
        $estado_cita = htmlspecialchars(trim($inputData['estado_cita'] ?? ''));
        $comentarios_cita = htmlspecialchars(trim($inputData['comentarios_cita'] ?? ''));
        $id_cliente = htmlspecialchars(trim($inputData['id_cliente'] ?? ''));
        $idusuario = $_SESSION['idusuario'];

        // Validaciones
        if (empty($tipo_cita)) {
            json_encode(['error' => 'El campo tipo_cita es obligatorio']);
            return;
        }

        if (empty($estado_cita)) {
            json_encode(['error' => 'El campo estado_cita de la cita es obligatorio']);
            return;
        }

        if (empty($id_cliente)) {
            json_encode(['error' => 'El campo id_cliente es obligatorio']);
            return;
        }
        $resultado = $this->CitaModel->createCita($fecha_cita, $lugar_cita, $tipo_cita, $objetivo_cita, $estado_cita, $comentarios_cita, $id_cliente, $idusuario);

        if ($resultado) {

            $this->LogModel->registrarLog(
            $idusuario,
            'Cita Registrada',
            "Se registró una cita con ID $resultado",
            $resultado,
            'citas'
        );
            echo json_encode(['success' => 'Cita registrada correctamente']);
        } else {
            echo json_encode(['error' => 'Error al registrar cita']);
        }
    }

    public function update()
    {
        header('Content-Type: application/json; charset=utf-8');
        $inputData = $_POST;
        // En caso de JSON
        if (empty($inputData)) {
            $jsonInput = file_get_contents("php://input");
            $inputData = json_decode($jsonInput, true);
        }
        // Sanitizar
        $id_cita = htmlspecialchars(trim($inputData['id_cita'] ?? ''));
        $fecha_cita = htmlspecialchars(trim($inputData['fecha_cita'] ?? ''));
        $lugar_cita = htmlspecialchars(trim($inputData['lugar_cita'] ?? ''));
        $tipo_cita = htmlspecialchars(trim($inputData['tipo_cita'] ?? ''));
        $objetivo_cita = htmlspecialchars(trim($inputData['objetivo_cita'] ?? ''));
        $estado_cita = htmlspecialchars(trim($inputData['estado_cita'] ?? ''));
        $comentarios_cita = htmlspecialchars(trim($inputData['comentarios_cita'] ?? ''));

        if (!isset($_SESSION['idusuario'])) {
        echo json_encode(['error' => 'No tienes permisos para realizar esta acción']);
        return;
        }

        $idusuario = $_SESSION['idusuario'];

        $resultado = $this->CitaModel->update($id_cita, $fecha_cita, $lugar_cita, $tipo_cita, $objetivo_cita, $estado_cita, $comentarios_cita);

        if ($resultado) {
            $this->LogModel->registrarLog(
            $idusuario,
            'Cita Actualizada',
            "Se actualizó la cita con ID $id_cita",
            $id_cita,
            'citas'
        );
            echo json_encode(['success' => 'Cita Modificada correctamente']);
        } else {
            echo json_encode(['error' => 'Error al Modificar cita']);
        }
    }
}
