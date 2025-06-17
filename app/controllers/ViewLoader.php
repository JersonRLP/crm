<?php
// Captura la vista enviada por GET
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'dashboard';

// Define la ruta del archivo
$filePath = __DIR__ . "/../views/partials/$vista.php";

// Verifica si el archivo existe antes de incluirlo
if (file_exists($filePath)) {
    include $filePath;
} else {
    echo "<p style='color:red;'>Error: La vista '$vista' no existe.</p>";
}
?>