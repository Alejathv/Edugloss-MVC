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
            <img src="../img/pic-1.jpg" class="image" alt="">
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
        <img src="../img/pic-1.jpg" class="image" alt="">
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="home.html"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="courses.html"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
        <a href="subir_material.php"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="estudiantes.html"><i class="fas fa-user-graduate"></i><span>Estudiantes</span></a>
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
    $modulosConCursos = $moduloCtrl->obtenerModulosConCursos(); // Necesitarás implementar este método
    
    foreach ($modulosConCursos as $modulo) {
        echo '<div class="module-section">';
        echo '<h3>Curso: ' . htmlspecialchars($modulo['curso_nombre']) . ' - Módulo: ' . htmlspecialchars($modulo['modulo_nombre']) . '</h3>';
        
        $materiales = $controller->listarMateriales($modulo['id_modulo']);
        
        if (!empty($materiales)) {
            echo '<table class="material-table">';
            echo '<thead><tr><th>Nombre</th><th>Tipo</th><th>Acción</th></tr></thead>';
            echo '<tbody>';
            
            foreach ($materiales as $material) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($material['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($material['tipo']) . '</td>';
                echo '<td>';
                
                if ($material['tipo'] === 'video') {
                    // Extraer ID de YouTube si es una URL de YouTube
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $material['url_material'], $matches)) {
                        $videoId = $matches[1];
                        echo '<button class="preview-btn" onclick="showVideoPreview(\'' . $videoId . '\')">Previsualizar</button>';
                    }
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="view-link">Ver en YouTube</a>';
                } else { // PDF
                    echo '<a href="' . htmlspecialchars($material['url_material']) . '" target="_blank" class="view-link">Ver PDF</a>';
                }
                
                echo '</td>';
                echo '</tr>';
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