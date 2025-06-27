<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../../Controller/CursoModuloController.php';
$db = new Database();
$conn = $db->getConnection();

$cursoCtrl = new CursoController($conn);

if (!isset($_GET['id'])) {
    echo "Curso no especificado.";
    exit;
}

$idCurso = $_GET['id'];
$curso = $cursoCtrl->obtenerCursoPorId($idCurso);

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cursoCtrl->actualizarCurso($_POST);
    echo "<script>window.parent.location.href='TablasCM.php?editado=curso';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Editar Curso</title>
   <link rel="stylesheet" href="../css/style_panel.css">

</head>
<body>

<section class="formulario-edicion">
  <h2>Editar Curso</h2>
  <form method="POST">
    <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($curso['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($curso['descripcion']) ?></textarea>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($curso['precio']) ?>" required>

    <label>Fecha Inicio:</label>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($curso['fecha_inicio'] ?? '') ?>" required>

    <label>Fecha Fin:</label>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($curso['fecha_fin'] ?? '') ?>" required>

    <label>Estado:</label>
    <select name="estado">
      <option value="Disponible" <?= ($curso['estado'] ?? '') == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
      <option value="Cerrado" <?= ($curso['estado'] ?? '') == 'Cerrado' ? 'selected' : '' ?>>Cerrado</option>
    </select>

    <button type="submit">Actualizar Curso</button>
  </form>
</section>

</body>
</html>
