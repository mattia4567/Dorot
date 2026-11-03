
<?php

session_start();
require_once "conexion.php";

$nombre = trim($_POST["nombre"] ?? "");
$username = trim($_POST["usuario"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirmar = $_POST["confirmar"] ?? "";

if (
    $nombre === "" ||
    $username === "" ||
    $email === "" ||
    $password === "" ||
    $confirmar === ""
) {
    die("Completá todos los campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no es válido.");
}

if (strlen($password) < 8) {
    die("La contraseña debe tener al menos 8 caracteres.");
}

if ($password !== $confirmar) {
    die("Las contraseñas no coinciden.");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios
        (nombre, username, email, password_hash)
        VALUES (?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ssss",
    $nombre,
    $username,
    $email,
    $password_hash
);

if ($stmt->execute()) {

    session_regenerate_id(true);

    $_SESSION["usuario_id"] = $stmt->insert_id;
    $_SESSION["username"] = $username;
    $_SESSION["nombre"] = $nombre;

    header("Location: index.php");
    exit;

} else {

    if ($conexion->errno === 1062) {
        echo "Ese usuario o correo ya está registrado.";
    } else {
        echo "No se pudo crear la cuenta.";
    }

}

$stmt->close();
$conexion->close();

?>
