<?php
session_start();
require 'db.php'; // Conexión a la base de datos

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Manejo de acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
  $accion = $_POST['accion'];
  $nombre = $_POST['nombre'];
  $precio = floatval($_POST['precio']);

  if ($accion === 'agregar') {
    $_SESSION['carrito'][] = ['nombre' => $nombre, 'precio' => $precio];
  } elseif ($accion === 'quitar') {
    foreach ($_SESSION['carrito'] as $index => $producto) {
      if ($producto['nombre'] === $nombre) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
        break;
      }
    }
  } elseif ($accion === 'vaciar') {
    $_SESSION['carrito'] = [];
  }

  header("Location: productos.php"); // Redirigir para evitar reenvío del formulario
  exit;
}

// Buscar productos según el término de búsqueda
$query = isset($_GET['query']) ? $_GET['query'] : ''; // Capturar la consulta de búsqueda

if ($query) {
  $query = $conn->real_escape_string($query); // Escapar caracteres especiales para evitar inyecciones SQL
  $result = $conn->query("SELECT * FROM productos WHERE nombre LIKE '%$query%'");
} else {
  $result = $conn->query("SELECT * FROM productos"); // Obtener todos los productos si no hay búsqueda
}

$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Productos - Sports Planet</title>
  <link rel="stylesheet" href="../assets/css/productos.css">
</head>

<body>

  <!-- Encabezado -->
  <header>
    <h1>Productos</h1>
    <nav>
      <button id="carrito-btn" onclick="toggleCarrito()">🛒</button>
    </nav>
  </header>

  <!-- Carrito -->
  <div id="carrito-contenedor" style="display: none;">
    <button onclick="cerrarCarrito()">✖</button>
    <h2>Carrito de Compras</h2>
    <ul>
      <?php $total = 0;
      foreach ($_SESSION['carrito'] as $producto) : ?>
        <li><?= htmlspecialchars($producto['nombre']) ?> - $<?= number_format($producto['precio'], 2) ?></li>
        <?php $total += $producto['precio']; ?>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<span id="total"><?= number_format($total, 2) ?></span></strong></p>
    <form action="./pago.php" method="post">
      <button type="submit">Finalizar Compra</button>
    </form>
    <form action="productos.php" method="post" style="display: inline;">
      <input type="hidden" name="accion" value="vaciar">
      <button type="submit">Vaciar Carrito</button>
    </form>
  </div>


  <!-- Botón de regreso -->
  <a href="inicio.php" class="boton-volver">← Volver al Inicio</a>

  <!-- Sección de productos -->
  <main>
    <div class="productos">
      <?php if (!empty($productos)) : ?>
        <?php foreach ($productos as $producto) : ?>
          <div class="producto">
            <img src="../assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
            <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
            <form method="post">
              <input type="hidden" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">
              <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
              <button type="submit" name="accion" value="agregar">Agregar al Carrito</button>
              <button type="submit" name="accion" value="quitar">Quitar del Carrito</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>No se encontraron productos que coincidan con la búsqueda.</p>
      <?php endif; ?>
    </div>
  </main>

  <!-- Pie de página -->
  <footer>
    <p>&copy; 2025 Sports Planet | Todos los derechos reservados</p>
  </footer>

  <!-- Scripts -->
  <script>
    function toggleCarrito() {
      const carrito = document.getElementById("carrito-contenedor");
      carrito.style.display = carrito.style.display === "none" || carrito.style.display === "" ? "block" : "none";
    }

    function cerrarCarrito() {
      document.getElementById("carrito-contenedor").style.display = "none";
    }
  </script>

</body>

</html>