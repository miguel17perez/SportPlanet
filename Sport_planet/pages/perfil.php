<?php
session_start();
require 'db.php';

if (!isset($host, $user, $password, $dbname)) {
    die("❌ Error: Credenciales de base de datos no definidas.");
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT nombre, email, foto FROM user WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    die("❌ Error: No se encontraron datos para el usuario.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario | Sports Planet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1e1e, #2c3e50);
            color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        .perfil-container {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            padding: 50px 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #ff7e00;
        }

        .info p {
            font-size: 18px;
            margin: 18px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        .info p span {
            font-weight: 600;
            color: #ffa94d;
            display: inline-block;
            width: 100px;
        }

        .acciones {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .acciones a {
            flex: 1 1 45%;
            padding: 12px 0;
            text-align: center;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            transition: 0.3s ease;
        }

        .inicio-btn {
            background: #27ae60;
        }

        .inicio-btn:hover {
            background: #2ecc71;
            box-shadow: 0 0 10px #2ecc71;
        }

        .cerrar {
            background: #c0392b;
        }

        .cerrar:hover {
            background: #e74c3c;
            box-shadow: 0 0 10px #e74c3c;
        }

        @media screen and (max-width: 480px) {
            .perfil-container {
                padding: 30px 25px;
            }

            h2 {
                font-size: 26px;
            }

            .info p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="perfil-container">
        <h2>👤 Mi Perfil</h2>
        <img src="uploads/perfiles/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" style="display: block; margin: 0 auto; border-radius: 50%; width: 120px; height: 120px; object-fit: cover; border: 3px solid #ffa94d;">


        <div class="info">
            <p><span>Nombre:</span> <?= htmlspecialchars($usuario['nombre']) ?></p>
            <p><span>Email:</span> <?= htmlspecialchars($usuario['email']) ?></p>
            <p><span>Usuario:</span> <?= htmlspecialchars($username) ?></p>
        </div>
        <form action="subir_foto.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px; text-align: center;">
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
                <label for="foto" style="padding: 10px 20px; background: #ffa94d; border: none; color: #000; font-weight: bold; border-radius: 8px; cursor: pointer;">
                    📁 Seleccionar Archivo
                </label>
                <input type="file" name="foto" id="foto" accept="image/*" required style="display: none;">

                <button type="submit"
                    style="padding: 10px 20px; background: #ffa94d; border: none; color: #000; font-weight: bold; border-radius: 8px; cursor: pointer;">
                    📸 Actualizar Foto
                </button>
            </div>
        </form>




        <div class="acciones">
            <a href="inicio.php" class="inicio-btn">🏠 Menú Principal</a>

            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a href="admin_panel.php" class="inicio-btn">⚙️ Panel Admin</a>
            <?php endif; ?>

            <a href="logout.php" class="cerrar">🔓 Cerrar Sesión</a>
        </div>

    </div>
</body>

</html>