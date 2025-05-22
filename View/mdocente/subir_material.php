<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../Model/database.php";
require_once "../../Controller/DocenteController.php";

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->subirMaterial();
    exit;
}

$id_modulo = $_GET['id_modulo'] ?? 0;
$materiales = $controller->listarMateriales($id_modulo);
?>

<h2>Subir material para el módulo <?= $id_modulo ?></h2>

<form action="../../Controller/subirMaterial.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_modulo" value="123">
    <input type="text" name="nombre" placeholder="Nombre del archivo">
    <input type="file" name="archivo" accept=".pdf,.mp4,.avi">
    <button type="submit">Subir archivo</button>
</form>


<h3>Materiales ya subidos:</h3>
<ul>
<?php foreach ($materiales as $mat): ?>
    <li><?= htmlspecialchars($mat['nombre']) ?> - <?= $mat['tipo'] ?></li>
<?php endforeach; ?>
</ul>
