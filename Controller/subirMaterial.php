<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye el modelo desde la carpeta correcta
require_once __DIR__ . '/../Model/MaterialModel.php';

// Incluye el controlador (ya que estás dentro de Controller)
require_once __DIR__ . '/DocenteController.php';

require_once __DIR__ . '/../Model/database.php';  // Ajusta ruta si hace falta

// Conexión a base de datos
$db = new Database();
$conn = $db->getConnection();

// Luego pasas $conn al controlador
require_once __DIR__ . '/DocenteController.php';

$docenteController = new DocenteController($conn);
$docenteController->subirMaterial();