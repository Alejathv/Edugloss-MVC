<?php
session_start();
require_once __DIR__ . '/../Model/database.php';
require_once __DIR__ . '/../Model/ClienteModel.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $nombre = isset($_POST['nombre_usuario']) ? trim($_POST['nombre_usuario']) : '';
    $apellido = isset($_POST['apellido_usuario']) ? trim($_POST['apellido_usuario']) : '';
    $correo = isset($_POST['email_usuario']) ? trim($_POST['email_usuario']) : '';
    $telefono = isset($_POST['telefono_usuario']) ? trim($_POST['telefono_usuario']) : '';
    
    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../index.php?error=");
        exit();
    }
    
    // Validar formato de correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El formato del correo electrónico no es válido.";
        header("Location: ../index.php?error=" );
        exit();
    }

    // Validar formato de teléfono (solo números y máximo 12 caracteres)
    if (!preg_match('/^[0-9]{7,12}$/', $telefono)) {
        $_SESSION['error'] = "El formato del teléfono no es válido. Debe contener entre 7 y 12 dígitos.";
        header("Location: ../index.php?error=" );
        exit();
    }
    
    // Crear una instancia del modelo de cliente
    $clienteModel = new ClienteModel();
    
    // Verificar si el correo ya existe
    if ($clienteModel->emailExists($correo)) {
        $_SESSION['error'] = "El correo electrónico ya está registrado.";
        header("Location: ../index.php?error=");
        exit();
    }
    
    // Registrar el cliente
    $resultado = $clienteModel->registrarCliente($nombre, $apellido, $correo, $telefono);
    
    if ($resultado) {
        $_SESSION['mensaje'] = "¡Registro exitoso! Nos pondremos en contacto contigo pronto.";
        header("Location: ../index.php?mensaje=" );
    } else {
        $_SESSION['error'] = "Hubo un error al registrar. Por favor, intenta nuevamente.";
        header("Location: ../index.php?error=" );
    }
    
    exit();
} else {
    // Si no es una petición POST, redirigir al index
    header("Location: ../index.php");
    exit();
}
?>
