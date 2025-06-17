<?php
require_once __DIR__ . '/database.php';

class ForoModel {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    // Obtener todos los comentarios generales sin respuestas
    public function getTodosLosComentariosGenerales() {
        $sql = "SELECT fc.*, u.nombre, u.apellido 
                FROM foro_comentarios fc
                JOIN usuarios u ON fc.id_usuario = u.id_usuario
                ORDER BY fc.fecha ASC";  // orden cronológico ascendente
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Insertar comentario general (sin respuesta)
    public function insertarComentarioGeneral($id_usuario, $contenido) {
        $id_foro_general = 505; // ID fijo para foro general
        $sql = "INSERT INTO foro_comentarios (id_foro, id_usuario, contenido, fecha) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iis", $id_foro_general, $id_usuario, $contenido);
        return $stmt->execute();
    }

    // Opcional: editar y eliminar comentario (sin manejo de respuestas)
    public function editarComentario($id_comentario, $contenido) {
        $sql = "UPDATE foro_comentarios SET contenido = ? WHERE id_comentario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $contenido, $id_comentario);
        return $stmt->execute();
    }

    public function eliminarComentario($id_comentario) {
        $sql = "DELETE FROM foro_comentarios WHERE id_comentario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_comentario);
        return $stmt->execute();
    }
     public function getComentariosAnidados() {
        $sql = "SELECT fc.*, u.nombre, u.apellido 
                FROM foro_comentarios fc
                JOIN usuarios u ON fc.id_usuario = u.id_usuario
                WHERE fc.id_foro = 505
                ORDER BY COALESCE(id_comentario_padre, id_comentario), fecha ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $comentarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Organizar en estructura jerárquica
        $arbol = [];
        foreach ($comentarios as $comentario) {
            if ($comentario['id_comentario_padre'] === null) {
                $arbol[$comentario['id_comentario']] = $comentario;
                $arbol[$comentario['id_comentario']]['respuestas'] = [];
            } else {
                $arbol[$comentario['id_comentario_padre']]['respuestas'][] = $comentario;
            }
        }
        return $arbol;
    }

    // Insertar respuesta (nuevo método)
    public function insertarRespuesta($id_usuario, $contenido, $id_comentario_padre) {
        $sql = "INSERT INTO foro_comentarios (id_foro, id_usuario, contenido, fecha, id_comentario_padre) 
                VALUES (505, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isi", $id_usuario, $contenido, $id_comentario_padre);
        return $stmt->execute();
    }
    public function crearNotificacion($id_usuario, $id_comentario, $tipo) {
    $sql = "INSERT INTO notificaciones (id_usuario, id_comentario, tipo) VALUES (?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iis", $id_usuario, $id_comentario, $tipo);
    return $stmt->execute();
}

}

?>