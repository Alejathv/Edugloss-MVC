<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../libs/vendor/autoload.php'; // Carga PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class InscripcionModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Envía un correo con las credenciales al nuevo estudiante
     * @param string $correo Correo del estudiante
     * @param string $clave Contraseña generada
     * @return bool True si el envío fue exitoso
     */
    private function enviarCredenciales($correo, $clave) {
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
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido como Estudiante - GlizCraft';
            $mail->Body    = "
                <h2>¡Bienvenido a nuestra plataforma educativa!</h2>
                <p>Tu registro como estudiante ha sido exitoso.</p>
                <p>Tus credenciales para acceder al sistema son:</p>
                <p><strong>Usuario:</strong> $correo</p>
                <p><strong>Contraseña:</strong> $clave</p>
                <p>Por seguridad, te recomendamos cambiar esta contraseña después de iniciar sesión.</p>
            ";
            $mail->AltBody = "Tus credenciales:\nUsuario: $correo\nContraseña: $clave";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el correo de un usuario por su ID
     * @param int $id_usuario ID del usuario
     * @return string|null Correo del usuario o null si no existe
     */
    private function obtenerCorreoUsuario($id_usuario) {
        $stmt = $this->conn->prepare("SELECT correo FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['correo'];
        }
        return null;
    }

    /**
     * Genera una contraseña aleatoria
     * @return string Contraseña generada
     */
    private function generarContraseña() {
        return substr(md5(microtime()), 0, 8);
    }

    /**
     * Actualiza la contraseña de un usuario
     * @param int $id_usuario ID del usuario
     * @param string $clave Nueva contraseña
     * @return bool True si la actualización fue exitosa
     */
    private function actualizarContraseña($id_usuario, $clave) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET clave = ? WHERE id_usuario = ?");
        $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hashedPassword, $id_usuario);
        return $stmt->execute();
    }

    /**
     * Inscribe un usuario y actualiza su rol
     * @param int $id_pago ID del pago
     * @param int $id_usuario ID del usuario
     * @param int|null $id_curso ID del curso (puede ser null)
     * @param int|null $id_modulo ID del módulo (puede ser null)
     * @return bool True si la operación fue exitosa
     */
    public function inscribirUsuario($id_pago, $id_usuario, $id_curso, $id_modulo) {
        $this->conn->begin_transaction();

        try {
            // 1. Insertar en la tabla inscripcion
            $stmt = $this->conn->prepare("
                INSERT INTO inscripcion (id_pago, id_usuario, id_curso, id_modulo, estado) 
                VALUES (?, ?, ?, ?, 'activa')
            ");
            $stmt->bind_param("iiii", $id_pago, $id_usuario, $id_curso, $id_modulo);
            $stmt->execute();

            // 2. Actualizar rol del usuario a 'estudiante'
            $stmt = $this->conn->prepare("
                UPDATE usuarios 
                SET rol = 'estudiante' 
                WHERE id_usuario = ? AND rol = 'cliente'
            ");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();

            // 3. Obtener correo del usuario
            $correo = $this->obtenerCorreoUsuario($id_usuario);
            if (!$correo) {
                throw new Exception("No se pudo obtener el correo del usuario");
            }

            // 4. Generar y actualizar contraseña
            $clave = $this->generarContraseña();
            if (!$this->actualizarContraseña($id_usuario, $clave)) {
                throw new Exception("No se pudo actualizar la contraseña");
            }

            // 5. Enviar correo con credenciales
            if (!$this->enviarCredenciales($correo, $clave)) {
                throw new Exception("No se pudo enviar el correo con las credenciales");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error al inscribir usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un pago ya tiene una inscripción
     * @param int $id_pago ID del pago
     * @return bool True si ya existe una inscripción
     */
    public function existeInscripcion($id_pago) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total 
            FROM inscripcion 
            WHERE id_pago = ?
        ");
        if (!$stmt) {
            error_log("Error en prepare: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id_pago);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] > 0;
        } else {
            error_log("Error en get_result: " . $stmt->error);
            return false;
        }
    }
}