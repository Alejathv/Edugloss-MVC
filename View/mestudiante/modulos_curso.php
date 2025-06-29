<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once "../../Model/ModuloModel.php";
    require_once "../../Model/database.php";
   require_once '../../Controller/EstudianteController.php';


    $database = new Database();
    $conn = $database->getConnection();
    $moduloModel = new ModuloModel($conn);
    $controller = new EstudianteController($conn);

    $redireccionCurso = $controller->mostrarInscripciones();
   $hayInscripciones = !empty($redireccionCurso);
    $idCurso = isset($_GET['id_curso']) ? (int)$_GET['id_curso'] : null;
    $modulos = [];

    if ($idCurso) {
        $modulos = $moduloModel->obtenerModulosPorCurso($idCurso);
    } else {
        echo "<p>Error: no se especificó un curso.</p>";
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
   <style>
   .icons .fas,
.icons a.fas {
   font-size: 2rem;
   color: #333;
   cursor: pointer;
   margin-left: 1rem;
   transition: color 0.3s;
}

.icons .fas:hover,
.icons a.fas:hover {
   color: #9b9b9b;
}

</style>

</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.php" class="logo">
         <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
      </a>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
          <a href="../../documentos/Manual de uso estudiante.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
      </div>
      <div class="profile">
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
         <p class="role">Estudiante</p>
         <a href="../perfil.php" class="btn">ver perfil</a>
         <div class="flex-btn">
            <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
         </div>
      </div>
   </section>
</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">estudiante</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">
   <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
   <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
   
      <?php if (isset($_SESSION['rol_nombre'])) { ?>
         <?php if ($_SESSION['rol_nombre'] == 'estudiante') { ?>
            <!-- Enlaces para estudiantes -->
            <a href="<?= htmlspecialchars($redireccionCurso) ?>"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
         
         <?php } elseif ($_SESSION['rol_nombre'] == 'docente') { ?>
            <!-- Enlaces para docentes -->
            <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
            <a href="./Contenido.php"><i class="fas fa-edit"></i><span>Contenido</span></a>
            <a href="./evidencias.php"><i class="fas fa-users"></i><span>Evidencias</span></a>
         
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
   
   <!-- Enlace común para todos -->
    <a href="subir-evidencia.php"><i class="fas fa-tasks"></i><span>Tareas</span></a>
</nav>

</div>

<section class="playlist-videos">
    <h1 style="text-align:center;">Módulos del Curso</h1>
    <?php if (empty($modulos)): ?>
        <p style="text-align:center;">No hay módulos disponibles para este curso.</p>
    <?php else: ?>
        <div class="modulos-container">
            <?php foreach ($modulos as $modulo): ?>
                <div class="card-modulo" onclick="abrirMaterial(<?= $modulo['id_modulo'] ?>)">
                    <div class="icono"><i class="fas fa-book-open"></i></div>
                    <div class="contenido">
                        <h3><?= htmlspecialchars($modulo['nombre']) ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>



<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script>
    function abrirMaterial(id_modulo) {
        // Abre en la misma pestaña
        window.location.href = `ver_materiales.php?id_modulo=${id_modulo}`;
    }
</script>


   
</body>
</html>