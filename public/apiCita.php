<?php
require_once __DIR__ . '/../app/controllers/CitaController.php';

$controller = new CitaController();
$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $controller->listAll();
        break;
    case 'NewCita':
        $controller->NewCita();
        break;
    case 'update':
        $controller->update();
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
