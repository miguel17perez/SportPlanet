<?php
require 'db.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $conn->query("DELETE FROM productos WHERE id = $id");
}

header("Location: admin_panel.php");
exit;
