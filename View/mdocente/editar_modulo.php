<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../../Controller/CursoModuloController.php';
$db = new Database();
$conn = $db->getConnection();

$moduloCtrl = new ModuloController($conn);


if (!isset($_GET['id'])) {
    echo "Curso no especificado.";
    exit;
}

$id_modulo= $_GET['id'];
$modulo = $moduloCtrl->obtenerModuloPorId($id_modulo);

if (!$modulo) {
    echo "Modulo no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduloCtrl->actualizarModulo($_POST);
    header("Location: TablasCM.php?editado=modulo");
    exit;
}
?>

<h2>Editar modulo</h2>
<form method="POST">
    <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($modulo['nombre']) ?>" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($modulo['descripcion']) ?></textarea><br>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($modulo['precio']) ?>" required><br>

    <label>Estado:</label>
    <select name="estado">
        <option value="disponible" <?= htmlspecialchars($modulo['estado'] == 'disponible' ? 'selected' : '') ?>>Disponible</option>
        <option value="cerrado" <?= htmlspecialchars($modulo['estado'] == 'cerrado' ? 'selected' : '') ?>>Cerrado</option>
    </select><br>

    <button type="submit">Actualizar Modulo</button>
</form>
