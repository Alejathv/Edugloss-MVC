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
    header("Location: TablasCM.php?editado=curso");
    exit;
}
?>

<h2>Editar Curso</h2>
<form method="POST">
    <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($curso['nombre']) ?>" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($curso['descripcion']) ?></textarea><br>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($curso['precio']) ?>" required><br>

    <label>Fecha Inicio:</label>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($curso['fecha_inicio'] )?>" required><br>

    <label>Fecha Fin:</label>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($curso['fecha_fin'] )?>" required><br>

    <label>Estado:</label>
    <select name="estado">
        <option value="disponible" <?= htmlspecialchars($curso['estado'] == 'disponible' ? 'selected' : '') ?>>Disponible</option>
        <option value="cerrado" <?= htmlspecialchars($curso['estado'] == 'cerrado' ? 'selected' : '') ?>>Cerrado</option>
    </select><br>

    <button type="submit">Actualizar Curso</button>
</form>
