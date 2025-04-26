<?php
session_start();

// Verifica si existen datos de pago
if (!isset($_SESSION['datos_pago'])) {
    echo "No hay datos disponibles para mostrar el recibo.";
    exit();
}

// Extrae los datos de la sesión
$datos = $_SESSION['datos_pago'];

$nombre_comprador = $datos['nombre'];
$ciudad = $datos['ciudad'];
$direccion_envio = $datos['direccion'];
$fecha_pago = $datos['fecha_pago'];
$fecha_envio = $datos['fecha_envio'];
$productos = $datos['productos'];
$total = $datos['total'];

// Crear código QR dinámico con la información del recibo
$qr_data = "Comprador: $nombre_comprador\nCiudad: $ciudad\nTotal: $total USD\nPago: $fecha_pago\nEnvío: $fecha_envio";
$qr_url = "https://quickchart.io/qr?text=$qr_data&size=200";



// Opcional: elimina los datos para que no se repitan si se recarga la página
unset($_SESSION['datos_pago']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Compra</title>
    <link rel="stylesheet" href="../assets/css/recibo.css">

</head>

<body>

    <div class="recibo-container">
        <h2>Recibo de Compra</h2>

        <p><strong>Comprador:</strong> <?php echo htmlspecialchars($nombre_comprador); ?></p>
        <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($ciudad); ?></p>
        <p><strong>Dirección de Envío:</strong> <?php echo htmlspecialchars($direccion_envio); ?></p>
        <p><strong>Fecha y Hora de Pago:</strong> <?php echo $fecha_pago; ?></p>
        <p><strong>Fecha Estimada de Envío:</strong> <?php echo $fecha_envio; ?></p>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto["nombre"]); ?></td>
                        <td>$<?php echo number_format(floatval($producto["precio"]), 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total a Pagar: $<?php echo number_format($total, 2); ?></h3>

        <h4>Código QR del Recibo</h4>
        <img src="<?php echo $qr_url; ?>" alt="Código QR de la compra">

        <button onclick="imprimirRecibo()">Imprimir Recibo</button>
    </div>

    <script>
        function imprimirRecibo() {
            window.print();
        }
    </script>

</body>

</html>