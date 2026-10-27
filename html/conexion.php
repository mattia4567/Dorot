<?php

mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "jewchetti_secret";
echo "hola";
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);
echo "hola 2";

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

?>