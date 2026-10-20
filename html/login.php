<?php

session_start();

require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identificador = trim($_POST["username"] ?? $_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT id, username, password_hash
            FROM usuarios
            WHERE username = ? OR email = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $identificador, $identificador);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario["password_hash"])) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["username"] = $usuario["username"];

            $permitidos = ["index.html", "index.php", "perfil.php"];

            $retorno = $_POST["retorno"] ?? "index.html";

            if (!in_array($retorno, $permitidos, true)) {
                $retorno = "index.html";
            }

            header("Location: " . $retorno);
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

        <input type="hidden" name="retorno" value="index.html">

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