<?php
require_once __DIR__ . '/database.php';

class InscripcionModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Inscribe un usuario y actualiza su rol
     * @param int $id_pago ID del pago
     * @param int $id_usuario ID del usuario
     * @param int|null $id_curso ID del curso (puede ser null)
     * @param int|null $id_modulo ID del módulo (puede ser null)
     * @return bool True si la operación fue exitosa
     */
    public function inscribirUsuario($id_pago, $id_usuario, $id_curso, $id_modulo) {
        $this->conn->begin_transaction();

        try {
            // 1. Insertar en la tabla inscripcion
            $stmt = $this->conn->prepare("
                INSERT INTO inscripcion (id_pago, id_usuario, id_curso, id_modulo, estado) 
                VALUES (?, ?, ?, ?, 'activa')
            ");
            $stmt->bind_param("iiii", $id_pago, $id_usuario, $id_curso, $id_modulo);
            $stmt->execute();

            // 2. Actualizar rol del usuario a 'estudiante'
            $stmt = $this->conn->prepare("
                UPDATE usuarios 
                SET rol = 'estudiante' 
                WHERE id_usuario = ? AND rol = 'cliente'
            ");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error al inscribir usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un pago ya tiene una inscripción
     * @param int $id_pago ID del pago
     * @return bool True si ya existe una inscripción
     */
    public function existeInscripcion($id_pago) {
    $stmt = $this->conn->prepare("
        SELECT COUNT(*) as total 
        FROM inscripcion 
        WHERE id_pago = ?
    ");
    if (!$stmt) {
        error_log("Error en prepare: " . $this->conn->error);
        return false;
    }

    $stmt->bind_param("i", $id_pago);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    } else {
        error_log("Error en get_result: " . $stmt->error);
        return false;
    }
}

}
?>