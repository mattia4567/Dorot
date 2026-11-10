
<?php

session_start();
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Iniciá sesión."]);
    exit;
}

$producto_id = (int)($_POST["producto_id"] ?? 0);
$usuario_id = $_SESSION["usuario_id"];

if ($producto_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Producto inválido."]);
    exit;
}

$sql = "DELETE FROM carrito
        WHERE producto_id = ? AND usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $producto_id, $usuario_id);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto eliminado."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo eliminar."]);
}

$stmt->close();
$conexion->close();

?>
