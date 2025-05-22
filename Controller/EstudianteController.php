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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        return "Error: No has iniciado sesión.";
    }

    $id_estudiante = $_SESSION['user_id'];

    // Consulta: obtener inscripciones y unir con curso o módulo según cuál esté presente
    $stmt = $this->db->prepare("
        SELECT 
            i.id_modulo,
            i.id_curso,
            m.nombre AS nombre_modulo,
            c.nombre AS nombre_curso
        FROM inscripcion i
        LEFT JOIN modulo m ON i.id_modulo = m.id_modulo
        LEFT JOIN curso c ON i.id_curso = c.id_curso
        WHERE i.id_usuario = ?
    ");
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    $inscripciones = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($inscripciones)) {
        return "<p>No estás inscrito en ningún curso o módulo.</p>";
    }

    $html = "<ul>";
    foreach ($inscripciones as $row) {
       if (!empty($row['id_modulo'])) {
            $html .= '<a href="#"><i class="fa-solid fa-book"></i><span>' 
                  . htmlspecialchars($row['nombre_modulo']) 
                  . '</span></a>';
        } elseif (!empty($row['id_curso'])) {
            $html .= '<a href="ver_videos.php?id_curso=' . urlencode($row['id_curso']) . '">
                        <i class="fa-solid fa-book"></i><span>' 
                        . htmlspecialchars($row['nombre_curso']) 
                        . '</span></a>';
        }
    }
    return $html;

}

}