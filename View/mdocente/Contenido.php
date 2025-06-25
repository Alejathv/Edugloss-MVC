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

// Procesar formularios si hay envío POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear_curso') {
        $cursoCtrl->crearCurso();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'crear_modulo') {
        $moduloCtrl->crearModulo();
    }
}
// Si llega POST, procesa la subida
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->subir();

    // Guardamos mensaje en sesión para evitar reenvío al refrescar
    $_SESSION['message'] = $resultado['message'];
    $_SESSION['success'] = $resultado['success'];

    // Redireccionamos a la misma página para limpiar POST (PRG)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// Aquí mostramos mensajes almacenados en sesión (si hay)
if (isset($_SESSION['message'])) {
    if ($_SESSION['success']) {
        echo '<p style="color: green;">' . htmlspecialchars($_SESSION['message']) . '</p>';
    } else {
        echo '<p style="color: red;">' . htmlspecialchars($_SESSION['message']) . '</p>';
    }
    unset($_SESSION['message'], $_SESSION['success']);
}
$controller->subir();
$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();

$materiales = [];
foreach ($modulos as $m) {
    $materiales[$m['id_modulo']] = $docenteCtrl->listarMateriales($m['id_modulo']);
}

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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inicio</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style_panel.css">

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
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
            </h3>
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
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
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
    <h2><strong>Subir por Módulo</strong></h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="id_modulo">Módulo:</label>
        <select name="id_modulo" id="id_modulo" required>
            <?php foreach ($modulos as $modulo): ?>
                <option value="<?= $modulo['id_modulo'] ?>"><?= $modulo['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="nombre">Nombre del material:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre del material" required>

        <label for="url">URL del material:</label>
        <input type="url" name="url" id="url" placeholder="URL del material (YouTube o PDF)" required>

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
    <h2><strong>Materiales Disponibles</strong></h2>
    
    <?php
    // Obtener todos los módulos con sus cursos y materiales
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
                        echo '<button class="preview-btn" onclick="showVideoPreview(\'' . $videoId . '\')">Previsualizar</button>';
                    }
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="view-link">Ver en YouTube</a>';
                } else { // PDF
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="view-link">Ver PDF</a>';
                }
                
echo '<td class="acciones-material">';

// Botón de editar
echo '<a href="editar_material.php?id=' . $material['id_material'] . '" class="btn-accion btn-editar"><i class="fas fa-pencil-alt"></i> Editar</a>';

// Botón de eliminar
echo '<form method="POST" action="eliminar_material.php" style="display:inline;" onsubmit="return confirm(\'¿Estás seguro de eliminar este material?\')">';
echo '<input type="hidden" name="id_material" value="' . $material['id_material'] . '">';
echo '<button type="submit" class="btn-accion btn-eliminar"><i class="far fa-trash-alt"></i></button>';
echo '</form>';

echo '</td>';
            }
            
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No hay materiales disponibles para este módulo.</p>';
        }
        
        echo '</div>';
    }
    ?>
</section>

<style>
    /* Estilos premium para los botones de acción */
    .acciones-material {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-accion {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        cursor: pointer;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        position: relative;
        overflow: hidden;
    }
    
    .btn-accion::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .btn-accion:hover::after {
        opacity: 1;
    }
    
    .btn-accion i {
        font-size: 13px;
    }
    
    /* Botón Editar - Estilo premium */
    .btn-editar {
        background: linear-gradient(135deg, #4a6bff 0%, #6a5acd 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .btn-editar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(74, 107, 255, 0.2);
    }
    
    /* Botón Eliminar - Estilo premium */
    .btn-eliminar {
        background: linear-gradient(135deg, #ff4a4a 0%, #d23369 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .btn-eliminar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 74, 74, 0.2);
    }
    
    /* Botón Previsualizar - Estilo premium */
    .btn-preview {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .btn-preview:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }
    
    /* Botón Ver - Estilo premium */
    .btn-ver {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .btn-ver:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
    }
    
    /* Efecto de click */
    .btn-accion:active {
        transform: translateY(1px);
    }
</style>

<!-- Modal para previsualización de videos -->
<div id="videoModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeVideoModal()">&times;</span>
        <div id="videoContainer"></div>
    </div>
</div>

<script>
    function showVideoPreview(videoId) {
        const modal = document.getElementById('videoModal');
        const videoContainer = document.getElementById('videoContainer');
        
        videoContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        
        modal.style.display = 'block';
    }
    
    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const videoContainer = document.getElementById('videoContainer');
        
        videoContainer.innerHTML = '';
        modal.style.display = 'none';
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    window.onclick = function(event) {
        const modal = document.getElementById('videoModal');
        if (event.target == modal) {
            closeVideoModal();
        }
    }
</script>
<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>