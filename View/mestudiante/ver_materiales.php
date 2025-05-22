<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../Model/database.php";
require_once "../../Model/MaterialModel.php";

$db = new Database();
$conn = $db->getConnection();
$materialModel = new MaterialModel($conn);

$materiales = [];

if (isset($_GET['id_modulo'])) {
    // Ver materiales de un solo módulo
    $id_modulo = intval($_GET['id_modulo']);
    $materiales = $materialModel->getMaterialesByModulo($id_modulo);

} elseif (isset($_GET['id_curso'])) {
    // Ver materiales de todos los módulos de un curso
    $id_curso = intval($_GET['id_curso']);
    $stmt = $conn->prepare("SELECT id_modulo FROM modulo WHERE id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $res = $stmt->get_result();
    
    while ($row = $res->fetch_assoc()) {
        $materiales_modulo = $materialModel->getMaterialesByModulo($row['id_modulo']);
        $materiales = array_merge($materiales, $materiales_modulo);
    }
}
?>

<h2>Materiales disponibles</h2>
<ul>
<?php foreach ($materiales as $mat): ?>
    <li>
        <strong><?= htmlspecialchars($mat['nombre']) ?></strong><br>
        <?php if ($mat['tipo'] === 'pdf'): ?>
            <a href="../../<?= $mat['url_video'] ?>" target="_blank">📄 Ver PDF</a>
        <?php else: ?>
            <video width="320" controls>
                <source src="../../<?= $mat['url_video'] ?>" type="video/mp4">
                Tu navegador no soporta el video.
            </video>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
