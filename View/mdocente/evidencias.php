<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "../../Model/database.php";

if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}

$database = new Database();
$conn = $database->getConnection();

// Obtener cursos
$cursos = $conn->query("SELECT id_curso, nombre FROM curso")->fetch_all(MYSQLI_ASSOC);

// Si se selecciona curso, obtener sus módulos
$modulos = [];
if (isset($_GET['curso']) && is_numeric($_GET['curso'])) {
    $idCursoSeleccionado = intval($_GET['curso']);
    $stmt = $conn->prepare("SELECT id_modulo, nombre FROM modulo WHERE id_curso = ?");
    $stmt->bind_param("i", $idCursoSeleccionado);
    $stmt->execute();
    $resultModulos = $stmt->get_result();
    $modulos = $resultModulos->fetch_all(MYSQLI_ASSOC);
}

// Filtros
$cursoFiltro = isset($_GET['curso']) && is_numeric($_GET['curso']) ? intval($_GET['curso']) : null;
$moduloFiltro = isset($_GET['modulo']) && is_numeric($_GET['modulo']) ? intval($_GET['modulo']) : null;

$query = "SELECT e.*, u.nombre AS estudiante 
          FROM evidencias e 
          JOIN usuarios u ON e.id_usuario = u.id_usuario";

$filtros = [];
if ($cursoFiltro) $filtros[] = "e.id_curso = $cursoFiltro";
if ($moduloFiltro) $filtros[] = "e.id_modulo = $moduloFiltro";
if (!empty($filtros)) {
    $query .= " WHERE " . implode(" AND ", $filtros);
}
$result = $conn->query($query);
$evidencias = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-size: 18px; } </style>
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/icon1.png" class="image" alt="Foto de perfil">
            <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
            <p class="role">Docente</p>
            <a href="../perfil.php" class="btn">ver perfil</a>
            <div class="flex-btn">
                <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/icon1.png" class="image" alt="Foto de perfil">
        <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="./docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestión De Aprendizaje</span></a>
        <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
        <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
    </nav>
</div>

<section class="main-content evidencias">
    <div class="dashboard contenedor-centro">
        <div class="tabla-evidencias">
            <h2>Evidencias De Estudiantes</h2>
            <!-- Filtro estilizado -->
            <form method="GET" class="filtro-evidencias">
                <div class="campo-filtro">
                    <label for="curso">Curso:</label>
                    <select name="curso" id="curso" onchange="this.form.submit()">
                        <option value="">-- Todos --</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id_curso'] ?>" <?= $cursoFiltro == $c['id_curso'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo-filtro">
                    <label for="modulo">Módulo:</label>
                    <select name="modulo" id="modulo" onchange="this.form.submit()">
                        <option value="">-- Todos --</option>
                        <?php foreach ($modulos as $m): ?>
                            <option value="<?= $m['id_modulo'] ?>" <?= $moduloFiltro == $m['id_modulo'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="tablaevidencia">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Módulo</th>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evidencias as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['estudiante']) ?></td>
                            <td><?= htmlspecialchars($e['id_curso']) ?></td>
                            <td><?= htmlspecialchars($e['id_modulo']) ?></td>
                            <td>
                                <?php 
                                $rutaFisica = __DIR__ . '/../../documentos/' . $e['url_archivo'];
                                if (!empty($e['url_archivo'])):
                                    $ext = strtolower(pathinfo($e['url_archivo'], PATHINFO_EXTENSION));
                                    $rutaArchivo = "/EDUGLOSS-MVC/documentos/" . rawurlencode($e['url_archivo']);
                                    if (file_exists($rutaFisica)):
                                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <a href="<?= $rutaArchivo ?>" target="_blank">
                                                <img src="<?= $rutaArchivo ?>" alt="Evidencia">
                                            </a>
                                        <?php elseif ($ext === 'pdf'): ?>
                                            <a href="<?= $rutaArchivo ?>" target="_blank">
                                                <img src="../assets/pdf-icon.png" alt="PDF" style="max-width:50px;">
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $rutaArchivo ?>" target="_blank">Descargar archivo</a>
                                        <?php endif;
                                    else: ?>
                                        Archivo no disponible
                                    <?php endif;
                                else: ?>
                                    No hay archivo
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($e['fecha_subida']) ?></td>
                            <td>
                                <span class="estado-<?= htmlspecialchars(strtolower($e['estado'])) ?>">
                                    <?= ucfirst($e['estado'] ?? 'pendiente') ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="../../Controller/EvidenciaController.php<?= $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" style="display: flex; gap: 8px; justify-content: center;">
                                    <input type="hidden" name="id_evidencia" value="<?= $e['id_evidencia'] ?>">
                                    <button type="submit" name="estado" value="aprobado" class="btn-icon aprobar" title="Aprobar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="submit" name="estado" value="reprobado" class="btn-icon reprobar" title="Reprobar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</section>
<footer class="footer">
   &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
