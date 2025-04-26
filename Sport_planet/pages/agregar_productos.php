<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $imagen = $_FILES['imagen'];

    if ($nombre !== '' && $precio > 0 && $imagen['error'] === 0) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($extension), $extensionesPermitidas)) {
            $nombreArchivo = uniqid('img_') . '.' . $extension;
            $rutaDestino = '../assets/img/' . $nombreArchivo;

            if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                $conn = new mysqli($host, $user, $password, $dbname);
                if ($conn->connect_error) {
                    die("❌ Error de conexión: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, imagen) VALUES (?, ?, ?)");
                $stmt->bind_param("sds", $nombre, $precio, $nombreArchivo);

                if ($stmt->execute()) {
                    $mensaje = "✅ Producto agregado correctamente.";
                } else {
                    $mensaje = "❌ Error al guardar en la base de datos.";
                }

                $stmt->close();
                $conn->close();
            } else {
                $mensaje = "❌ Error al subir la imagen.";
            }
        } else {
            $mensaje = "⚠️ Formato de imagen no permitido. Usa JPG, PNG o GIF.";
        }
    } else {
        $mensaje = "⚠️ Completa todos los campos y selecciona una imagen.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219150;
        }

        .mensaje {
            margin-top: 15px;
            font-weight: bold;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>

<body>
    <h1>➕ Agregar Nuevo Producto</h1>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" required>

        <label for="precio">Precio ($):</label>
        <input type="number" name="precio" step="0.01" required>

        <label for="imagen">Imagen del Producto:</label>
        <input type="file" name="imagen" accept="/assets/img/" required>

        <button type="submit">Guardar Producto</button>
    </form>

    <div class="mensaje"><?= $mensaje ?></div>
    <a href="admin_panel.php">🔙 Volver al Panel</a>
</body>

</html>