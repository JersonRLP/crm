<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnectionCrm();

if ($conn) {
    echo "✅ Conexión exitosa a la base de datos.<br>";

    /* // Realiza una consulta simple
    $stmt = $conn->query("SELECT * FROM cliente");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT * FROM concepto_tarifario");
    $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    echo "<pre>";
    print_r($result2);
    echo "</pre>"; */
} else {
    echo "❌ Falló la conexión a la base de datos.";
}
