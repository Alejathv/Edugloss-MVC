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
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_curso') {
        $cursoCtrl->eliminarCurso();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_modulo') {
        $moduloCtrl->eliminarModulo();
}

$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();
$idCursoPreseleccionado = isset($_GET['id_curso']) ? $_GET['id_curso'] : '';
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
   
   <?php if (isset($_SESSION['rol_nombre'])) { ?>
      <?php if ($_SESSION['rol_nombre'] == 'estudiante') { ?>
         <!-- Enlaces para estudiantes -->
         <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
         <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'docente') { ?>
         <!-- Enlaces para docentes -->
         <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
         <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
         <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'administrador') { ?>
         <!-- Enlaces para administradores -->
         <a href="gestion_usuarios.php"><i class="fas fa-user-cog"></i><span>Gestión de Usuarios</span></a>
         <a href="gestion_cursos_admin.php"><i class="fas fa-book"></i><span>Gestión de Cursos</span></a>
         <a href="reportes.php"><i class="fas fa-chart-bar"></i><span>Reportes</span></a>
      <?php } ?>
   <?php } else { ?>
      <!-- Enlace para invitados/no logueados -->
      <a href="login.php"><i class="fas fa-sign-in-alt"></i><span>Iniciar Sesión</span></a>
   <?php } ?>

</nav>
</div>

<!--SECIÓN DE CREAR CURSO Y MODULO-->

<section class="main-content">
    <h2 class="heading">Gestión de Cursos y Módulos</h2>

    <div class="formulario-grid">
        <!-- Formulario de Curso -->
        <div class="formulario-contenedor">
            <div class="formulario-titulo">Información del Curso</div>

            <form method="POST" action="" enctype="multipart/form-data" class="formulario-cuerpo">
                <input type="hidden" name="accion" value="crear_curso">

                <input name="nombre" placeholder="Nombre del curso" required>
                <input name="descripcion" placeholder="Descripción" required>
                <input name="precio" type="number" step="0.01" placeholder="Precio" required>
                <input name="fecha_inicio" type="date" required>
                <input name="fecha_fin" type="date" required>

                <select name="estado" required>
                    <option value="disponible">Disponible</option>
                    <option value="cerrado">Cerrado</option>
                </select>

                <button type="submit" name="crear_curso">Crear</button>
                <a href="TablasCM.php" class="btn-volver">Volver</a>
            </form>
        </div>

        <!-- Formulario de Módulo -->
        <div class="formulario-contenedor">
            <div class="formulario-titulo">Información del Módulo</div>

            <form method="POST" action="" enctype="multipart/form-data" class="formulario-cuerpo">
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
                <select name="estado" required>
                    <option value="disponible">Disponible</option>
                    <option value="cerrado">Cerrado</option>
                </select>

                <button type="submit" name="crear_modulo">Crear</button>
                <a href="TablasCM.php" class="btn-volver">Volver</a>
            </form>

        </div>
    </div>
</section>


<!--SECIÓN DE CREAR CURSO Y MODULO-->

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>

