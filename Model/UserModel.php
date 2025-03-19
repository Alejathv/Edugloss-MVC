<?php
require_once 'Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function updatePassword($email, $newPassword) {
        $stmt = $this->db->prepare("UPDATE usuarios SET contrasena = ? WHERE email_usuario = ?");
        $stmt->bind_param("ss", $newPassword, $email);
        
        return $stmt->execute();
    }
}
?>
