<?php
session_start();
require_once __DIR__ . '/../Model/CursoModel.php';
require_once __DIR__ . '/../Model/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Curso no válido");
}

$curso_id = (int) $_GET['id'];
$model = new CursoModel();
$modulos = $model->getModulosYVideosPorCurso($curso_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Curso</title>
    <link rel="stylesheet" href="css/style_panel.css">
</head>
<body>

<h2 style="text-align:center; margin-top: 2rem;">Contenido del Curso</h2>

<div style="padding: 2rem;">
    <?php if (!empty($modulos)): ?>
        <?php foreach ($modulos as $modulo): ?>
            <div style="margin-bottom: 2rem; border: 1px solid #ccc; padding: 1rem; border-radius: 10px;">
                <h3><?= htmlspecialchars($modulo['nombre_modulo']) ?></h3>
                <p><?= htmlspecialchars($modulo['descripcion']) ?></p>

                <?php if (!empty($modulo['videos'])): ?>
                    <ul style="list-style: none; padding-left: 0;">
                        <?php foreach ($modulo['videos'] as $video): ?>
                            <li style="margin-top: 0.8rem; border: 1px solid #eee; padding: 0.5rem; border-radius: 6px;">
                                <strong><?= htmlspecialchars($video['nombre_video']) ?></strong><br>
                                <a href="watch-video.php?id_video=<?= $video['id_video'] ?>" class="btn" style="margin-top: 0.4rem;">Ver Video</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><em>Este módulo aún no tiene videos.</em></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se encontraron módulos para este curso.</p>
    <?php endif; ?>
</div>

</body>
</html>
