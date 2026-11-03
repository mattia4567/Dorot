<?php

session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

try {

    $conexion->begin_transaction();

    $sql = "SELECT
                carrito.producto_id,
                carrito.cantidad,
                productos.nombre,
                productos.precio,
                productos.stock
            FROM carrito
            INNER JOIN productos
                ON carrito.producto_id = productos.id
            WHERE carrito.usuario_id = ?
            FOR UPDATE";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $productos = [];
    $total = 0;

    while ($fila = $resultado->fetch_assoc()) {

        if ($fila["cantidad"] > $fila["stock"]) {
            throw new Exception("No hay suficiente stock de " . $fila["nombre"]);
        }

        $productos[] = $fila;
        $total += $fila["precio"] * $fila["cantidad"];
    }

    $stmt->close();

    if (count($productos) === 0) {
        throw new Exception("El carrito está vacío.");
    }

    $sql = "INSERT INTO pedidos (usuario_id, total)
            VALUES (?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("id", $usuario_id, $total);
    $stmt->execute();

    $pedido_id = $stmt->insert_id;
    $stmt->close();

    foreach ($productos as $producto) {

        $sql = "INSERT INTO detalle_pedidos
                (pedido_id, producto_id, nombre_producto, precio, cantidad)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "iisdi",
            $pedido_id,
            $producto["producto_id"],
            $producto["nombre"],
            $producto["precio"],
            $producto["cantidad"]
        );

        $stmt->execute();
        $stmt->close();

        $sql = "UPDATE productos
                SET stock = stock - ?
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "ii",
            $producto["cantidad"],
            $producto["producto_id"]
        );

        $stmt->execute();
        $stmt->close();
    }

    $sql = "DELETE FROM carrito WHERE usuario_id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    $conexion->commit();

    echo "¡Compra registrada correctamente! Número de pedido: " . $pedido_id;

} catch (Exception $e) {

    $conexion->rollback();
    echo "No se pudo completar el pedido: " . $e->getMessage();

}

$conexion->close();

?>