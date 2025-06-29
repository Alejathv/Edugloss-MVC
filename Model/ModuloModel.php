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

    public function eliminarModulo($idModulo) {
    // Elimina pagos relacionados con el módulo
    $stmtPagos = $this->db->prepare("DELETE FROM pago WHERE id_modulo = ?");
    $stmtPagos->bind_param("i", $idModulo);
    $stmtPagos->execute();
    $stmtPagos->close();

    // Ahora elimina el módulo
    $stmtModulo = $this->db->prepare("DELETE FROM modulo WHERE id_modulo = ?");
    $stmtModulo->bind_param("i", $idModulo);
    $resultado = $stmtModulo->execute();
    $stmtModulo->close();

    return $resultado;
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
public function obtenerModulosDisponibles() {
        $stmt = $this->db->prepare("SELECT id_modulo, nombre, descripcion, precio FROM modulo WHERE estado = 'disponible'");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $modulos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $modulos;
    }

    public function obtenerModuloPorId($id_modulo) {
        $stmt = $this->db->prepare("SELECT id_modulo, nombre, descripcion, precio FROM modulo WHERE id_modulo = ?");
        $stmt->bind_param("i", $id_modulo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $modulo = $resultado->fetch_assoc();
        $stmt->close();
        
        return $modulo;
    }


}
