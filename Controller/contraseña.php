<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../Model/Database.php';
require_once '../Model/UserModel.php';
require_once __DIR__ . '/../libs/vendor/autoload.php'; // Carga automática de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../libs/vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

if (!isset($_POST['correo']) || empty($_POST['correo'])) {
    $_SESSION['error'] = "Error: No se proporcionó un correo válido.";
    header("Location: ../View/recovery.php?error=");
    exit();
}

$correo = trim($_POST['correo']);

// Crear instancia de UserModel
$userModel = new UserModel();
$user = $userModel->getUserByEmail($correo);

if (!$user) {
    $_SESSION['error'] = "No se encontró ninguna cuenta con ese correo.";
    header("Location: ../View/recovery.php?error=");
    exit();
}

// Validar rol permitido
$rolesPermitidos = ['administrador', 'estudiante', 'docente'];
if (!in_array(strtolower($user['rol']), $rolesPermitidos)) {
    $_SESSION['error'] = "No tienes permiso para recuperar la contraseña.";
    header("Location: ../View/recovery.php?error=");
    
    exit();
}

// Generar nueva clave
$clave = substr(md5(microtime()), 0, 8);
// Actualizar clave en la base de datos
if ($userModel->updatePassword($correo, $clave)) {

    // Enviar correo con PHPMailer
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';


    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'paolaig5609@gmail.com'; // Tu correo Gmail
        $mail->Password   = 'uhyvpfonzfwtxlde';      // Contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Remitente y destinatario
        $mail->setFrom('paolaig5609@gmail.com', 'GlizCraft');
        $mail->addAddress($correo, $user['nombre'] ?? 'Usuario');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña - GlizCraft';
        $mail->Body    = "
            <h2>Recuperación de Contraseña</h2>
            <p>Hola,</p>
            <p>Tu nueva clave para acceder al sistema es: <strong>$clave</strong></p>
            <p>Por seguridad, cambia esta contraseña después de iniciar sesión.</p>
        ";
        $mail->AltBody = "Tu nueva clave es: $clave";

        $mail->send();
        $_SESSION['mensaje'] = "Clave actualizada correctamente. Se ha enviado un correo a <strong>$correo</strong> con la nueva contraseña.";
        header("Location: ../recovery.php?mensaje=");

    } catch (Exception $e) {
        if ($_ENV['ENV'] === 'development') {
            $_SESSION['mensaje'] = "Error al enviar el correo: {$mail->ErrorInfo}";
            header("Location: ../View/recovery.php?error=");
        } else {
            $_SESSION['error'] = "Hubo un error al enviar el correo. Intenta nuevamente más tarde.";
            header(header: "Location: ../view/recovery.php?error=");
        }
        
    }

} else {
        $_SESSION['error'] = "Error al actualizar la contraseña.";
        header(header: "Location: ../view/recovery.php?error=");
}
