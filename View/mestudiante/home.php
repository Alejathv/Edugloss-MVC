<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "../../Model/database.php";
require_once '../../Controller/EstudianteController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new EstudianteController($db);

$redireccionCurso = $controller->mostrarInscripciones();
$hayInscripciones = !empty($redireccionCurso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inicio</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
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
   <div id="close-btn"><i class="fas fa-times"></i></div>
   <div class="profile">
      <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
      <p class="role">estudiante</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>
   <nav class="navbar">
   <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
   <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
   <a href="<?= htmlspecialchars($redireccionCurso) ?>"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
    <a href="subir-evidencia.php"><i class="fas fa-tasks"></i><span>Tareas</span></a>
   </nav> 
</div>

<div class="estudiante-container">
   <div class="bienvenida-estudiante">
      <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h2>
      <p>Revisa tus cursos, docentes y temas desde este panel</p>
   </div>

   <?php if ($hayInscripciones): ?>
      <div class="cursos-grid">
         <a href="<?= htmlspecialchars($redireccionCurso) ?>" class="card-estudiante">
            <div class="icono-estudiante"><i class="fas fa-book-open"></i></div>
            <h3>Mis Cursos</h3>
            <p>Accede a tus clases y contenidos</p>
         </a>
         <a href="../ForoGeneral.php" class="card-estudiante">
            <div class="icono-estudiante"><i class="fas fa-comments"></i></div>
            <h3>Foro General</h3>
            <p>Haz preguntas o comenta tus dudas</p>
         </a>
      </div>
   <?php endif; ?>
   
   <div class="estadisticas-rapidas">
      <div class="card-estadistica">
         <h4>Temas Populares</h4>
         <p class="numero-estadistica">4</p>
      </div>
   </div>
</div>


<footer class="footer">
   &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
