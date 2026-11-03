
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Dorot</title>
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
            <a href="registro.php">Crear cuenta</a>
        </nav>
    </header>

    <main class="auth-contenedor">

        <form action="procesar_login.php" method="POST">

            <p class="etiqueta">BIENVENIDO DE NUEVO</p>

            <h1>Iniciar sesión</h1>

            <p class="auth-descripcion">
                Ingresá tus datos para acceder a tu cuenta.
            </p>

            <label for="email">Correo electrónico</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="ejemplo@gmail.com"
                required
            >

            <label for="password">Contraseña</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Ingresá tu contraseña"
                required
            >

            <button type="submit" class="auth-boton">
                Ingresar
            </button>

            <p class="auth-enlace">
                ¿Todavía no tenés cuenta?
                <a href="registro.php">Registrate acá</a>
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
