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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión / Registrarse</title>
    <!-- Enlace a FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div id="mensaje-container"></div>  
    <div class="container" id="container">
        
        <div class="form-container sign-in-container">
            <form action="../Controller/LoginController.php" method="POST">
                <a href="../index.php" class="navbar-brand p-0">
                        <h1 class="text-primary mb-0"><img src="img/logo.png" alt="Logo"></h1>
                </a>
                
                <span>Ingresa con tu cuenta</span>

                <input type="email" name="email" placeholder="Correo electrónico" />
                <div class="custom-password-group">
                    <input type="password" name="password" placeholder="Contraseña" id="password" class="custom-input">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div> 
                <a href="recovery.php">¿Olvidaste tu contraseña?</a>
                
                <button type="submit" name="btnlogin">Iniciar sesión</button>  
            </form>            
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1>¡Empecemos!</h1>
                    <p>Para mantenerte conectado con nosotros, inicia sesión con tu información personal</p>
                    <h5>¿No tienes una cuenta?</h5>
                    <br>
                    <p>Recuerda que primero necesitas adquirir un plan.</p>
                    <h5>¡Te invitamos a conocer nuestras opciones!</h5>
                    <a href="planes.php" class="ghost">Ver Planes</a>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="View/js/mensajes.js"></script>
    <script src="../View/js/vercontraseña.js"></script>
    

</body>
</html>
