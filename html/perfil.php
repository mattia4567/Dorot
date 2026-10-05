<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";

$id = $_SESSION["usuario_id"];

$sql = "SELECT username, email, fecha_registro
        FROM usuarios
        WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil - Jewchetti Secret</title>
</head>

<body>

    <h1>Mi perfil</h1>

    <h2>Bienvenido, <?php echo htmlspecialchars($usuario["username"]); ?>!</h2>

    <p>
        <strong>Usuario:</strong>
        <?php echo htmlspecialchars($usuario["username"]); ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?php echo htmlspecialchars($usuario["email"]); ?>
    </p>

    <p>
        <strong>Fecha de registro:</strong>
        <?php echo $usuario["fecha_registro"]; ?>
    </p>

    <br>

    <a href="index.php">Volver a la tienda</a>

    <br><br>

    <a href="cerrar_sesion.php">Cerrar sesión</a>

</body>

</html>