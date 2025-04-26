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

$usuarios = $conn->query("SELECT id, nombre, email FROM user");
$productos = $conn->query("SELECT id, nombre, precio FROM productos");
$pagos = $conn->query("SELECT id, nombre, total, fecha FROM pagos");

$totalVentas = 0;
$pagosLista = [];
if ($pagos && $pagos->num_rows > 0) {
    while ($pago = $pagos->fetch_assoc()) {
        $totalVentas += $pago['total'];
        $pagosLista[] = $pago;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .seccion {
            margin-bottom: 50px;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-delete:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <h1>⚙️ Panel de Administración</h1>
    <a href="perfil.php">🔙 Volver al Perfil</a>

    <div class="seccion">
        <h2>👥 Usuarios Registrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $u['id'] ?>">✏️ Editar</a> |
                        <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="seccion">
        <h2>📦 Productos</h2>
        <a href="agregar_productos.php">➕ Agregar Producto</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php while ($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $p['id'] ?>">✏️ Editar</a> |
                        <a href="eliminar_producto.php?id=<?= $p['id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="seccion">
        <h2>🧾 Ventas Realizadas</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Comprador</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>
            <?php foreach ($pagosLista as $venta): ?>
                <tr>
                    <td><?= $venta['id'] ?></td>
                    <td><?= htmlspecialchars($venta['nombre']) ?></td>
                    <td>$<?= number_format($venta['total'], 2) ?></td>
                    <td><?= $venta['fecha'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Total de Ventas:</strong> $<?= number_format($totalVentas, 2) ?></p>
    </div>
</body>

</html>