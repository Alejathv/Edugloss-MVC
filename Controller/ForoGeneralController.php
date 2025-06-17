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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['responder_a'])) {
    $id_usuario = $_SESSION['user_id'];
    $contenido = trim($_POST['contenido_respuesta']);
    $id_comentario_padre = (int)$_POST['responder_a'];

    if (!empty($contenido)) {
        $foroModel->insertarRespuesta($id_usuario, $contenido, $id_comentario_padre);
        header("Location: ../View/ForoGeneral.php");
        exit;
    }
}

// Obtener comentarios anidados en lugar de planos
$comentarios = $foroModel->getComentariosAnidados();

$id_comentario_padre = (int)$_POST['responder_a'];
if ($foroModel->insertarRespuesta($id_usuario, $contenido, $id_comentario_padre)) {
    // Obtener el autor del comentario original para notificarle
    $sql = "SELECT id_usuario FROM foro_comentarios WHERE id_comentario = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_comentario_padre);
    $stmt->execute();
    $result = $stmt->get_result();
    $autor_original = $result->fetch_assoc();
    
    if ($autor_original && $autor_original['id_usuario'] != $id_usuario) {
        $foroModel->crearNotificacion($autor_original['id_usuario'], $id_comentario_padre, 'respuesta');
    }
}
}

include '../View/ForoGeneral.php';
