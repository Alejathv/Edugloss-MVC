<?php
session_start();
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnlogin"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Por favor, complete los campos requeridos.";
        header("Location: ../View/login.php");
        exit();
    }

    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);

    if (!$user) {
        // Usuario no existe
        $_SESSION['error'] = "Credenciales incorrectas. Intenta de nuevo.";
        header("Location: ../View/login.php");
        exit();
    }

    // Verificar contraseña (idealmente con password_verify si usas hash)
    if ($password !== $user["contraseña"]) {
        $_SESSION['error'] = "Credenciales incorrectas. Intenta de nuevo.";
        header("Location: ../View/login.php");
        exit();
    }

    // Inicio sesión exitoso
    $_SESSION['user_id'] = $user['id_usuario'];
    $_SESSION['rol_nombre'] = $user['rol'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['apellido'] = $user['apellido'];

    if ($user['rol'] === 'estudiante') {
        $inscripciones = $userModel->getInscripcionesByUserId($user['id_usuario']);
        $_SESSION['inscripciones'] = $inscripciones;
    }

    // Mensaje de bienvenida para mostrar en la vista del usuario
    $_SESSION['mensaje_bienvenida'] = "Bienvenido, " . $user['nombre'] . ", tu sesión ha iniciado con éxito.";

    switch ($user['rol']) {
        case 'administrador':
            header("Location: ../View/madmin/admin_panel.php");
            break;
        case 'docente':
            header("Location: ../View/mdocente/docente_panel.php");
            break;
        case 'estudiante':
            header("Location: ../View/mestudiante/home.php");
            break;
        default:
            $_SESSION['error'] = "¡Rol no reconocido.";
            header("Location: ../View/login.php");
            break;
    }
    exit();
} else {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: ../View/login.php");
    exit();
}
?>