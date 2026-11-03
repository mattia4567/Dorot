<?php

require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

$sql = "SELECT id, nombre, descripcion, precio, categoria, imagen, stock
        FROM productos";

$resultado = $conexion->query($sql);

$productos = [];

while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE);

$conexion->close();

?>