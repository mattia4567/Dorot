
<?php

session_start();
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Iniciá sesión."]);
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

$sql = "DELETE FROM carrito WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Carrito vaciado."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo vaciar el carrito."]);
}

$stmt->close();
$conexion->close();

?>
