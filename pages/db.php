<?php
$host = "localhost"; // Cambia esto si tu base de datos está en otro servidor
$user = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos (déjala vacía si no tiene)
$dbname = "sport_planet"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar codificación UTF-8 para evitar problemas con caracteres especiales
$conn->set_charset("utf8");
