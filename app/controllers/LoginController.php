<?php
require_once  __DIR__ . ("/../model/Login.php");

// Asegúrate de iniciar la sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class LoginController
{
    private $LoginModel;

    public function __construct()
    {
        $this->LoginModel = new Login();
    }



public function login()
{
    header('Content-Type: application/json; charset=utf-8'); // ← importante

    $inputData = $_POST;

    if (empty($inputData)) {
        $jsonInput = file_get_contents("php://input");
        $inputData = json_decode($jsonInput, true);
    }

    if (empty($inputData)) {
        echo json_encode(["error" => "No se recibieron datos de usuario y contraseña."]);
        exit;
    }

    $usuario = trim($inputData['usuario'] ?? '');
    $password = trim($inputData['password'] ?? '');

    if (empty($usuario) || empty($password)) {
        echo json_encode(["error" => "Por favor, ingresa tu usuario y contraseña."]);
        exit;
    }

    $user = $this->LoginModel->getUserByUsername($usuario);

    if ($user && $password === $user['password']) {
        session_regenerate_id(true);
        $_SESSION['idusuario'] = $user['idusuario'];
        $_SESSION['nombres'] = $user['nombres'];
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['loggedin'] = true;

        echo json_encode(["success" => true]);
        exit;
    } else {
        echo json_encode(["error" => "Usuario o contraseña incorrectos."]);
        exit;
    }
}


    public function logout() {
        // 1. Elimina todas las variables de la sesión del array $_SESSION.
        // Esto limpia los datos sensibles.
        $_SESSION = array();

        // 2. Destruye la cookie de sesión en el navegador (altamente recomendado).
        // Esto asegura que el navegador del usuario "olvide" el ID de sesión.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 3. Destruye la sesión del lado del servidor.
        // Esto elimina el archivo de sesión o la entrada de la sesión de la base de datos.
        session_destroy();

        // 4. Redirige al usuario a la página de login, pasando un indicador de éxito por URL.
        // La ruta '/proveedores/login' debe ser la URL correcta para tu página de login.
        header("Location: /proveedores/login?logout_status=success");
        exit(); // Termina la ejecución del script después de la redirección.
    }

}