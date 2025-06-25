<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../Model/database.php';
require_once __DIR__ . '/../../Controller/DocenteController.php';

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_material'])) {
    if ($controller->eliminarMaterial($_POST['id_material'])) {
        $_SESSION['success'] = "Material eliminado correctamente";
    } else {
        $_SESSION['error'] = "Error al eliminar el material";
    }
}

header("Location: Contenido.php");
exit;
?>