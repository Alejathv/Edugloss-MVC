<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/database.php';

class EstudianteController {
    private $db;
    public function __construct($db) {
    $this->db = $db;
    }
    public function mostrarInscripciones() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $id_estudiante = $_SESSION['user_id'];

    $stmt = $this->db->prepare("
        SELECT 
            i.id_inscripcion,
            i.id_modulo,
            i.id_curso,
            m.nombre AS nombre_modulo,
            c.nombre AS nombre_curso,
            c.id_curso AS curso_id
        FROM inscripcion i
        LEFT JOIN modulo m ON i.id_modulo = m.id_modulo
        LEFT JOIN curso c ON i.id_curso = c.id_curso OR m.id_curso = c.id_curso
        WHERE i.id_usuario = ? AND i.estado = 'activa'
        LIMIT 1
    ");
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        return null;
    }

    // Retorna solo la URL que debe usarse en el href
    if (!empty($row['id_modulo'])) {
        return 'ver_materiales.php?id_modulo=' . $row['id_modulo'];
    } elseif (!empty($row['curso_id'])) {
        return 'modulos_curso.php?id_curso=' . $row['curso_id'];
    }

    return null;
}
}