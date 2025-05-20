<?php
session_start();
require_once __DIR__ .  '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnlogin"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);

        if ($user && $password === $user["contraseña"]) {
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['rol_nombre'] = $user['rol'];

        switch ($user['rol']) {
            case 'administrador':
                header("Location: ../View/madmin/admin.html");
                break;
            case 'docente':
                header("Location: ../View/mdocente/docente_panel.php");
                break;
            case 'estudiante':
                header("Location: ../View/mestudiante/estudiante.html");
                break;
            default:
                $_SESSION['mensaje'] = "¡Rol no reconocido.";
                header("Location: ../login.php?mensaje=");
        }
        exit();
    } else {
        $_SESSION['error'] = "Hubo un error al registrar. Por favor, intenta nuevamente.";
        header("Location: ../View/login.php?error=1");
    }
} else {
    $_SESSION['mensaje'] = "Acceso no permitido.";
    header("Location: ../login.php?mensaje=");
}
?>
