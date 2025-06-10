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
}
