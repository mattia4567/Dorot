<?php

require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($username == "" || $email == "" || $password == "") {

        $mensaje = "Completá todos los campos.";

    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (username, email, password_hash)
                VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {

            $mensaje = "Cuenta creada correctamente.";

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

        <label>Usuario:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Registrarse</button>

    </form>

    <br>

    <a href="login.php">Ya tengo una cuenta</a>

</body>

</html>