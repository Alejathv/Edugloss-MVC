<?php
require_once 'Database.php';

class ClienteModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Verifica si un correo electrónico ya existe en la base de datos
     * @param string $correo
     * @return bool
     */
    public function emailExists($correo) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count > 0;
    }

    /**
     * Registra un nuevo cliente en la base de datos
     * @param string $nombre
     * @param string $apellido
     * @param string $correo
     * @param string $telefono
     * @return bool
     */
    public function registrarCliente($nombre, $apellido, $correo, $telefono) {
        // Definir rol como 'cliente'
        $rol = 'cliente';
        $estado = 'activo';
        $fecha_creacion = date('Y-m-d');
        
        // La contraseña será NULL
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, apellido, telefono, correo, contraseña, rol, fecha_creacion, estado) VALUES (?, ?, ?, ?, NULL, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nombre, $apellido, $telefono, $correo, $rol, $fecha_creacion, $estado);
        
        $resultado = $stmt->execute();
        $stmt->close();
        
        return $resultado;
    }
     public function buscarClientePorEmail($correo) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        
        return $resultado->fetch_assoc();
    }

    public function actualizarCliente($id_cliente, $nombre, $apellido, $telefono) {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, telefono = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nombre, $apellido, $telefono, $id_cliente);
        
        $resultado = $stmt->execute();
        $stmt->close();
        
        return $resultado;
    }
}
?>