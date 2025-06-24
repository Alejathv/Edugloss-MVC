<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Model/PagoModel.php';
require_once __DIR__ . '/../Model/ClienteModel.php';

class PagoController {
    private $pagoModel;
    private $clienteModel;

    public function __construct($conn) {
        $this->pagoModel = new PagoModel($conn);
        $this->clienteModel = new ClienteModel();
    }

    public function procesarPago() {
        // Validar campos obligatorios
        $requiredFields = ['nombre', 'apellido', 'email', 'telefono', 'tipo_producto', 'id_producto', 'precio_producto'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                header("Location: planes.php?error=campos_requeridos");
                exit();
            }
        }

        // Registrar/actualizar cliente
        $cliente = $this->clienteModel->buscarClientePorEmail($_POST['email']);
        
        if ($cliente) {
            $idCliente = $cliente['id_usuario'];
            $this->clienteModel->actualizarCliente(
                $idCliente,
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['telefono']
            );
        } else {
            $idCliente = $this->clienteModel->registrarCliente(
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['email'],
                $_POST['telefono']
            );
        }

        // Crear registro de pago (siempre como 'transferencia')
        if ($_POST['tipo_producto'] === 'curso') {
            $idPago = $this->pagoModel->crearPagoCurso(
                $idCliente,
                $_POST['id_producto'],
                $_POST['precio_producto'],
                'transferencia'
            );
        } else {
            $idPago = $this->pagoModel->crearPagoModulo(
                $idCliente,
                $_POST['id_producto'],
                $_POST['precio_producto'],
                'transferencia'
            );
        }

        if ($idPago) {
            // Redirigir directamente a la página para subir comprobante
            header("Location: subir_comprobante.php?id=".$idPago);
            exit();
        } else {
            header("Location: planes.php?error=procesar_pago");
            exit();
        }
    }
     public function obtenerPagosAdmin($filtroEstado = null) {
        $query = "SELECT p.*, 
                 u.nombre, u.apellido,
                 IFNULL(c.nombre, m.nombre) as nombre_producto,
                 p.detalles_pago
                 FROM pago p
                 JOIN usuarios u ON p.id_usuario = u.id_usuario
                 LEFT JOIN curso c ON p.id_curso = c.id_curso
                 LEFT JOIN modulo m ON p.id_modulo = m.id_modulo
                 WHERE (? IS NULL OR p.estado = ?)";
        
        $stmt = $this->pagoModel->getDb()->prepare($query);
        $stmt->bind_param("ss", $filtroEstado, $filtroEstado);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cambia el estado de un pago (completado/cancelado)
     */
    public function cambiarEstadoPago($idPago, $nuevoEstado) {
        $estadosPermitidos = ['completado', 'cancelado'];
        if (!in_array($nuevoEstado, $estadosPermitidos)) {
            return false;
        }

        $query = "UPDATE pago SET estado = ? WHERE id_pago = ?";
        $stmt = $this->pagoModel->getDb()->prepare($query);
        $stmt->bind_param("si", $nuevoEstado, $idPago);
        return $stmt->execute();
    }

    /**
     * Obtiene detalles completos de un pago
     */
    public function obtenerDetallePago($idPago) {
        $query = "SELECT p.*, 
                 u.nombre, u.apellido, u.email, u.telefono,
                 IFNULL(c.nombre, m.nombre) as nombre_producto,
                 p.detalles_pago
                 FROM pago p
                 JOIN usuarios u ON p.id_usuario = u.id_usuario
                 LEFT JOIN curso c ON p.id_curso = c.id_curso
                 LEFT JOIN modulo m ON p.id_modulo = m.id_modulo
                 WHERE p.id_pago = ?";
        
        $stmt = $this->pagoModel->getDb()->prepare($query);
        $stmt->bind_param("i", $idPago);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function obtenerPagoPorId($idPago) {
        $query = "SELECT * FROM pago WHERE id_pago = ?";
        $stmt = $this->pagoModel->getDb()->prepare($query);
        $stmt->bind_param("i", $idPago);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}