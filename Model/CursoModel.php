<?php
// En tu modelo CursoModel.php
class CursoModel {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

public function getCursosInscritosPorUsuario($id_usuario) {
    $stmt = $this->db->prepare("SELECT c.* FROM curso c
        JOIN inscripcion i ON c.id_curso = i.id_curso
        WHERE i.id_usuario = ? AND i.estado = 'activa'");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

    public function getModulosYVideosPorCurso($id_curso) {
    $sql = "SELECT 
                m.id_modulo, m.nombre AS nombre_modulo, m.descripcion,
                v.id_video, v.nombre AS nombre_video, v.url_video
            FROM modulo m
            LEFT JOIN videos v ON m.id_modulo = v.id_modulo
            WHERE m.id_curso = ?
            ORDER BY m.id_modulo, v.id_video";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    $modulos = [];
    while ($row = $result->fetch_assoc()) {
        $id_modulo = $row['id_modulo'];
        if (!isset($modulos[$id_modulo])) {
            $modulos[$id_modulo] = [
                'nombre_modulo' => $row['nombre_modulo'],
                'descripcion' => $row['descripcion'],
                'videos' => []
            ];
        }

        if (!empty($row['id_video'])) {
            $modulos[$id_modulo]['videos'][] = [
                'id_video' => $row['id_video'],
                'nombre_video' => $row['nombre_video'],
                'url_video' => $row['url_video']
            ];
        }
    }

    return $modulos;
}

}

?>