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



    public function login() {
        // Paso 1: Obtener los datos del cuerpo de la solicitud (FormData o JSON)
        $inputData = $_POST; // Intenta obtener de FormData primero

        // Si $_POST está vacío, intenta leer como JSON (para peticiones tipo application/json)
        if (empty($inputData)) {
            $jsonInput = file_get_contents("php://input");
            $inputData = json_decode($jsonInput, true); // true para array asociativo
        }

        // Verifica si inputData está vacío después de ambos intentos
        if (empty($inputData)) {
            $_SESSION['error'] = "No se recibieron datos de usuario y contraseña.";
            header("Location: /crm/app/views/Login");
            exit();
        }

        // Paso 2: Limpiar y validar la entrada
        // Usa el operador de coalescencia nula (?? '') para evitar errores si las claves no existen
        $usuario = trim($inputData['usuario'] ?? '');
        $password = trim($inputData['password'] ?? ''); // Asumo 'password' como clave para la contraseña

        if (empty($usuario) || empty($password)) {
            $_SESSION['error'] = "Por favor, ingresa tu usuario y contraseña.";
            header("Location: /crm/app/views/Login");
            exit();
        }

        // Paso 3: Obtener el usuario de la base de datos
        // Asegúrate de que la columna en la BD para el nombre de usuario sea 'usuario'
        // Y que tu método getUserByUsername en el modelo devuelva el campo 'password' tal cual está en la BD.
        $user = $this->LoginModel->getUserByUsername($usuario);

        // Paso 4: Verificar si el usuario existe Y si la contraseña es correcta (COMPARACIÓN DE TEXTO PLANO)
        // ESTA ES LA COMPARACIÓN INSEGURA, PERO ES LO QUE SOLICITASTE.
        if ($user && $password === $user['password']) {
            // Contraseña correcta: Iniciar sesión

            // Regenerar el ID de sesión para prevenir ataques de fijación de sesión
            session_regenerate_id(true);

            // Almacenar datos del usuario en la sesión
            $_SESSION['idusuario'] = $user['idusuario'];
            $_SESSION['nombres'] = $user['nombres'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['loggedin'] = true;

            // Redirigir al dashboard
            // Ajusta la ruta si es necesario
            header("Location: /crm/app/views/dashboard.php");
            exit();

        } else {
            // Usuario no encontrado o contraseña incorrecta
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header("Location: /crm/app/views/Login"); // Redirigir al login
            exit();
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