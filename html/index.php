<?php

session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Jewchetti Secret</title>
</head>

<body>

    <h1>Jewchetti Secret</h1>

    <?php if (isset($_SESSION["usuario_id"])): ?>

        <p>
            Bienvenido,
            <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
        </p>

        <a href="perfil.php">Mi perfil</a>

        <br><br>

        <a href="cerrar_sesion.php">Cerrar sesión</a>

    <?php else: ?>

        <a href="login.php">Iniciar sesión</a>

        <br><br>

        <a href="registro.php">Registrarse</a>

    <?php endif; ?>

</body>

</html>