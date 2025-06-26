<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../../Model/database.php";
require_once "../../Controller/DocenteController.php";
require_once '../../Controller/CursoModuloController.php';
require_once '../../Model/database.php';

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);
$cursoCtrl = new CursoController($conn);
$moduloCtrl = new ModuloController($conn);
$docenteCtrl = new DocenteController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear_curso') {
        $cursoCtrl->crearCurso();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'crear_modulo') {
        $moduloCtrl->crearModulo();
    }
    $resultado = $controller->subir();
    $_SESSION['message'] = $resultado['message'];
    $_SESSION['success'] = $resultado['success'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_SESSION['message'])) {
    echo '<p style="color: ' . ($_SESSION['success'] ? 'green' : 'red') . ';">' . htmlspecialchars($_SESSION['message']) . '</p>';
    unset($_SESSION['message'], $_SESSION['success']);
}

$controller->subir();
$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();

$id_modulo = $_GET['id_modulo'] ?? 0;
$materiales = $controller->listarMateriales($id_modulo);

if (isset($_GET['m']) && $_GET['m'] == 'subir') {
    $controller->subir();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style_panel.css">
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="docente_panel.php" class="logo"><img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;"></a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
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
        <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
        <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="./docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion de Aprendizaje</span></a>
        <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
        <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
    </nav>
</div>

<section class="home-grid">
    <div class="form-material">
        <h2>Subir por Módulo</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="id_modulo">Módulo:</label>
            <select name="id_modulo" id="id_modulo" required>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?= $modulo['id_modulo'] ?>"><?= $modulo['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
            <label for="nombre">Nombre del material:</label>
            <input type="text" name="nombre" id="nombre" required>
            <label for="url">URL del material:</label>
            <input type="url" name="url" id="url" required>
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
            </select>
            <button type="submit">Subir</button>
        </form>
    </div>
</section>

<section class="materials-table">
    <h2>Materiales Disponibles</h2>
    <?php
    $modulosConCursos = $moduloCtrl->obtenerModulosConCursos();
    foreach ($modulosConCursos as $modulo) {
        echo '<div class="module-section">';
        echo '<h3>Curso: ' . htmlspecialchars($modulo['curso_nombre']) . ' - Módulo: ' . htmlspecialchars($modulo['modulo_nombre']) . '</h3>';
        $materiales = $controller->listarMateriales($modulo['id_modulo']);
        if (!empty($materiales)) {
            echo '<table class="material-table">';
            echo '<thead><tr><th>Nombre</th><th>Tipo</th><th>Acceso</th><th>Acciones</th></tr></thead>';
            echo '<tbody>';
            foreach ($materiales as $material) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($material['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($material['tipo']) . '</td>';
                echo '<td>';
                if ($material['tipo'] === 'video') {
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $material['url_material'], $matches)) {
                        $videoId = $matches[1];
                        echo '<button class="btn-preview" onclick="showVideoPreview(\'' . $videoId . '\')">Previsualizar</button>';
                    }
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="btn-ver">Ver en YouTube</a>';
                } else {
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="btn-ver">Ver PDF</a>';
                }
                echo '</td>';
                echo '<td class="acciones-material">';
                echo '<form method="GET" action="editar_material.php" style="display:inline-block;">';
                echo '<input type="hidden" name="id" value="' . $material['id_material'] . '">';
                echo '<button type="submit" class="btn-editar" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                echo '</form>';
                echo '<form method="POST" action="eliminar_material.php" style="display:inline-block;" onsubmit="return confirm(\'\u00bfEst\u00e1s seguro de eliminar este material?\')">';
                echo '<input type="hidden" name="id_material" value="' . $material['id_material'] . '">';
                echo '<button type="submit" class="btn-eliminar" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No hay materiales disponibles para este módulo.</p>';
        }
        echo '</div>';
    }
    ?>
</section>

<style>
.acciones-material {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}
.btn-editar, .btn-eliminar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    font-size: 14px;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
    text-decoration: none;
    height: 36px;
    line-height: 1;
}
.btn-editar {
    background: linear-gradient(135deg, #4a6bff 0%, #6a5acd 100%);
    border: 1px solid rgba(255,255,255,0.1);
}
.btn-editar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(74, 107, 255, 0.2);
}
.btn-eliminar {
    background: linear-gradient(135deg, #ff4a4a 0%, #d23369 100%);
    border: 1px solid rgba(255,255,255,0.1);
}
.btn-eliminar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 74, 74, 0.2);
}
.btn-preview {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
    border: 1px solid rgba(255,255,255,0.1);
    padding: 7px;
    border-radius: 5px;
}
.btn-ver {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border: 1px solid rgba(255,255,255,0.1);
    padding: 6px 12px;
    border-radius: 6px;
    margin-left: 5px;
    display: inline-block;
    text-align: center;
    font-size: 14px;
    text-decoration: none;
}
</style>

<script>
function showVideoPreview(videoId) {
    const modal = document.getElementById('videoModal');
    const videoContainer = document.getElementById('videoContainer');
    videoContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allowfullscreen></iframe>`;
    modal.style.display = 'block';
}
function closeVideoModal() {
    document.getElementById('videoModal').style.display = 'none';
    document.getElementById('videoContainer').innerHTML = '';
}
window.onclick = function(event) {
    const modal = document.getElementById('videoModal');
    if (event.target == modal) {
        closeVideoModal();
    }
}
</script>
<div id="videoModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeVideoModal()">&times;</span>
        <div id="videoContainer"></div>
    </div>
</div>
<footer class="footer">
    &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>
<script src="../js/script.js"></script>
</body>
</html>
