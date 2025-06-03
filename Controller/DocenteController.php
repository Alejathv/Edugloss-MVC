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
        return $this->materialModel->getMaterialesByModulo($id_modulo);
    }
}
