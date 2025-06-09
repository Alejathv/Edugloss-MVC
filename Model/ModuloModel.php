<?php
require_once __DIR__ . '/database.php';
class ModuloModel {
        private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function crearModulo($id_curso, $nombre, $descripcion, $precio, $estado = 'disponible') {
        $stmt = $this->db->prepare("INSERT INTO modulo (id_curso, nombre, descripcion, precio, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $id_curso, $nombre, $descripcion, $precio, $estado);
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
    public function obtenerModuloPorId($id_modulo) {
    $stmt = $this->db->prepare("SELECT * FROM modulo WHERE id_modulo = ?");
    $stmt->bind_param("i", $id_modulo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }


    public function actualizarModulo($data) {
    $stmt = $this->db->prepare("UPDATE modulo SET nombre = ?, descripcion = ?, precio = ?, estado = ? WHERE id_modulo = ?");
    $stmt->bind_param("ssdsi",
        $data['nombre'],
        $data['descripcion'],
        $data['precio'],
        $data['estado'],
        $data['id_modulo']
    );
    return $stmt->execute();
}


}
