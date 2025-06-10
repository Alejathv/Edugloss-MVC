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
$cursoCtrl->eliminarCurso(); 
$moduloCtrl->eliminarModulo();
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
        <a href="TablasCM.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
        <a href="Contenido.php"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="estudiantes.html"><i class="fas fa-user-graduate"></i><span>Estudiantes</span></a>
    </nav>
</div>

<h2 class="heading">Cursos Registrados</h2>

<div class="tabla-contenedor">
    <table border="1" cellpadding="10" cellspacing="0" class="tablacurso">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Fechas</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?= htmlspecialchars($curso['nombre']) ?></td>
                    <td><?= htmlspecialchars($curso['descripcion']) ?></td>
                    <td>$<?= number_format($curso['precio'], 2) ?></td>
                    <td><?= $curso['fecha_inicio'] ?> - <?= $curso['fecha_fin'] ?></td>
                    <td><?= ucfirst($curso['estado']) ?></td>
                    <td>
                        <div class="acciones">
                            <form method="GET" action="CursoModulo.php"class="boton-estilo">
                                <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                                <button type="submit"><i class="fa-solid fa-plus"></i></button>
                            </form>
                            <a href="editar_curso.php?id=<?= $curso['id_curso'] ?>" class="boton-estilo"><i class="fas fa-edit"></i></a>

                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este curso y sus módulos?')" class="boton-estilo">
                                <input type="hidden" name="accion" value="eliminar_curso">
                                <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                                <button type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<br><br>

<h2 class="heading">Módulos por Curso</h2>
<div class="tabla-contenedor">
    <table border="1" cellpadding="10" cellspacing="0" class="tablamodulo">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Módulo</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso): ?>
                <?php
                $modulosCurso = $moduloCtrl->obtenerModulosPorCurso($curso['id_curso']);
                foreach ($modulosCurso as $modulo):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['nombre']) ?></td>
                        <td><?= htmlspecialchars($modulo['nombre']) ?></td>
                        <td><?= htmlspecialchars($modulo['descripcion']) ?></td>
                        <td>$<?= number_format($modulo['precio'], 2) ?></td>
                        <td><?= ucfirst($modulo['estado']) ?></td>
                        <td>
                            <div class="acciones">
                               <form method="GET" action="CursoModulo.php"class="boton-estilo">
                                <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">
                                <button type="submit"><i class="fa-solid fa-plus"></i></button>
                            </form>

                            <a href="editar_modulo.php?id=<?= $modulo['id_modulo'] ?>"class="boton-estilo"><i class="fas fa-edit"></i></a>

                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este módulo?')"class="boton-estilo">
                                <input type="hidden" name="accion" value="eliminar_modulo">
                                <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">
                                <button type="submit"><i class="fas fa-trash"></i></button>
                            </form> 
                            </div>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


</section>

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>