<?php
session_start();
require_once '../Model/UserModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnlogin"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);

        if ($user && $password === $user["contrasena"]) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol_nombre'] = $user['rol_usuario_id'];

        switch ($user['rol_usuario_id']) {
            case 1:
                header("Location: ../View/admin.html");
                break;
            case 2:
                header("Location: ../View/docente.html");
                break;
            case 3:
                header("Location: ../View/estudiante.html");
                break;
            default:
                echo "Rol no reconocido.";
        }
        exit();
    } else {
        header("Location: ../View/login.php?error=1");
    }
} else {
    echo "Acceso no permitido.";
}
?>
