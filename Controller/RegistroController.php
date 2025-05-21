<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../Model/database.php';
require_once __DIR__ . '/../Model/ClienteModel.php';
require_once __DIR__ . '/../libs/vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre_usuario']) ? trim($_POST['nombre_usuario']) : '';
    $apellido = isset($_POST['apellido_usuario']) ? trim($_POST['apellido_usuario']) : '';
    $correo = isset($_POST['email_usuario']) ? trim($_POST['email_usuario']) : '';
    $telefono = isset($_POST['telefono_usuario']) ? trim($_POST['telefono_usuario']) : '';

    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../index.php?error=");
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El formato del correo electrónico no es válido.";
        header("Location: ../index.php?error=" );
        exit();
    }

    if (!preg_match('/^[0-9]{7,12}$/', $telefono)) {
        $_SESSION['error'] = "El formato del teléfono no es válido. Debe contener entre 7 y 12 dígitos.";
        header("Location: ../index.php?error=" );
        exit();
    }

    $clienteModel = new ClienteModel();

    if ($clienteModel->emailExists($correo)) {
        $_SESSION['error'] = "El correo electrónico ya está registrado.";
        header("Location: ../index.php?error=");
        exit();
    }

    $resultado = $clienteModel->registrarCliente($nombre, $apellido, $correo, $telefono);

    if ($resultado) {
        // ---------------------------
        // Enviar correo con PHPMailer
        // ---------------------------
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

            $mail->isHTML(true);
            $mail->Subject ='¡Bienvenido(a) a EduGlos! Explora nuestros planes';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                    <div style='max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>
                        <h2 style='color: #6f42c1;'>¡Hola $nombre!</h2>
                        <p style='font-size: 16px; color: #333;'>
                            ¡Gracias por registrarte en <strong>EduGlos</strong>! Hemos recibido tus datos correctamente.
                        </p>
                        <p style='font-size: 16px; color: #333;'>
                            Te invitamos a conocer todos los planes y beneficios que tenemos para ti. Descubre cuál se ajusta mejor a tus necesidades.
                        </p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='https://wa.me/+5491134106985' style='background-color: #0dcaf0; color: #fff; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;'>
                                Chatea con Nosotros
                            </a>
                        </div>
                                </a>
                        <hr style='margin: 30px 0;'>
                        <p style='font-size: 12px; color: #bbb;'>
                            Este correo fue generado automáticamente. No respondas directamente a este mensaje.<br>
                            &copy; " . date('Y') . " EduGlos. Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            ";
            $mail->AltBody = "Hola $nombre,\n\nGracias por registrarte en EduGlos.";


            $mail->send();
        } catch (Exception $e) {
            // Si el correo falla, igual registramos al usuario, solo logueamos el error (opcional)
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
        }

        $_SESSION['mensaje'] = "¡Registro exitoso! Nos pondremos en contacto contigo pronto.";
        header("Location: ../index.php?mensaje=");
    } else {
        $_SESSION['error'] = "Hubo un error al registrar. Por favor, intenta nuevamente.";
        header("Location: ../index.php?error=");
    }

    exit();
} else {
    header("Location: ../index.php");
    exit();
}

