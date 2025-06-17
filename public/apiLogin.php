<?php
require_once __DIR__ . '/../app/controllers/LoginController.php';

$controller = new LoginController();
$action = $_GET['action'] ?? '';

switch ($action) {

    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
