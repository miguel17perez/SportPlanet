<?php
session_start();

// Configuración de la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "sport_planet";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Calcular total del carrito
$total = 0;
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
  foreach ($_SESSION['carrito'] as $producto) {
    $total += floatval($producto['precio']);
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, trim($_POST['nombre'])) : '';
  $email = isset($_POST['email']) ? mysqli_real_escape_string($conexion, trim($_POST['email'])) : '';
  $direccion = isset($_POST['direccion']) ? mysqli_real_escape_string($conexion, trim($_POST['direccion'])) : '';
  $ciudad = isset($_POST['ciudad']) ? mysqli_real_escape_string($conexion, trim($_POST['ciudad'])) : '';
  $codigoPostal = isset($_POST['codigoPostal']) ? mysqli_real_escape_string($conexion, trim($_POST['codigoPostal'])) : '';
  $tarjeta = isset($_POST['tarjeta']) ? mysqli_real_escape_string($conexion, trim($_POST['tarjeta'])) : '';
  $fechaExp = isset($_POST['fechaExp']) ? mysqli_real_escape_string($conexion, trim($_POST['fechaExp'])) : '';
  $cvv = isset($_POST['cvv']) ? mysqli_real_escape_string($conexion, trim($_POST['cvv'])) : '';

  if (
    empty($nombre) || empty($email) || empty($direccion) || empty($ciudad) ||
    empty($codigoPostal) || empty($tarjeta) || empty($fechaExp) || empty($cvv)
  ) {
    // Puedes mostrar un mensaje de error aquí si lo deseas
  } else {
    $sql = "INSERT INTO pagos (nombre, email, direccion, ciudad, codigo_postal, tarjeta, fecha_exp, cvv, total)
            VALUES ('$nombre', '$email', '$direccion', '$ciudad', '$codigoPostal', '$tarjeta', '$fechaExp', '$cvv', '$total')";

    if ($conexion->query($sql) === TRUE) {

      // ✅ Guardar datos del recibo en sesión
      $_SESSION['datos_pago'] = [
        'nombre' => $nombre,
        'ciudad' => $ciudad,
        'direccion' => $direccion,
        'fecha_pago' => date("d/m/Y H:i:s"),
        'fecha_envio' => date("d/m/Y", strtotime("+2 days")),
        'productos' => $_SESSION['carrito'],
        'total' => $total
      ];

      $_SESSION['carrito'] = []; // Vaciar carrito
      header("Location: ./recibo.php?total=$total");
      exit();
    } else {
      echo "Error: " . $conexion->error;
    }
  }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Página de Pago</title>
  <link rel="stylesheet" href="../assets/css/pagos.css">
</head>

<body>
  <div class="container">
    <h1>Finalizar Pago</h1>

    <!-- Mostrar productos del carrito -->
    <?php if (!empty($_SESSION['carrito'])) : ?>
      <h2>Productos en tu Carrito</h2>
      <table border="1" cellpadding="10" cellspacing="0">
        <tr>
          <th>Producto</th>
          <th>Precio</th>
        </tr>
        <?php foreach ($_SESSION['carrito'] as $producto) : ?>
          <tr>
            <td><?= htmlspecialchars($producto['nombre']) ?></td>
            <td>$<?= number_format($producto['precio'], 2) ?> USD</td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td><strong>Total:</strong></td>
          <td><strong>$<?= number_format($total, 2) ?> USD</strong></td>
        </tr>
      </table>
    <?php else : ?>
      <p>No hay productos en el carrito.</p>
    <?php endif; ?>

    <!-- Formulario de pago -->


    <form method="POST">
      <div class="form-group">
        <label>Nombre Completo:</label>
        <input type="text" name="nombre" required>
      </div>
      <div class="form-group">
        <label>Correo Electrónico:</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Dirección:</label>
        <input type="text" name="direccion" required>
      </div>
      <div class="form-group">
        <label>Ciudad:</label>
        <input type="text" name="ciudad" required>
      </div>
      <div class="form-group">
        <label>Código Postal:</label>
        <input type="text" name="codigoPostal" required>
      </div>
      <div class="form-group">
        <label>Número de Tarjeta:</label>
        <input type="text" name="tarjeta" pattern="\d{13,18}" title="Debe contener entre 13 y 18 números" required>
      </div>
      <div class="form-group">
        <label>Fecha de Expiración:</label>
        <input type="text" name="fechaExp" placeholder="MM/AA" pattern="(0[1-9]|1[0-2])\/\d{2}" title="Formato válido: MM/AA" required>
      </div>
      <div class="form-group">
        <label>CVV:</label>
        <input type="text" name="cvv" pattern="\d{3,4}" title="Debe contener 3 o 4 números" required>
      </div>

      <button type="submit" class="btn">Confirmar Pago</button>
    </form>
  </div>
</body>

</html>