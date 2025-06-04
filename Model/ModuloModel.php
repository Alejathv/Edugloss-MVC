<?php
require_once __DIR__ . '/database.php';
class ModuloModel {
        private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function crearModulo($id_curso, $nombre, $descripcion, $precio) {
        $stmt = $this->db->prepare("INSERT INTO modulo (id_curso, nombre, descripcion, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $id_curso, $nombre, $descripcion, $precio);
        return $stmt->execute();
    }


    public function obtenerModulos() {
        $result = $this->db->query("SELECT * FROM modulo");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerModulosPorCurso($id_curso) {
        $stmt = $this->db->prepare("SELECT * FROM modulo WHERE id_curso = ?");
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function eliminarModulo($id_modulo) {
    $stmt = $this->db->prepare("DELETE FROM modulo WHERE id_modulo = ?");
    $stmt->bind_param("i", $id_modulo);
    return $stmt->execute();
}

}
