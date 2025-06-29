<?php
session_start();
// Destruye todas las variables de sesión
$_SESSION = array();

// Si se usa cookie de sesión, eliminarla también
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login o página principal
header("Location: view/login.php");

exit;
?>
