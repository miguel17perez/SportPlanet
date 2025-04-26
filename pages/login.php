<?php
session_start();

// Configuración de conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "sport_planet";

// Establecer conexión
$conn = new mysqli($servername, $db_username, $db_password, $database);
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar mensaje de error
$error_message = "";

// --------------- MANEJO DE INICIO DE SESIÓN ---------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  // Validar entrada vacía
  if (empty($username) || empty($password)) {
    $error_message = "Por favor, ingrese usuario y contraseña.";
  } else {
    $stmt = $conn->prepare("SELECT username, contraseña, rol FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
      $hashed_password = $user['contraseña'];
      if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol'] = $user['rol']; // <-- SE AGREGA EL ROL A LA SESIÓN
        header("Location: ../pages/inicio.php?login=true");
        exit;
      }
    } else {
      $error_message = "Usuario no encontrado";
    }
  }
}


// --------------- MANEJO DE REGISTRO DE USUARIO ---------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
  $name = trim($_POST['name'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  // Validar entrada vacía
  if (empty($name) || empty($username) || empty($email) || empty($password)) {
    $error_message = "Todos los campos son obligatorios.";
  } else {
    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO user (nombre, username, email, contraseña) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

    if ($stmt->execute()) {
      $_SESSION['username'] = $username;
      header("Location: ../pages/login.php?registered=true");
      exit;
    } else {
      $error_message = "Error al registrar el usuario.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión - Sports Planet</title>
  <link rel="stylesheet" href="../assets/css/secion.css">
  <style>
    .toggle-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }

    .toggle-btn {
      cursor: pointer;
      padding: 12px 24px;
      background: linear-gradient(135deg, #ff7e00, #ff5100);
      color: white;
      font-weight: bold;
      font-size: 16px;
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(255, 81, 0, 0.4);
    }

    .toggle-btn:hover {
      transform: translateY(-2px) scale(1.05);
      background: linear-gradient(135deg, #ffa84c, #ff6a1a);
      box-shadow: 0 6px 12px rgba(255, 106, 0, 0.6);
    }

    .toggle-btn.active {
      box-shadow: 0 6px 14px rgba(255, 106, 0, 0.8);
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Sports Planet</h1>

    <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true') : ?>
      <p class="success-message">Registro exitoso. Ahora puedes iniciar sesión.</p>
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
      <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <div id="form-container">
      <form id="login-form" class="form" method="POST">
        <input type="text" name="username" placeholder="Nombre de Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login">Ingresar</button>
      </form>

      <form id="register-form" class="form" method="POST" style="display: none;">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="text" name="username" placeholder="Nombre de Usuario" required>
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="register">Registrarse</button>
      </form>
    </div>
    <div class="toggle-buttons">
      <div class="toggle-btn active" id="login-toggle">Iniciar Sesión</div>
      <div class="toggle-btn" id="register-toggle">Registrarse</div>
    </div>
  </div>

  <script>
    document.getElementById("login-toggle").addEventListener("click", () => {
      document.getElementById("login-form").style.display = "flex";
      document.getElementById("register-form").style.display = "none";
    });

    document.getElementById("register-toggle").addEventListener("click", () => {
      document.getElementById("login-form").style.display = "none";
      document.getElementById("register-form").style.display = "flex";
    });
  </script>
  <script>
    // Ocultar mensajes después de 2 segundos
    setTimeout(() => {
      document.querySelectorAll('.success-message, .error-message').forEach(msg => {
        msg.style.display = 'none';
      });
    }, 2000);
  </script>

</body>

</html>