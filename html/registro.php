<?php

session_start();

require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $password2 = $_POST["password2"] ?? "";

    $permitidos = ["index.html", "index.php", "login.php"];

    $retorno = $_POST["retorno"] ?? "index.html";

    if (!in_array($retorno, $permitidos, true)) {
        $retorno = "index.html";
    }

    if ($username == "" || $email == "" || $password == "") {

        $mensaje = "Completá todos los campos.";

    } elseif ($password !== $password2) {

        $mensaje = "Las contraseñas no coinciden.";

    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (username, email, password_hash)
                VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $conexion->insert_id;
            $_SESSION["username"] = $username;

            header("Location: " . $retorno);
            exit();

        } else {

            if ($conexion->errno == 1062) {
                $mensaje = "El usuario o email ya existe.";
            } else {
                $mensaje = "Error al crear la cuenta.";
            }
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrarse - Jewchetti Secret</title>
</head>

<body>

    <h1>Crear cuenta</h1>

    <?php if ($mensaje != ""): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="retorno" value="index.html">

        <label>Usuario:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <br><br>

        <label>Repetir contraseña:</label>
        <input type="password" name="password2" required>

        <br><br>

        <button type="submit">Registrarse</button>

    </form>

    <br>

    <a href="login.php">Ya tengo una cuenta</a>

</body>

</html>