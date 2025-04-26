<?php
session_start();
require 'db.php'; // Conexión a la base de datos

// Conectar a MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Subir la imagen
    $imagen = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $ruta_destino = "../assets/img/" . $imagen; // Guarda en la carpeta de imágenes

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO producto (nombre, precio, imagen) VALUES ('$nombre', '$precio', '$imagen')";

        if ($conn->query($sql) === TRUE) {
            echo "Producto agregado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error al subir la imagen.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
</head>

<body>
    <h2>Agregar Producto</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required><br><br>

        <label>Imagen:</label>
        <input type="file" name="imagen" required><br><br>

        <button type="submit">Agregar Producto</button>
    </form>
</body>

</html>