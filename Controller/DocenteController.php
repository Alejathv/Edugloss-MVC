<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Model/MaterialModel.php';

class DocenteController {
    private $materialModel;

    public function __construct($conn) {
        $this->materialModel = new MaterialModel($conn);
    }

    public function listarMateriales($id_modulo) {
        return $this->materialModel->obtenerMaterialPorModulo($id_modulo);
    }
    public function subir() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_modulo = $_POST['id_modulo'];
        $nombre = $_POST['nombre'];
        $url = $_POST['url'];
        $tipo = $_POST['tipo'];

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'La URL ingresada no es válida.'];
        }

        if ($this->materialModel->agregarMaterial($id_modulo, $nombre, $url, $tipo)) {
            return ['success' => true, 'message' => 'Material subido con éxito.'];
        } else {
            return ['success' => false, 'message' => 'Error al subir el material.'];
        }
    }
    return ['success' => false, 'message' => 'Método no permitido'];
}

}
