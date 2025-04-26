<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

// Verificar si viene ID
if (!isset($_GET['id'])) {
    header("Location: admin_panel.php");
    exit;
}

$id = intval($_GET['id']);

// Buscar usuario
$resultado = $conn->query("SELECT nombre, email FROM user WHERE id = $id");

if ($resultado->num_rows === 0) {
    $mensaje = "⚠️ Usuario no encontrado.";
} else {
    $usuario = $resultado->fetch_assoc();

    // Si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevoNombre = trim($_POST['nombre']);
        $nuevoEmail = trim($_POST['email']);

        if ($nuevoNombre !== '' && $nuevoEmail !== '') {
            $stmt = $conn->prepare("UPDATE user SET nombre = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nuevoNombre, $nuevoEmail, $id);

            if ($stmt->execute()) {
                $mensaje = "✅ Usuario actualizado correctamente.";
                $usuario['nombre'] = $nuevoNombre;
                $usuario['email'] = $nuevoEmail;
            } else {
                $mensaje = "❌ Error al actualizar el usuario.";
            }

            $stmt->close();
        } else {
            $mensaje = "⚠️ Por favor completa todos los campos.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            width: 300px;
            border-radius: 10px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #2980b9;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1f6391;
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
    <h2>✏️ Editar Usuario</h2>

    <?php if (isset($usuario)): ?>
        <form method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php endif; ?>

    <div class="mensaje"><?= $mensaje ?></div>
    <a href="admin_panel.php">🔙 Volver al Panel</a>
</body>

</html>