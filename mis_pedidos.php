
<?php
session_start();
require_once "conexion.php";

// Comprobar que el usuario inició sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

// Buscar los pedidos del usuario conectado
$sql = "SELECT id, fecha_pedido, total, estado
        FROM pedidos
        WHERE usuario_id = ?
        ORDER BY fecha_pedido DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mis compras | DOROT</title>

    <link rel="stylesheet" href="style.css">

    <style>
        .pedidos-contenedor {
            max-width: 1000px;
            margin: 60px auto;
            padding: 20px;
        }

        .pedido-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .pedido-superior {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pedido-estado {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .pedido-total {
            color: #102f57;
            font-size: 20px;
            font-weight: bold;
        }

        .pedido-enlace {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 16px;
            background: #102f57;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        .pedido-enlace:hover {
            background: #f5c542;
            color: #102f57;
        }

        .sin-pedidos {
            text-align: center;
            padding: 40px 15px;
        }
    </style>
</head>

<body>

<header class="encabezado">
    <a class="logo" href="index.php">✡ Dorot</a>

    <nav class="navegacion">
        <a href="index.php">Inicio</a>
        <a href="remeras.html">Remeras</a>
        <a href="kipas.html">Kipás</a>
        <a href="joyeria.html">Joyería</a>
        <a href="carrito.php">Carrito</a>
        <span>Hola, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="pedidos-contenedor">

    <h1>Mis compras</h1>

    <p>Acá podés consultar el historial de tus pedidos en DOROT.</p>

    <?php if ($resultado->num_rows > 0): ?>

        <?php while ($pedido = $resultado->fetch_assoc()): ?>

            <article class="pedido-card">

                <div class="pedido-superior">

                    <div>
                        <h2>Pedido #<?php echo $pedido["id"]; ?></h2>

                        <p>
                            Fecha:
                            <?php echo date("d/m/Y H:i", strtotime($pedido["fecha_pedido"])); ?>
                        </p>
                    </div>

                    <span class="pedido-estado">
                        <?php echo htmlspecialchars($pedido["estado"]); ?>
                    </span>

                </div>

                <p class="pedido-total">
                    Total: $<?php echo number_format($pedido["total"], 2, ",", "."); ?>
                </p>

                <a
                    class="pedido-enlace"
                    href="detalle_pedido.php?id=<?php echo $pedido["id"]; ?>"
                >
                    Ver detalles
                </a>

            </article>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="sin-pedidos">
            <h2>Todavía no realizaste compras</h2>
            <p>Cuando hagas tu primera compra, aparecerá acá.</p>

            <a class="pedido-enlace" href="index.php">
                Explorar productos
            </a>
        </div>

    <?php endif; ?>

</main>

<footer class="pie">
    <a class="logo" href="index.php">✡ Dorot</a>
    <p>Ropa y accesorios con identidad.</p>
    <span>© 2026 Dorot. Todos los derechos reservados.</span>
</footer>

</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
