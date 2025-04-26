<?php
session_start();
require 'db.php'; // Conexión a la base de datos

// Verificar si se ha enviado una consulta de búsqueda
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Construir la consulta SQL dependiendo de si hay un término de búsqueda
if ($query) {
  $query = $conn->real_escape_string($query); // Escapar caracteres especiales para evitar inyecciones SQL
  $result = $conn->query("SELECT * FROM productos WHERE nombre LIKE '%$query%' ORDER BY RAND() LIMIT 4");
} else {
  // Si no hay búsqueda, mostrar productos aleatorios
  $result = $conn->query("SELECT * FROM productos ORDER BY RAND() LIMIT 4");
}

$productos_recomendados = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sports Planet - Inicio</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>

  <!-- 🔹 Encabezado -->
  <header class="navbar">
    <div class="navbar-container">
      <h1>Sports Planet</h1>
      <nav>
        <a class="nav-link" href="inicio.php">Inicio</a>
        <a class="nav-link" href="productos.php">Productos</a>
        <a class="nav-link" href="promociones.php">Promociones</a>
        <a class="nav-link" href="Atencion_Al_Cliente.php">Soporte</a>
        <a class="nav-link" href="perfil.php">👤</a>
      </nav>
    </div>
  </header>

  <!-- 🔹 Portada -->
  <section class="portada">
    <img src="../assets/img/fondo.jpg" alt="Bienvenido a Sports Planet">
    <div class="portada-texto">
      <h2>Encuentra el mejor equipo deportivo aquí</h2>
      <p>Explora nuestra selección de ropa, calzado y accesorios deportivos.</p>
      <a href="productos.php" class="boton">Ver Productos</a>
    </div>
  </section>

  <!-- 🔹 Barra de búsqueda -->
  <section class="buscador">
    <form action="productos.php" method="GET">
      <input class="search-bar" type="text" name="query" placeholder="Buscar productos..." value="<?= htmlspecialchars($query) ?>" required>
      <button type="submit">Buscar</button>
    </form>
  </section>

  <!-- 🔹 Productos Recomendados -->
  <main>
    <h2>🔥 Productos Recomendados</h2>
    <div class="productos">
      <?php if (!empty($productos_recomendados)) : ?>
        <?php foreach ($productos_recomendados as $producto) : ?>
          <div class="producto">
            <img src="../assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
            <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
            <a href="productos.php" class="boton">Ver Más</a>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>No hay productos recomendados en este momento.</p>
      <?php endif; ?>
    </div>
  </main>

  <!-- 🔹 Pie de página -->
  <footer>
    <p>&copy; 2025 Sports Planet | Todos los derechos reservados</p>
  </footer>

</body>

</html>