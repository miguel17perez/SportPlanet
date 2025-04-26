<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = [
        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'precio' => $_POST['precio']
    ];

    $_SESSION['carrito'][] = $producto;
}
header("Location: productos.php");
