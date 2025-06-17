<?php
require_once  __DIR__ . ("/../model/Cliente.php");
require_once  __DIR__ . ("/../model/Documento.php");
require_once  __DIR__ . ("/../model/LogModel.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class ClienteController
{
    private $ClienteModel;
    private $DocumentoModel;
    private $LogModel;

    public function __construct()
    {
        $this->ClienteModel = new Cliente();
        $this->DocumentoModel = new Documento();
        $this->LogModel = new LogModel();
    }
    public function listAll()
    {
        if (!isset($_SESSION['idusuario'])) {
            return ["error" => "No tienes permisos para acceder a esta información."];
        }
        $idusuario = $_SESSION['idusuario'];
        $listado = $this->ClienteModel->listar($idusuario);
        header('Content-Type: application/json; charset=utf-8');
        if (empty($listado)) {
            // La lista está vacía, enviamos un mensaje indicándolo
            echo json_encode(['message' => 'No se encontraron clientes.', 'data' => []]);
        } else {
            // La lista tiene datos, los enviamos
            echo json_encode($listado);
        }
    }
    public function newCliente()
    {
        header('Content-Type: application/json; charset=utf-8');
        $inputData = $_POST;
        // En caso de JSON (para datos de texto)
        if (empty($inputData) && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $jsonInput = file_get_contents("php://input");
            $inputData = json_decode($jsonInput, true);
        }
        // Los archivos subidos siempre están en $_FILES, no en $_POST ni en php://input
        $archivo = $_FILES['archivo'] ?? null;
        // --- Extracción y limpieza de datos de texto ---
        $nom_cli = htmlspecialchars(trim($inputData['nom_cli'] ?? ''));
        $empresa = htmlspecialchars(trim($inputData['empresa'] ?? ''));
        $correo_cli = htmlspecialchars(trim($inputData['correo_cli'] ?? ''));
        $telefono_cli = htmlspecialchars(trim($inputData['telefono_cli'] ?? ''));
        $estado_cli = htmlspecialchars(trim($inputData['estado_cli'] ?? ''));
        if (!isset($_SESSION['idusuario'])) {
            return ["error" => "No tienes permisos para acceder a esta información."];
        }
        $idusuario = $_SESSION['idusuario'];
        // --- Validaciones de campos obligatorios ---
        if (empty($idusuario)) {
            echo json_encode(['error' => 'El campo idusuario es obligatorio']);
            return;
        }
        if (empty($nom_cli)) {
            echo json_encode(['error' => 'El campo nombre es obligatorio']);
            return;
        }
        if (empty($estado_cli)) {
            echo json_encode(['error' => 'El campo estado es obligatorio']);
            return;
        }
        $nombreArchivo = null;
        $tipoArchivo = null; // Para guardar el tipo de documento (ej. 'pdf', 'docx')
        // --- 1. Registrar el Cliente primero ---
        // Tu método createCliente en el modelo de Cliente ya no necesita el argumento $archivo
        $idCliente = $this->ClienteModel->createCliente(
            $nom_cli,
            $empresa,
            $correo_cli,
            $telefono_cli,
            $estado_cli,
            $idusuario // Aquí ya no se pasa $nombreArchivo
        );
        // Si el cliente no se registró, enviamos el error y terminamos
        if (!is_int($idCliente) || $idCliente <= 0) {
            // El modelo Cliente::createCliente ahora debería devolver un mensaje de error o false
            echo json_encode(["error" => "Error al registrar el cliente: " . $idCliente]); // Si $idCliente es un mensaje de error
            // O: echo json_encode(["error" => "Error desconocido al registrar el cliente."]); si solo devuelve false
            return;
        }
        // --- 2. Procesar y Registrar el Archivo (si se subió uno) ---
        // Solo si el cliente fue registrado exitosamente
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $mimeType = $archivo['type'];
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/vnd.ms-excel', // .xls
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // .xlsx
            ];
            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                echo json_encode(["error" => "Tipo de archivo no permitido. Solo se aceptan PDF, Word (doc, docx) y Excel (xls, xlsx)."]);
                // Considera eliminar el cliente si el archivo es obligatorio y falló la validación aquí
                return;
            }
            $maxFileSize = 5 * 1024 * 1024; // 5 MB
            if ($archivo['size'] > $maxFileSize) {
                echo json_encode(["error" => "El archivo es demasiado grande. Tamaño máximo permitido: 5 MB."]);
                // Considera eliminar el cliente si el archivo es obligatorio y falló la validación aquí
                return;
            }
            $id_documento_unico = uniqid();
            // --- Generar nombre único para el archivo ---
            $nombreArchivo = basename($archivo['name']); // Ej. 'doc_60b7c2a4f3d1b.pdf'
            $tipoArchivo = $extension; // Guarda la extensión como el "tipo_do"
            $directorio = __DIR__ . "/../../uploads/";
            $rutaArchivo = $directorio . $id_documento_unico . "." . $extension;
            if (!is_dir($directorio)) {
                if (!mkdir($directorio, 0755, true)) { // Usa 0755 o 0775, no 0777 en producción
                    echo json_encode(["error" => "Error al crear el directorio de uploads."]);
                    // Considera eliminar el cliente si el archivo es obligatorio y falló la creación del directorio
                    return;
                }
            }
            if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                echo json_encode(["error" => "Error al mover el archivo subido."]);
                // Considera eliminar el cliente si el archivo es obligatorio y falló la subida
                return;
            }
            $resultadoDoc = $this->DocumentoModel->newfile(
                $id_documento_unico, // El ID que generaste para el documento
                $nombreArchivo,      // El nombre del archivo guardado
                $tipoArchivo,        // La extensión del archivo
                $idCliente           // El ID del cliente que acabamos de registrar
            );
            // ¡LA CONDICIÓN CORREGIDA AHORA ES ASÍ DE SIMPLE!
            if ($resultadoDoc === false) { // Si el resultado es EXACTAMENTE false, hubo un error.
                echo json_encode(["error" => "Error al registrar el documento asociado en la base de datos."]);
                // Considera un "rollback" aquí si es necesario.
                return;
            }

            $this->LogModel->registrarLog(
                $idusuario,
                'Documento Registrado',
                "Se registró un documento '$nombreArchivo' para el cliente con ID $idCliente",
                $idCliente,
                'clientes'
            );

        } elseif ($archivo && $archivo['error'] !== UPLOAD_ERR_NO_FILE) {
            echo json_encode(["error" => "Error al subir el archivo: " . $this->getUploadErrorMessage($archivo['error'])]);
            // Considera eliminar el cliente si el archivo es obligatorio y falló la subida
            return;
        }

        $this->LogModel->registrarLog(
            $idusuario,
            'Cliente Registrado',
            "Se registró al cliente '$nom_cli' con ID $idCliente",
            $idCliente,
            'clientes'
        );

        // --- Respuesta final si todo fue exitoso ---
        echo json_encode(["success" => "Cliente registrado correctamente.", "cliente_id" => $idCliente]);
        // Si el archivo fue subido, también puedes incluir info del documento
        // "documento_id" => $id_documento_unico, "nombre_archivo" => $nombreArchivo
    }
    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "El archivo excede el tamaño máximo permitido por el servidor (php.ini).";
            case UPLOAD_ERR_FORM_SIZE:
                return "El archivo excede el tamaño máximo permitido por el formulario HTML.";
            case UPLOAD_ERR_PARTIAL:
                return "El archivo fue subido solo parcialmente.";
            case UPLOAD_ERR_NO_FILE:
                return "No se subió ningún archivo.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Falta una carpeta temporal.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Fallo al escribir el archivo en el disco.";
            case UPLOAD_ERR_EXTENSION:
                return "Una extensión de PHP detuvo la subida del archivo.";
            default:
                return "Error de subida desconocido.";
        }
    }
    public function updateCliente()
    {
        header('Content-Type: application/json; charset=utf-8');

        $inputData = $_POST;
        if (empty($inputData) && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $jsonInput = file_get_contents("php://input");
            $inputData = json_decode($jsonInput, true);
        }

        $archivo = $_FILES['archivo'] ?? null;

        $id_cliente = htmlspecialchars(trim($inputData['id_cliente'] ?? ''));
        $nom_cli = htmlspecialchars(trim($inputData['nom_cli'] ?? ''));
        $empresa = htmlspecialchars(trim($inputData['empresa'] ?? ''));
        $correo_cli = htmlspecialchars(trim($inputData['correo_cli'] ?? ''));
        $telefono_cli = htmlspecialchars(trim($inputData['telefono_cli'] ?? ''));
        $estado_cli = htmlspecialchars(trim($inputData['estado_cli'] ?? ''));
        if (!isset($_SESSION['idusuario'])) {
            return ["error" => "No tienes permisos para acceder a esta información."];
        }
        $idusuario = $_SESSION['idusuario'];

        if (empty($id_cliente)) {
            echo json_encode(['error' => 'El campo id_cliente es obligatorio para actualizar.']);
            return;
        }

        // Obtener cliente actual de la BD para verificar cambios
        $clienteActual = $this->ClienteModel->getClienteById($id_cliente);
        if (!$clienteActual) {
            echo json_encode(['error' => 'Cliente no encontrado']);
            return;
        }

        // Detectar si hay cambios en los datos del cliente
        $hayCambiosCliente = (
            $nom_cli !== $clienteActual['nom_cli'] ||
            $empresa !== $clienteActual['empresa'] ||
            $correo_cli !== $clienteActual['correo_cli'] ||
            $telefono_cli !== $clienteActual['telefono_cli'] ||
            $estado_cli !== $clienteActual['estado_cli']
        );

        $mensajeCliente = "";
        if ($hayCambiosCliente) {
            $resultadoCliente = $this->ClienteModel->update(
                $id_cliente,
                $nom_cli,
                $empresa,
                $correo_cli,
                $telefono_cli,
                $estado_cli
            );
            $mensajeCliente = $resultadoCliente ? "Cliente actualizado correctamente." : "No se actualizó el cliente.";
        }

        // Verificar si hay documento actual
        $documentoActual = $this->DocumentoModel->BuscarFilexCli($id_cliente);
        $documentoActual = is_array($documentoActual) ? [$documentoActual] : [];

        $mensajeDocumento = "";
        $documentoRegistrado = false;

        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $mimeType = $archivo['type'];
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];

            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                echo json_encode(["error" => "Tipo de archivo no permitido."]);
                return;
            }

            if ($archivo['size'] > 5 * 1024 * 1024) {
                echo json_encode(["error" => "El archivo es demasiado grande. Máximo 5MB."]);
                return;
            }


            foreach ($documentoActual as $doc) {
                $resultado = $this->DocumentoModel->eliminarArchivo($doc['id_documento']);
                if ($resultado !== true) {
                    echo json_encode(["error" => "Error al eliminar documento: " . json_encode($resultado)]);
                    return;
                }

                // Log de eliminación de documento
                $this->LogModel->registrarLog(
                    $idusuario,
                    'Documento Eliminado',
                    "Se eliminó el documento '{$doc['nombre_doc']}' con ID {$doc['id_documento']} del cliente con ID $id_cliente",
                    $id_cliente,
                    'clientes'
                );
            }


            $id_documento_unico = uniqid();
            $nombreArchivo = basename($archivo['name']);
            $directorio = __DIR__ . "/../../uploads/";
            $rutaArchivo = $directorio . $id_documento_unico . "." . $extension;

            if (!is_dir($directorio)) mkdir($directorio, 0755, true);

            if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                echo json_encode(["error" => "Error al mover el archivo subido."]);
                return;
            }

            $resultadoDoc = $this->DocumentoModel->newfile(
                $id_documento_unico,
                $nombreArchivo,
                $extension,
                $id_cliente
            );

            if ($resultadoDoc === false) {
                echo json_encode(["error" => "Error al registrar el documento."]);
                return;
            }

            $mensajeDocumento = "Documento actualizado correctamente.";
            $documentoRegistrado = true;

            $this->LogModel->registrarLog(
            $idusuario,
            'Documento Modificado',
            "Se actualizó el documento '$nombreArchivo' para el cliente con ID $id_cliente",
            $id_cliente,
            'clientes'
        );
        } elseif ($archivo && $archivo['error'] !== UPLOAD_ERR_NO_FILE) {
            echo json_encode(["error" => "Error al subir el archivo: " . $this->getUploadErrorMessage($archivo['error'])]);
            return;
        }

        if (!$hayCambiosCliente && !$documentoRegistrado) {
            echo json_encode(["info" => "No se realizaron cambios."]);
            return;
        }

        $this->LogModel->registrarLog(
            $idusuario,
            'Cliente Modificado',
            "Se modificó el cliente '$nom_cli' con ID $id_cliente",
            $id_cliente,
            'clientes'
        );


        $response = ["success" => "Actualización completada."];
        if ($hayCambiosCliente) $response["cliente"] = $mensajeCliente;
        if ($documentoRegistrado) $response["documento"] = $mensajeDocumento;



        echo json_encode($response);
    }
}
