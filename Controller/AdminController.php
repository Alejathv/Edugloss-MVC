<?php
require_once __DIR__ . '/../Model/UserModel.php';

class AdminController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function listarUsuarios() {
        return $this->userModel->getAllUsers();
    }

    public function obtenerUsuario($id) {
        return $this->userModel->getUserById($id);
    }

    public function actualizarUsuario($datos) {
        $this->userModel->updateUser($datos);
    }
}

