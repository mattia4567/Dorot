<?php

$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "dorot";

$conexion = new mysqli(
    $host,
    $usuario,
    $contrasena,
    $base_datos
);

if ($conexion->connect_error) {
    die("Error al conectar con la base de datos");
}

$conexion->set_charset("utf8mb4");

?>