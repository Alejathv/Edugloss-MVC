<?php
require_once '../Model/Database.php';
require_once '../Model/UserModel.php';

if (!isset($_POST['email_usuario']) || empty($_POST['email_usuario'])) {
    echo "<p>Error: No se proporcionó un correo válido.</p>";
    exit();
}

$correo = trim($_POST['email_usuario']); // Elimina espacios en blanco

// Crear instancia de UserModel
$userModel = new UserModel();
$user = $userModel->getUserByEmail($correo);

if (!$user) {
    echo "<p>No se encontró ninguna cuenta con ese correo.</p>";
    exit();
}

// Generar clave aleatoria de 8 caracteres
$clave = substr(md5(microtime()), 0, 8);

// Actualizar contraseña usando el método de UserModel
if ($userModel->updatePassword($correo, $clave)) {
    echo "<p>Clave actualizada correctamente. La nueva clave es: <strong>$clave</strong></p>";
} else {
    echo "<p>Error al actualizar la contraseña.</p>";
}
?>