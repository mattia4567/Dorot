<?php

session_start();

require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT id, username, password_hash
            FROM usuarios
            WHERE username = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario["password_hash"])) {

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["username"] = $usuario["username"];

            header("Location: perfil.php");
            exit();

        } else {

            $mensaje = "Contraseña incorrecta.";

        }

    } else {

        $mensaje = "El usuario no existe.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Jewchetti Secret</title>
</head>

<body>

    <h1>Iniciar sesión</h1>

    <?php if ($mensaje != ""): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Usuario:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Iniciar sesión</button>

    </form>

    <br>

    <a href="registro.php">Crear una cuenta</a>

</body>

</html>