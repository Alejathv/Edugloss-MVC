<?php
session_start();
$mensaje = '';
$tipo = ''; // "exito" o "error"

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo = 'success';
    unset($_SESSION['mensaje']);
} elseif (isset($_SESSION['error'])) {
    $mensaje = $_SESSION['error'];
    $tipo = 'danger';
    unset($_SESSION['error']);
}

if (!empty($mensaje)) {
    echo "<script>
        window.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('mensaje-container');
            if (container) {
                container.innerHTML = `<div class='alert alert-{$tipo} alert-dismissible fade show'>
                    {$mensaje}
                    
                </div>`;
                setTimeout(() => {
                    container.innerHTML = '';
                }, 5000);
            }
        });
    </script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400&display=swap" rel="stylesheet">


  <!-- CSS Personalizado -->
  <link rel="stylesheet" href="css/recupe.css">
  <title>Recuperar Contraseña</title>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

  <main class="w-100" style="max-width: 400px;">
    <div class="card shadow p-4">
      <div class="card-body">
        <h1 class="text-center mb-3">Recuperar Contraseña</h1>
        <p class="text-center text-muted">Ingresa tu correo y te enviaremos una nueva clave.</p>
        <div id="mensaje-container"></div> 
        <form action="../Controller/contraseña.php" method="POST">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="correo" required>
            <label for="floatingInput">Correo electrónico</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Recuperar contraseña</button>

          <a href="login.php">Inicio Sesión</a>
        </form>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
