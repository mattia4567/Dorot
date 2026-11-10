
<?php
session_start();
require_once "conexion.php";

// Comprobar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

// Comprobar el ID recibido
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"]) || (int)$_GET["id"] < 1) {
    header("Location: mis_pedidos.php");
    exit;
}

$pedido_id = (int)$_GET["id"];

// Buscar el pedido verificando que pertenezca al usuario
$sql = "SELECT id, fecha_pedido, total, estado
        FROM pedidos
        WHERE id = ? AND usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $pedido_id, $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$pedido = $resultado->fetch_assoc();

$stmt->close();

// Si no existe o pertenece a otro usuario
if (!$pedido) {
    http_response_code(404);
    exit("Pedido no encontrado.");
}

// Buscar los productos del pedido
$sql = "SELECT nombre_producto, precio, cantidad
        FROM detalle_pedidos
        WHERE pedido_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();

$productos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalle del pedido | DOROT</title>

    <link rel="stylesheet" href="style.css">

    <style>
        .detalle-contenedor {
            max-width: 900px;
            margin: 60px auto;
            padding: 20px;
        }

        .detalle-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .producto-fila {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }

        .detalle-total {
            text-align: right;
            color: #102f57;
            font-size: 23px;
            font-weight: bold;
            margin-top: 25px;
        }

        .detalle-enlace {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 17px;
            background: #102f57;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        .detalle-enlace:hover {
            background: #f5c542;
            color: #102f57;
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

<main class="detalle-contenedor">

    <h1>Pedido #<?php echo $pedido["id"]; ?></h1>

    <p>
        Fecha:
        <?php echo date("d/m/Y H:i", strtotime($pedido["fecha_pedido"])); ?>
    </p>

    <p>
        Estado:
        <strong><?php echo htmlspecialchars($pedido["estado"]); ?></strong>
    </p>

    <section class="detalle-card">

        <h2>Productos comprados</h2>

        <?php if ($productos->num_rows > 0): ?>

            <?php while ($producto = $productos->fetch_assoc()): ?>

                <div class="producto-fila">

                    <div>
                        <strong>
                            <?php echo htmlspecialchars($producto["nombre_producto"]); ?>
                        </strong>

                        <p>
                            Cantidad: <?php echo $producto["cantidad"]; ?>
                        </p>
                    </div>

                    <div>
                        $<?php echo number_format($producto["precio"], 2, ",", "."); ?>
                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No hay productos registrados en este pedido.</p>

        <?php endif; ?>

        <div class="detalle-total">
            Total: $<?php echo number_format($pedido["total"], 2, ",", "."); ?>
        </div>

    </section>

    <a class="detalle-enlace" href="mis_pedidos.php">
        Volver a mis compras
    </a>

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
