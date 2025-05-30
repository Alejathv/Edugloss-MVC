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

$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();

$materiales = [];
foreach ($modulos as $m) {
    $materiales[$m['id_modulo']] = $docenteCtrl->listarMateriales($m['id_modulo']);
}

$id_modulo = $_GET['id_modulo'] ?? 0;
$materiales = $controller->listarMateriales($id_modulo);
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
        <form action="search.html" method="post" class="search-form">
            <input type="text" name="search_box" required placeholder="Buscar cursos..." maxlength="100">
            <button type="submit" class="fas fa-search"></button>
        </form>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/pic-1.jpg" class="image" alt="">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
            </h3>
            <p class="role">Docente</p>
            <a href="profile.html" class="btn">Ver Perfil</a>
            <div class="flex-btn">
                <a href="../logout.php" class="option-btn">Cerrar sesión</a>
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
        <a href="profile.html" class="btn">Ver Perfil</a>
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
    <H2>CREACION DE CURSO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="crear_curso"> <!-- ESTA ES LA LÍNEA QUE FALTABA -->

    <input name="nombre" placeholder="Nombre del curso" required>
    <input name="descripcion" placeholder="Descripción" required>
    <input name="precio" type="number" step="0.01" placeholder="Precio" required>
    <input name="fecha_inicio" type="date" required>
    <input name="fecha_fin" type="date" required>
    
    <select name="estado" required>
        <option value="disponible">Disponible</option>
        <option value="cerrado">Cerrado</option>
    </select>

    <input name="imagen" placeholder="Imagen (URL o nombre)" required>

    <button type="submit" name="crear_curso">Crear Curso</button>
</form>




<H2>CREACION DE MODULO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="crear_modulo">

    <select name="id_curso" required>
        <option value="">Selecciona un curso</option>
        <?php foreach ($cursos as $curso): ?>
            <option value="<?= $curso['id_curso'] ?>"><?= $curso['nombre'] ?></option>
        <?php endforeach; ?>
    </select>

    <input name="nombre" placeholder="Nombre del módulo" required>
    <input name="descripcion" placeholder="Descripción" required>
    <input name="precio" type="number" step="0.01" placeholder="Precio" required>

    <button type="submit" name="crear_modulo">Crear Módulo</button>
</form>




<H2>Subir por MODULO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
    <select name="id_modulo" required>
        <?php foreach ($modulos as $modulo): ?>
            <option value="<?= $modulo['id_modulo'] ?>"><?= $modulo['nombre'] ?></option>
        <?php endforeach; ?>
    </select>
    <input name="nombre" placeholder="Nombre del material" required>
    <input type="file" name="archivo" required>
    <button type="submit">Subir</button>
</form>



<h3>Contenido:</h3>
<?php foreach ($cursos as $curso): ?>
    <h3>Curso: <?= htmlspecialchars($curso['nombre']) ?></h3>

    <?php
    // Obtener módulos de este curso
    $modulosDelCurso = array_filter($modulos, function($m) use ($curso) {
        return $m['id_curso'] == $curso['id_curso'];
    });
    ?>

    <?php foreach ($modulosDelCurso as $modulo): ?>
        <h4>Módulo: <?= htmlspecialchars($modulo['nombre']) ?></h4>
        <ul>
            <?php
            $matDelModulo = $materiales[$modulo['id_modulo']] ?? [];
            foreach ($matDelModulo as $material):
            ?>
                <li>
                    <?= htmlspecialchars($material['nombre']) ?> |
                    <a href="<?= htmlspecialchars($material['url_video']) ?>" target="_blank">Ver</a>
                    (<?= strtoupper(htmlspecialchars($material['tipo'])) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

<?php endforeach; ?>
</section>

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>

