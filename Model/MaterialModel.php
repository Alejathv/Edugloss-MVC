<?php
class MaterialModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function obtenerMaterialPorModulo($id_modulo) {
        $stmt = $this->conn->prepare("SELECT * FROM material WHERE id_modulo = ?");
        $stmt->bind_param("i", $id_modulo);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarMaterial($id_modulo, $nombre, $url, $tipo) {
        $stmt = $this->conn->prepare("INSERT INTO material (id_modulo, nombre, url_material, tipo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_modulo, $nombre, $url, $tipo);
        return $stmt->execute();
    }
    public function actualizarMaterial($id_material, $nombre, $url_material, $tipo, $id_modulo) {
        $stmt = $this->conn->prepare("UPDATE material SET 
                                    nombre = ?,
                                    url_material = ?,
                                    tipo = ?,
                                    id_modulo = ?
                                    WHERE id_material = ?");
        $stmt->bind_param("sssii", $nombre, $url_material, $tipo, $id_modulo, $id_material);
        return $stmt->execute();
    }

    public function eliminarMaterial($id_material) {
        $stmt = $this->conn->prepare("DELETE FROM material WHERE id_material = ?");
        $stmt->bind_param("i", $id_material);
        return $stmt->execute();
    }

    public function obtenerMaterialPorId($id_material) {
        $stmt = $this->conn->prepare("SELECT * FROM material WHERE id_material = ?");
        $stmt->bind_param("i", $id_material);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}
