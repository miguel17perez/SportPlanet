<?php
session_start();
require 'db.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$mensaje = "";
$producto = null;

// Conexión
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Obtener ID del producto
if (!isset($_GET['id'])) {
    echo "⚠️ ID no proporcionado.";
    exit;
}

$id = intval($_GET['id']);

// Cargar datos actuales
$resultado = $conn->query("SELECT * FROM productos WHERE id = $id");
if ($resultado->num_rows === 0) {
    echo "❌ Producto no encontrado.";
    exit;
}
$producto = $resultado->fetch_assoc();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = trim($_POST['nombre']);
    $nuevoPrecio = floatval($_POST['precio']);

    if ($nuevoNombre !== '' && $nuevoPrecio > 0) {
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE id = ?");
        $stmt->bind_param("sdi", $nuevoNombre, $nuevoPrecio, $id);

        if ($stmt->execute()) {
            $mensaje = "✅ Producto actualizado con éxito.";
            // Actualizar valores en pantalla sin recargar
            $producto['nombre'] = $nuevoNombre;
            $producto['precio'] = $nuevoPrecio;
        } else {
            $mensaje = "❌ Error al actualizar.";
        }

        $stmt->close();
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios y deben ser válidos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
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
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #2980b9;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #216a9c;
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
    <h1>✏️ Editar Producto</h1>

    <form method="post" action="">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label for="precio">Precio ($):</label>
        <input type="number" name="precio" step="0.01" value="<?= $producto['precio'] ?>" required>

        <button type="submit">Actualizar</button>
    </form>

    <div class="mensaje"><?= $mensaje ?></div>
    <a href="admin_panel.php">🔙 Volver al Panel</a>
</body>

</html>