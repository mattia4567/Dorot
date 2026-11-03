<?php

session_start();
require_once "conexion.php";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    die("Completá todos los campos.");
}

$sql = "SELECT id, username, password_hash
        FROM usuarios
        WHERE email = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($password, $usuario["password_hash"])) {

        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["username"] = $usuario["username"];

        header("Location: index.php");
        exit;

    }
}

echo "Correo o contraseña incorrectos.";

$stmt->close();
$conexion->close();

?>