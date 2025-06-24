<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Model/inscripcionmodel.php';


class InscripcionController {
    private $inscripcionModel;

    public function __construct($conn) {
        $this->inscripcionModel = new InscripcionModel($conn);
    }

    /**
     * Procesa la inscripción de un usuario
     * @param int $id_pago ID del pago
     * @param array $pagoData Datos del pago (debe contener id_usuario, id_curso o id_modulo)
     * @return array Resultado de la operación
     */
    public function procesarInscripcion($id_pago, $pagoData) {
        // Verificar si el pago ya tiene una inscripción
        if ($this->inscripcionModel->existeInscripcion($id_pago)) {
            return [
                'success' => false,
                'message' => 'Este pago ya tiene una inscripción asociada.'
            ];
        }

        // Extraer datos necesarios
        $id_usuario = $pagoData['id_usuario'];
        $id_curso = $pagoData['id_curso'] ?? null;
        $id_modulo = $pagoData['id_modulo'] ?? null;

        // Validar que tenga al menos curso o módulo
        if (empty($id_curso) && empty($id_modulo)) {
            return [
                'success' => false,
                'message' => 'El pago no está asociado a ningún curso o módulo.'
            ];
        }

        // Realizar la inscripción
        $resultado = $this->inscripcionModel->inscribirUsuario(
            $id_pago, 
            $id_usuario, 
            $id_curso, 
            $id_modulo
        );

        return [
            'success' => $resultado,
            'message' => $resultado 
                ? 'Inscripción realizada correctamente.' 
                : 'Error al realizar la inscripción.'
        ];
    }
}
?>