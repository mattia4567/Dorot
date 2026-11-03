<?php

session_start();
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Tenés que iniciar sesión."]);
    exit;
}

$producto_id = (int)($_POST["producto_id"] ?? 0);
$cantidad = (int)($_POST["cantidad"] ?? 1);
$usuario_id = $_SESSION["usuario_id"];

if ($producto_id <= 0 || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incorrectos."]);
    exit;
}

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

$sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto agregado al carrito."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo agregar el producto."]);
}

$stmt->close();
$conexion->close();

?>