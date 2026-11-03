<?php

session_start();

$mensaje = $_SESSION["mensaje_bienvenida"] ?? "";

unset($_SESSION["mensaje_bienvenida"]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dorot </title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
 
    <?php if ($mensaje !== ""): ?>

        <div class="mensaje-bienvenida">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

    <header class="encabezado">
    <a class="logo" href="index.php">✡ Dorot</a>

    <nav class="navegacion">
        <a class="activo" href="index.php">Inicio</a>
        <a href="remeras.html">Remeras</a>
        <a  href="kipas.html">Kipás</a>
        <a href="joyeria.html">Joyería</a>
      
        <?php if (isset($_SESSION["usuario_id"])): ?>

    <span class="usuario-nav">
        Hola, <?php echo htmlspecialchars($_SESSION["username"]); ?>
    </span>

    <a href="cerrar_sesion.php">Cerrar sesión</a>

<?php else: ?>

    <a href="login.php">Iniciar sesión</a>
    <a href="registro.php">Crear cuenta</a>

<?php endif; ?>

        <a href="#" class="boton-carrito"
        onclick="abrirCarrito(); return false;">
            🛒 Carrito
            <span class="contador-carrito" id="contador-carrito">0</span>
        </a>
    </nav>
</header>

    <main>
        <section class="portada">
            <p class="etiqueta">IDENTIDAD · ESTILO · TRADICIÓN</p>
            <h1>Vestí tus raíces.</h1>
            <p class="descripcion">Una colección inspirada en la cultura judía, pensada para llevar tu identidad con estilo.</p>
            <a class="boton" href="#secciones">Explorar colección</a>
        </section>

        <section class="secciones" id="secciones">
            <div class="titulo-seccion">
                <p class="etiqueta">NUESTRA COLECCIÓN</p>
                <h2>Encontrá tu estilo</h2>
                <p>Elegí una categoría para descubrir todos sus productos.</p>
            </div>
            <div class="grilla">
                <a class="tarjeta categoria categoria-remeras" href="remeras.html">
                    <div class="categoria-contenido">
                        <span>01 / INDUMENTARIA</span>
                        <h3>Remeras</h3>
                        <p>Diseños para todos los días.</p>
                        <strong>Ver colección →</strong>
                    </div>
                </a>
                <a class="tarjeta categoria categoria-kipas" href="kipas.html">
                    <div class="categoria-contenido">
                        <span>02 / TRADICIÓN</span>
                        <h3>Kipás</h3>
                        <p>Distintos estilos y materiales.</p>
                        <strong>Ver colección →</strong>
                    </div>
                </a>
                <a class="tarjeta categoria categoria-joyeria" href="joyeria.html">
                    <div class="categoria-contenido">
                        <span>03 / ACCESORIOS</span>
                        <h3>Joyería</h3>
                        <p>Detalles que representan.</p>
                        <strong>Ver colección →</strong>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <footer class="pie">
        <a class="logo" href="index.html">✡ Dorot</a>
        <p>Ropa y accesorios con identidad.</p>
        <span>© 2026 Dorot. Todos los derechos reservados.</span>
    </footer>
   <div class="carrito-fondo" id="carrito-fondo"
     onclick="cerrarCarrito()"></div>

<aside class="carrito-panel" id="carrito-panel">

    <div class="carrito-encabezado">
        <h2>Mi carrito</h2>

        <button class="carrito-cerrar"
                onclick="cerrarCarrito()">
            &times;
        </button>
    </div>

    <div id="carrito-productos"></div>

    <div class="carrito-resumen">
        <div class="carrito-total-fila">
            <span>Total:</span>
            <span id="carrito-total">$0</span>
        </div>

        <button class="carrito-comprar"
                onclick="finalizarCompra()">
            FINALIZAR COMPRA
        </button>

        <button class="carrito-vaciar"
                onclick="vaciarCarrito()">
            Vaciar carrito
        </button>
    </div>

</aside>

<script src="carrito.js"></script>
</body>
</html>