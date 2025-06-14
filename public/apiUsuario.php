<?php
require_once __DIR__ . '/../app/controllers/UsuarioController.php';

$controller = new UsuarioController();
$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $controller->listAll();
        break;
    case 'create':
        $controller->newUser();
        break;
    case 'update':
        $controller->EditUser();
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
