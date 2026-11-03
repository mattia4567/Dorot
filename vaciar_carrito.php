<?php

session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    exit("Iniciá sesión.");
}

$usuario_id = $_SESSION["usuario_id"];

$sql = "DELETE FROM carrito WHERE usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

header("Location: carrito_web.php");
exit;

?>