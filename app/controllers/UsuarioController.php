<?php
require_once  __DIR__ . ("/../model/Usuario.php");

class UsuarioController
{
    private $UsuarioModel;

    public function __construct()
    {
        $this->UsuarioModel = new Usuario();
    }

    public function listAll() {

    $listado = $this->UsuarioModel->listar();

    header('Content-Type: application/json; charset=utf-8');

    if (empty($listado)) {
        // La lista está vacía, enviamos un mensaje indicándolo
        echo json_encode(['message' => 'No se encontraron usuarios.', 'data' => []]);
    } else {
        // La lista tiene datos, los enviamos
        echo json_encode($listado);
    }

    }


public function newUser() {
    header('Content-Type: application/json; charset=utf-8');

    $inputData = $_POST;

    // En caso de JSON
    if (empty($inputData)) {
        $jsonInput = file_get_contents("php://input");
        $inputData = json_decode($jsonInput, true);
    }

    // Sanitizar
    $nombres = htmlspecialchars(trim($inputData['nombres'] ?? ''));
    $usuario = htmlspecialchars(trim($inputData['usuario'] ?? ''));
    $password = htmlspecialchars(trim($inputData['password'] ?? ''));
    $telefono = htmlspecialchars(trim($inputData['telefono'] ?? ''));
    $cargo = htmlspecialchars(trim($inputData['cargo'] ?? ''));
    $correo = htmlspecialchars(trim($inputData['correo'] ?? ''));

    // Validaciones
    if (empty($nombres)){
            json_encode(['error' => 'El campo nombre es obligatorio']);
            return;
    }

    if (empty($usuario)){
            json_encode(['error' => 'El campo usuario es obligatorio']);
            return;
    }

    if (empty($password)) {
        json_encode(['error' => 'El campo password es obligatorio']);
        return;
    }
    if (empty($telefono)){
        json_encode(['error' => 'El campo telefono  es obligatorio']);
        return;
    }

    if (empty($cargo)){
        json_encode(['error' => 'El campo cargo es obligatorio']);
        return;
    }
    if (empty($correo)){
        json_encode(['error' => 'El campo correo es obligatorio']);
        return;
    }

    if ($this->UsuarioModel->existeUsuario($usuario)) {
        echo json_encode(['error' => 'Usuario ya está registrado']);
        return;
    }

    if ($this->UsuarioModel->existeTelefono($telefono)) {
        echo json_encode(['error' => 'Telefono ya está registrado']);
        return;
    }

    if ($this->UsuarioModel->existeCorreo($correo)) {
        echo json_encode(['error' => 'Telefono ya está registrado']);
        return;
    }
    $resultado = $this->UsuarioModel->createUsuario($nombres, $usuario, $password, $telefono, $cargo ,$correo);

    if ($resultado) {
        echo json_encode(['success' => 'Usuario registrado correctamente']);
    } else {
        echo json_encode(['error' => 'Error al registrar usuario']);
    }
}

public function EditUser() {
    header('Content-Type: application/json; charset=utf-8');

    $inputData = $_POST;

    // En caso de JSON
    if (empty($inputData)) {
        $jsonInput = file_get_contents("php://input");
        $inputData = json_decode($jsonInput, true);
    }

    // Sanitizar
    $idusuario = htmlspecialchars(trim($inputData['idusuario'] ?? ''));
    $nombres = htmlspecialchars(trim($inputData['nombres'] ?? ''));
    $usuario = htmlspecialchars(trim($inputData['usuario'] ?? ''));
    $password = htmlspecialchars(trim($inputData['password'] ?? ''));
    $telefono = htmlspecialchars(trim($inputData['telefono'] ?? ''));
    $cargo = htmlspecialchars(trim($inputData['cargo'] ?? ''));
    $correo = htmlspecialchars(trim($inputData['correo'] ?? ''));
    $estado = htmlspecialchars(trim($inputData['estado'] ?? ''));
    // Validaciones
    if (empty($idusuario)){
            json_encode(['error' => 'El campo idusuario es obligatorio']);
            return;
    }
    if (empty($nombres)){
            json_encode(['error' => 'El campo nombre es obligatorio']);
            return;
    }

    if (empty($usuario)){
            json_encode(['error' => 'El campo usuario es obligatorio']);
            return;
    }

    if (empty($password)) {
        json_encode(['error' => 'El campo password es obligatorio']);
        return;
    }
    if (empty($telefono)){
        json_encode(['error' => 'El campo telefono  es obligatorio']);
        return;
    }

    if (empty($cargo)){
        json_encode(['error' => 'El campo cargo es obligatorio']);
        return;
    }
    if (empty($correo)){
        json_encode(['error' => 'El campo correo es obligatorio']);
        return;
    }
    if (empty($estado)){
        json_encode(['error' => 'El campo correo es obligatorio']);
        return;
    }

    $resultado = $this->UsuarioModel->update($idusuario,$nombres, $usuario, $password, $telefono, $cargo ,$correo, $estado);

    if ($resultado) {
        echo json_encode(['success' => 'Usuario Modificado correctamente']);
    } else {
        echo json_encode(['error' => 'Error al Modificar usuario']);
    }
}

}