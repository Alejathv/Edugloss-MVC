<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../Model/ForoModel.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../Model/ForoModel.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['rol_nombre'], ['estudiante', 'docente'])) {
    header("Location: login.php");
    exit;
}

$foroModel = new ForoModel();

// Obtener todos los comentarios (sin respuestas)
$comentarios = $foroModel->getTodosLosComentariosGenerales();

// Guardar nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenido'])) {
    $id_usuario = $_SESSION['user_id'];
    $contenido = trim($_POST['contenido']);

    if (!empty($contenido)) {
        $foroModel->insertarComentarioGeneral($id_usuario, $contenido);
        header("Location: ../View/ForoGeneral.php");

        exit;
    }
}

// Opcional: editar y eliminar, si los quieres mantener, ajusta aquí

include '../View/ForoGeneral.php';
