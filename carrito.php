<?php

session_start();
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Iniciá sesión para ver tu carrito."]);
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

$sql = "SELECT
            productos.id,
            productos.nombre,
            productos.precio,
            productos.imagen,
            carrito.cantidad,
            productos.precio * carrito.cantidad AS subtotal
        FROM carrito
        INNER JOIN productos
            ON carrito.producto_id = productos.id
        WHERE carrito.usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();

$productos = [];
$total = 0;

while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
    $total += $fila["subtotal"];
}

echo json_encode([
    "productos" => $productos,
    "total" => $total
], JSON_UNESCAPED_UNICODE);

$stmt->close();
$conexion->close();

?>