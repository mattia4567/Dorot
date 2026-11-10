
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
$producto_id = (int)($_POST["producto_id"] ?? 0);
$cantidad = (int)($_POST["cantidad"] ?? 0);

if ($producto_id <= 0 || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incorrectos."]);
    exit;
}

// Comprobar que el producto exista y tenga stock suficiente.
$sql = "SELECT stock FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $producto_id);
$stmt->execute();

$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();
$stmt->close();

if (!$producto) {
    http_response_code(404);
    echo json_encode(["error" => "El producto no existe."]);
    exit;
}

if ($cantidad > $producto["stock"]) {
    http_response_code(400);
    echo json_encode(["error" => "No hay suficiente stock."]);
    exit;
}

// Actualizar solamente el carrito del usuario conectado.
$sql = "UPDATE carrito
        SET cantidad = ?
        WHERE producto_id = ? AND usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iii", $cantidad, $producto_id, $usuario_id);
$stmt->execute();

if ($stmt->affected_rows >= 0 && $stmt->errno === 0) {
    echo json_encode(["mensaje" => "Cantidad actualizada."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo actualizar."]);
}

$stmt->close();
$conexion->close();

?>
