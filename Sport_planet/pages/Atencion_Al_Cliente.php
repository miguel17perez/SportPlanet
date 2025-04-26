<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Atencion Al Cliente</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #333;
            padding: 40px;
            text-align: center;
            color: #fff;
        }

        h1 {
            color: #d35400;
            margin-bottom: 25px;
        }

        .contacto-container {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 40px 30px;
            display: inline-block;
            box-shadow: 0 0 20px #d35400(0, 255, 213, 0.3);
            backdrop-filter: blur(8px);
        }

        .contacto-link {
            display: block;
            margin: 20px auto;
            font-size: 18px;
            text-decoration: none;
            color: #d35400;
            background-color: rgba(255, 255, 255, 0.1);
            width: 80%;
            max-width: 400px;
            padding: 12px 20px;
            border-radius: 10px;
            transition: 0.3s ease-in-out;
        }

        .contacto-link:hover {
            background-color: #d35400;
            color: #111;
            transform: scale(1.05);
            box-shadow: 0 0 10px #d35400;
        }

        .icono {
            margin-right: 8px;
        }

        @media (max-width: 480px) {
            .contacto-link {
                font-size: 16px;
                padding: 10px 15px;
            }

            .contacto-container {
                width: 90%;
            }
        }
    </style>

</head>

<body>

    <div class="contacto-container">
        <h1>Atencion Al Cliente</h1>

        <a class="contacto-link" href="https://wa.me/573207523025" target="_blank">
            📱 WhatsApp: +57 320 7523025
        </a>

        <a class="contacto-link" href="mailto:luismi221017@gmail.com">
            📧 Correo: luismi221017@gmail.com
        </a>

        <a class="contacto-link" href="https://instagram.com/miguel_preciado.17" target="_blank">
            📸 Instagram: @miguel_preciado.17
        </a>

        <a class="contacto-link" href="https://tiktok.com/@miguel_preciado.17" target="_blank">
            🎵 TikTok: @miguel_preciado.17
        </a>
    </div>
    <footer>
        <p>&copy; 2025 Sports Planet | Todos los derechos reservados</p>
    </footer>
</body>

</html>