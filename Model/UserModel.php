<?php
require_once 'Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getUserByEmail($correo) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePassword($correo, $newPassword) {
        $stmt = $this->db->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
        $stmt->bind_param("ss", $newPassword, $correo);

        
        return $stmt->execute();
    }
    public function getInscripcionesByUserId($userId) {
        $sql = "SELECT i.id_modulo, i.id_curso, m.nombre AS nombre_modulo, c.nombre AS nombre_curso
                FROM inscripcion i
                LEFT JOIN modulo m ON i.id_modulo = m.id_modulo
                LEFT JOIN curso c ON i.id_curso = c.id_curso
                WHERE i.id_usuario = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $inscripciones = [];
        while ($row = $result->fetch_assoc()) {
            $inscripciones[] = $row;
        }
        return $inscripciones;
    }

    //paola documento
    public function getAllUsers() {
        $sql = "SELECT * FROM usuarios";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($date) {
        $sql = "UPDATE usuarios SET nombre=?, apellido=?, telefono=?, correo=?, rol=?, estado=?, especialidad=? WHERE id_usuario=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssssi",
            $date['nombre'],
            $date['apellido'],
            $date['telefono'],
            $date['correo'],
            $date['rol'],
            $date['estado'],
            $date['especialidad'],
            $date['id_usuario']
        );
        $stmt->execute();
    }

}
?>
