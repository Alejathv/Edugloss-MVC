<?php
class PagoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


     public function crearPagoModulo($id_usuario, $id_modulo, $monto, $metodo_pago, $referencia = null, $comprobante = null) {
        // Verificar que el usuario existe primero
        if (!$this->usuarioExiste($id_usuario)) {
            error_log("Error: El usuario con ID $id_usuario no existe");
            return false;
        }

        $estado = 'pendiente';
        $detalles = json_encode([
            'monto' => $monto,
            'metodo_pago' => $metodo_pago,
            'referencia' => $referencia,
            'comprobante' => $comprobante
        ]);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO pago (id_usuario, id_modulo, estado, detalles_pago, fecha_pago) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiss", $id_usuario, $id_modulo, $estado, $detalles);
            
            if (!$stmt->execute()) {
                error_log("Error ejecutando query: ".$stmt->error);
                return false;
            }
            
            return $stmt->insert_id;
        } catch (mysqli_sql_exception $e) {
            error_log("Error en crearPagoModulo: ".$e->getMessage());
            return false;
        }
    }

    // Método para verificar existencia de usuario
    private function usuarioExiste($id_usuario) {
        $stmt = $this->db->prepare("SELECT 1 FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_row();
    }

    public function obtenerPagoPorId($id_pago) {
        $stmt = $this->db->prepare("SELECT p.*, 
                                  JSON_UNQUOTE(JSON_EXTRACT(p.detalles_pago, '$.monto')) AS monto,
                                  JSON_UNQUOTE(JSON_EXTRACT(p.detalles_pago, '$.metodo_pago')) AS metodo_pago,
                                  u.nombre, u.apellido, u.correo, u.telefono,
                                  IFNULL(c.nombre, m.nombre) AS nombre_producto
                                  FROM pago p
                                  LEFT JOIN curso c ON p.id_curso = c.id_curso
                                  LEFT JOIN modulo m ON p.id_modulo = m.id_modulo
                                  JOIN usuarios u ON p.id_usuario = u.id_usuario
                                  WHERE p.id_pago = ?");
        $stmt->bind_param("i", $id_pago);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        
        return $resultado->fetch_assoc();
    }

    public function actualizarPago($id_pago, $referencia, $comprobante) {
        $stmt = $this->db->prepare("UPDATE pago SET 
                                  referencia_pago = ?,
                                  detalles_pago = JSON_SET(detalles_pago, '$.comprobante', ?)
                                  WHERE id_pago = ?");
        $stmt->bind_param("ssi", $referencia, $comprobante, $id_pago);
        
        $resultado = $stmt->execute();
        $stmt->close();
        
        return $resultado;
    }
    // En PagoModel.php
public function crearPagoCurso($id_usuario, $id_curso, $monto, $metodo_pago, $referencia = null, $comprobante = null) {
    // Verificar primero que el usuario existe
    $stmtCheck = $this->db->prepare("SELECT 1 FROM usuarios WHERE id_usuario = ?");
    $stmtCheck->bind_param("i", $id_usuario);
    $stmtCheck->execute();
    
    if (!$stmtCheck->get_result()->fetch_row()) {
        error_log("Error: El usuario con ID $id_usuario no existe");
        return false;
    }
    
    $estado = 'pendiente';
    $detalles = json_encode([
        'monto' => $monto,
        'metodo_pago' => $metodo_pago,
        'referencia' => $referencia,
        'comprobante' => $comprobante
    ]);
    
    $stmt = $this->db->prepare("INSERT INTO pago 
        (id_usuario, id_curso, estado, detalles_pago, fecha_pago) 
        VALUES (?, ?, ?, ?, NOW())");
    
    if (!$stmt) {
        error_log("Error preparando query: ".$this->db->error);
        return false;
    }

    $stmt->bind_param("iiss", $id_usuario, $id_curso, $estado, $detalles);
    
    if (!$stmt->execute()) {
        error_log("Error ejecutando query: ".$stmt->error);
        return false;
    }
    
    return $this->db->insert_id;
}
 public function obtenerTodosPagos($estado = null) {
        $query = "SELECT p.*, 
                 u.nombre, u.apellido,
                 IFNULL(c.nombre, m.nombre) AS nombre_producto,
                 JSON_UNQUOTE(JSON_EXTRACT(p.detalles_pago, '$.monto')) AS monto,
                 JSON_UNQUOTE(JSON_EXTRACT(p.detalles_pago, '$.metodo_pago')) AS metodo_pago
                 FROM pago p
                 JOIN usuarios u ON p.id_usuario = u.id_usuario
                 LEFT JOIN curso c ON p.id_curso = c.id_curso
                 LEFT JOIN modulo m ON p.id_modulo = m.id_modulo
                 WHERE (? IS NULL OR p.estado = ?)
                 ORDER BY p.fecha_pago DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $estado, $estado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Actualiza el estado de un pago
     */
    public function actualizarEstadoPago($id_pago, $nuevo_estado) {
        $estados_permitidos = ['pendiente', 'completado', 'cancelado'];
        if (!in_array($nuevo_estado, $estados_permitidos)) {
            return false;
        }
        
        $stmt = $this->db->prepare("UPDATE pago SET estado = ? WHERE id_pago = ?");
        $stmt->bind_param("si", $nuevo_estado, $id_pago);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    /**
     * Método para obtener la conexión (necesario para el PagoController)
     */
    public function getDb() {
        return $this->db;
    }

}
?>