
<?php

session_start();

if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | Dorot</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="encabezado">
        <a class="logo" href="index.php">✡ Dorot</a>

        <nav class="navegacion">
            <a href="index.php">Inicio</a>
            <a href="remeras.html">Remeras</a>
            <a href="kipas.html">Kipás</a>
            <a href="joyeria.html">Joyería</a>
            <a href="login.php">Iniciar sesión</a>
        </nav>
    </header>

    <main class="auth-contenedor">

        <form action="procesar_registro.php" method="POST">

            <p class="etiqueta">BIENVENIDO A Dorot</p>

            <h1>Crear cuenta</h1>

            <p class="auth-descripcion">
                Registrate para formar parte de nuestra comunidad.
            </p>

            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Tu nombre" required>

            <label for="usuario">Nombre de usuario</label>
            <input type="text" id="usuario" name="usuario"
                   placeholder="Elegí un usuario" required>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email"
                   placeholder="ejemplo@gmail.com" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password"
                   placeholder="Ingresá tu contraseña" required>

            <label for="confirmar">Confirmar contraseña</label>
            <input type="password" id="confirmar" name="confirmar"
                   placeholder="Repetí tu contraseña" required>

            <button type="submit" class="auth-boton">
                Crear cuenta
            </button>

            <p class="auth-enlace">
                ¿Ya tenés una cuenta?
                <a href="login.php">Iniciá sesión</a>
            </p>

        </form>

    </main>

    <footer class="pie">

        <a class="logo" href="index.php">✡ Dorot</a>

        <p>Ropa y accesorios con identidad.</p>

        <span>© 2026 Dorot. Todos los derechos reservados.</span>

    </footer>

</body>
</html>
