<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto"])) {
    $foto = $_FILES["foto"];
    $nombreFoto = uniqid() . "_" . basename($foto["name"]);
    $directorio = "uploads/perfiles/";

    // ✅ Verificar si la carpeta existe, si no, crearla
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $rutaDestino = $directorio . $nombreFoto;

    $tipoPermitido = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($foto["type"], $tipoPermitido) && move_uploaded_file($foto["tmp_name"], $rutaDestino)) {
        $conn = new mysqli($host, $user, $password, $dbname);
        $sql = "UPDATE user SET foto=? WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombreFoto, $username);
        $stmt->execute();
        header("Location: perfil.php");
    } else {
        echo "❌ Error al subir la imagen. Asegúrate de que sea JPG o PNG.";
    }
}
