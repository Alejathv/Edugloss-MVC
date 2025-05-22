<?php
class MaterialModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMaterialesByModulo($id_modulo) {
        $stmt = $this->conn->prepare("SELECT * FROM videos WHERE id_modulo = ?");
        $stmt->bind_param("i", $id_modulo);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarMaterial($id_modulo, $nombre, $url, $tipo) {
        $stmt = $this->conn->prepare("INSERT INTO videos (id_modulo, nombre, url_video, tipo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_modulo, $nombre, $url, $tipo);
        return $stmt->execute();
    }
}
