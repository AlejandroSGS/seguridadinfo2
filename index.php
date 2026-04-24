<?php
$showError = false;
$errorMsg  = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $conexion = new mysqli("localhost", "root", "", "clase");
    if ($conexion->connect_error) {
        $showError = true;
        $errorMsg  = "Error de conexión con el servidor.";
    } else {
        $stmt = $conexion->prepare("SELECT contrasenia FROM datost WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row || !password_verify($password, $row["contrasenia"])) {
            $showError = true;
            $errorMsg  = "No encontramos tu cuenta de Google.";
        }
        $stmt->close();
        $conexion->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión – Cuentas de Google</title>
  <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Roboto', 'Google Sans', Arial, sans-serif;
      background: #f1f3f4;
      color: #202124;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
      padding: 48px 40px 36px;
      width: 100%;
      max-width: 450px;
    }

    .google-logo {
      display: block;
      margin: 0 auto 28px;
      width: 75px;
    }

    .card-title {
      font-family: 'Google Sans', sans-serif;
      font-size: 24px;
      font-weight: 400;
      color: #202124;
      text-align: center;
      margin-bottom: 8px;
    }

    .card-subtitle {
      font-size: 16px;
      color: #202124;
      text-align: center;
      margin-bottom: 32px;
    }

    .field {
      margin-bottom: 24px;
      position: relative;
    }

    .field input {
      width: 100%;
      border: 1px solid #dadce0;
      border-radius: 4px;
      padding: 16px 14px 6px;
      font-size: 16px;
      color: #202124;
      outline: none;
      background: transparent;
      transition: border-color 0.2s;
    }

    .field input:focus {
      border-color: #1a73e8;
      border-width: 2px;
    }

    .field label {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #5f6368;
      pointer-events: none;
      transition: all 0.15s ease;
      background: #fff;
      padding: 0 2px;
    }

    .field input:focus + label,
    .field input:not(:placeholder-shown) + label {
      top: 0;
      font-size: 11px;
      color: #1a73e8;
    }

    .field input:not(:focus):not(:placeholder-shown) + label {
      color: #5f6368;
    }

    .forgot-link {
      display: block;
      margin-top: -12px;
      margin-bottom: 24px;
      font-size: 14px;
      color: #1a73e8;
      text-decoration: none;
      font-weight: 500;
    }
    .forgot-link:hover { text-decoration: underline; }

    .error-msg {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fce8e6;
      border-radius: 4px;
      padding: 12px 14px;
      margin-bottom: 20px;
      color: #c5221f;
      font-size: 14px;
    }

    .error-msg svg { flex-shrink: 0; }

    .card-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 8px;
    }

    .btn-create {
      font-family: 'Google Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      color: #1a73e8;
      background: none;
      border: none;
      cursor: pointer;
      padding: 10px 8px;
      border-radius: 4px;
      text-decoration: none;
    }
    .btn-create:hover { background: #e8f0fe; }

    .btn-next {
      font-family: 'Google Sans', sans-serif;
      background: #1a73e8;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px 24px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s, box-shadow 0.2s;
    }
    .btn-next:hover {
      background: #1765cc;
      box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .divider {
      border: none;
      border-top: 1px solid #e0e0e0;
      margin: 28px 0 20px;
    }

    .footer {
      margin-top: 16px;
      text-align: center;
      font-size: 12px;
      color: #5f6368;
    }

    .footer a {
      color: #5f6368;
      text-decoration: none;
      margin: 0 8px;
    }
    .footer a:hover { text-decoration: underline; }

    .lang-selector {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-bottom: 10px;
      font-size: 13px;
      color: #5f6368;
    }

    .lang-selector svg { color: #5f6368; }
  </style>
</head>
<body>

<div class="card">
  <!-- Google SVG logo -->
  <svg class="google-logo" viewBox="0 0 272 92" xmlns="http://www.w3.org/2000/svg">
    <path d="M115.75 47.18c0 12.77-9.99 22.18-22.25 22.18s-22.25-9.41-22.25-22.18C71.25 34.32 81.24 25 93.5 25s22.25 9.32 22.25 22.18zm-9.74 0c0-7.98-5.79-13.44-12.51-13.44S80.99 39.2 80.99 47.18c0 7.9 5.79 13.44 12.51 13.44s12.51-5.55 12.51-13.44z" fill="#EA4335"/>
    <path d="M163.75 47.18c0 12.77-9.99 22.18-22.25 22.18s-22.25-9.41-22.25-22.18c0-12.85 9.99-22.18 22.25-22.18s22.25 9.32 22.25 22.18zm-9.74 0c0-7.98-5.79-13.44-12.51-13.44s-12.51 5.46-12.51 13.44c0 7.9 5.79 13.44 12.51 13.44s12.51-5.55 12.51-13.44z" fill="#FBBC05"/>
    <path d="M209.75 26.34v39.82c0 16.38-9.66 23.07-21.08 23.07-10.75 0-17.22-7.19-19.67-13.07l8.48-3.53c1.51 3.61 5.21 7.87 11.17 7.87 7.31 0 11.84-4.51 11.84-13v-3.19h-.34c-2.18 2.69-6.38 5.04-11.68 5.04-11.09 0-21.25-9.66-21.25-22.09 0-12.52 10.16-22.26 21.25-22.26 5.29 0 9.49 2.35 11.68 4.96h.34v-3.61h9.26zm-8.56 20.92c0-7.81-5.21-13.52-11.84-13.52-6.72 0-12.35 5.71-12.35 13.52 0 7.73 5.63 13.36 12.35 13.36 6.63 0 11.84-5.63 11.84-13.36z" fill="#4285F4"/>
    <path d="M225 3v65h-9.5V3h9.5z" fill="#34A853"/>
    <path d="M262.02 54.48l7.56 5.04c-2.44 3.61-8.32 9.83-18.48 9.83-12.6 0-22.01-9.74-22.01-22.18 0-13.19 9.49-22.18 20.92-22.18 11.5 0 17.14 9.16 18.98 14.11l1.01 2.52-29.65 12.28c2.27 4.45 5.8 6.72 10.75 6.72 4.96 0 8.4-2.44 10.92-6.14zm-23.27-7.98l19.82-8.23c-1.09-2.77-4.37-4.7-8.23-4.7-4.95 0-11.84 4.37-11.59 12.93z" fill="#EA4335"/>
    <path d="M35.29 41.41V32H67c.31 1.64.47 3.58.47 5.68 0 7.06-1.93 15.79-8.15 22.01-6.05 6.3-13.78 9.66-24.02 9.66C16.32 69.35.36 53.89.36 34.91.36 15.93 16.32.47 35.3.47c10.5 0 17.98 4.12 23.6 9.49l-6.64 6.64c-4.03-3.78-9.49-6.72-16.97-6.72-13.86 0-24.7 11.17-24.7 25.03 0 13.86 10.84 25.03 24.7 25.03 8.99 0 14.11-3.61 17.39-6.89 2.66-2.66 4.41-6.46 5.1-11.65l-22.49.01z" fill="#4285F4"/>
  </svg>

  <h1 class="card-title">Iniciar sesión</h1>
  <p class="card-subtitle">Usa tu Cuenta de Google</p>

  <?php if ($showError): ?>
  <div class="error-msg">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="#c5221f"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    <?= htmlspecialchars($errorMsg) ?>
  </div>
  <?php endif; ?>

  <form method="post" action="index.php">
    <div class="field">
      <input type="email" name="email" id="email" placeholder=" " required
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      <label for="email">Correo electrónico o teléfono</label>
    </div>
    <div class="field">
      <input type="password" name="password" id="password" placeholder=" " required />
      <label for="password">Contraseña</label>
    </div>

    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>

    <div class="card-actions">
      <a href="Registro.php" class="btn-create">Crear cuenta</a>
      <button type="submit" class="btn-next">Siguiente</button>
    </div>
  </form>
</div>

<div class="footer">
  <div class="lang-selector">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2s.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2s.07-1.35.16-2h4.68c.09.65.16 1.32.16 2s-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2s-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z"/></svg>
    Español (Latinoamérica)
  </div>
  <a href="#">Ayuda</a>
  <a href="#">Privacidad</a>
  <a href="#">Condiciones</a>
</div>

</body>
</html>
