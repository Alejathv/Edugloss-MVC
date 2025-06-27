<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../../Controller/CursoModuloController.php';
$db = new Database();
$conn = $db->getConnection();

$moduloCtrl = new ModuloController($conn);

if (!isset($_GET['id'])) {
    echo "Módulo no especificado.";
    exit;
}

$id_modulo = $_GET['id'];
$modulo = $moduloCtrl->obtenerModuloPorId($id_modulo);

if (!$modulo) {
    echo "Módulo no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduloCtrl->actualizarModulo($_POST);
    echo "<script>window.parent.location.href = 'TablasCM.php?editado=modulo';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Editar Módulo</title>
   <link rel="stylesheet" href="../css/style_panel.css">

</head>
<body>

<section class="formulario-edicion">
   <h2>Editar Módulo</h2>
   <form method="POST">
      <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">

      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($modulo['nombre']) ?>" required>

      <label>Descripción:</label>
      <textarea name="descripcion" required><?= htmlspecialchars($modulo['descripcion']) ?></textarea>

      <label>Precio:</label>
      <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($modulo['precio']) ?>" required>

      <label>Estado:</label>
      <select name="estado">
         <option value="disponible" <?= ($modulo['estado'] ?? '') == 'disponible' ? 'selected' : '' ?>>Disponible</option>
         <option value="cerrado" <?= ($modulo['estado'] ?? '') == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
      </select>

      <button type="submit">Actualizar Módulo</button>
   </form>
</section>

</body>
</html>
