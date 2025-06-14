<?php
require_once __DIR__ . '/../app/controllers/ClienteController.php';

$controller = new ClienteController();
$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $controller->listAll();
        break;
    case 'newCliente':
        $controller->newCliente();
        break;
    case 'updateCli':
        $controller->updateCliente();
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
