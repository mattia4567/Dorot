<?php

session_start();

require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["usuario_id"])) {
    echo json_encode(["logueado" => false, "items" => []]);
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$accion = $_GET["accion"] ?? "cargar";

if ($accion === "cargar") {

    $sql = "SELECT nombre, precio
            FROM carrito
            WHERE usuario_id = ?
            ORDER BY id";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $items = [];

    while ($fila = $resultado->fetch_assoc()) {
        $items[] = [
            "nombre" => $fila["nombre"],
            "precio" => (int) $fila["precio"],
        ];
    }

    $stmt->close();

    echo json_encode([
        "logueado" => true,
        "username" => $_SESSION["username"],
        "items" => $items,
    ]);

    exit();
}

if ($accion === "guardar" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $datos = json_decode(file_get_contents("php://input"), true);

    $items = (isset($datos["items"]) && is_array($datos["items"]))
        ? $datos["items"]
        : [];

    $conexion->begin_transaction();

    $borrar = $conexion->prepare("DELETE FROM carrito WHERE usuario_id = ?");
    $borrar->bind_param("i", $usuario_id);
    $borrar->execute();
    $borrar->close();

    $insertar = $conexion->prepare(
        "INSERT INTO carrito (usuario_id, nombre, precio) VALUES (?, ?, ?)"
    );

    $guardados = 0;

    foreach ($items as $item) {

        if (!isset($item["nombre"], $item["precio"])) {
            continue;
        }

        $nombre = mb_substr((string) $item["nombre"], 0, 100);
        $precio = max(0, (int) $item["precio"]);

        if ($nombre === "") {
            continue;
        }

        $insertar->bind_param("isi", $usuario_id, $nombre, $precio);

        if ($insertar->execute()) {
            $guardados++;
        }
    }

    $insertar->close();

    $conexion->commit();

    echo json_encode([
        "logueado" => true,
        "ok" => true,
        "guardados" => $guardados,
    ]);

    exit();
}

http_response_code(400);

echo json_encode(["error" => "Acción no válida."]);

?>
