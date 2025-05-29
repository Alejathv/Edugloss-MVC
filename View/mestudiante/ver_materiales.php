<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// playlist.php
require_once "../../Model/database.php";
require_once "../../Model/MaterialModel.php";

$db = new Database();
$conn = $db->getConnection();
$materialModel = new MaterialModel($conn);

$materiales = [];

if (isset($_GET['id_modulo'])) {
   $id_modulo = intval($_GET['id_modulo']);
   $materiales = $materialModel->getMaterialesByModulo($id_modulo);
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

      <a href="home.php" class="logo">
         <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
      </a>
      

      <form action="search.html" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="buscar cursos..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <!-- Perfil del usuario, muestra la imagen, nombre y rol -->
      <div class="profile">
         <img src="images/Estud1.jpeg" class="image" alt="imagen de estudiante">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
         <p class="role">Estudiante</p>
         <a href="profile.html" class="btn">ver perfil</a>
         <!-- Botones para iniciar sesión o registrarse -->
         <div class="flex-btn">
            <a href="login.html" class="option-btn">Entrar</a>
            <a href="register.html" class="option-btn">Registrarse</a>
         </div>
      </div>   

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/Estud1.jpeg" class="image" alt="">
      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">estudiante</p>
      <a href="profile.html" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="about.html"><i class="fas fa-question"></i><span>Nosotros</span></a>
      <a href="#"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
   </nav>

</div>

<section class="playlist-videos">

   <h1 class="heading">Lista de Reproducción</h1>

   <div class="box-container">
        <?php foreach ($materiales as $mat): ?>
      <?php if ($mat['tipo'] === 'pdf'): ?>
         <a class="box" href="ver_material.php?archivo=<?= urlencode($mat['url_video']) ?>&tipo=pdf" target="_blank">
            <i class="fa-solid fa-file-pdf"></i>
            <img src="../img/videos/<?= $mat['id_video'] ?>.png" alt="pdf">
            <h3><?= htmlspecialchars($mat['nombre']) ?></h3>
         </a>
      <?php else: ?>
         <a class="box" href="ver_material.php?archivo=<?= urlencode($mat['url_video']) ?>&tipo=video">
            <i class="fas fa-play"></i>
            <img src="../img/videos/<?= $mat['id_video'] ?>.jpg" alt="Video 1">
            <h3><?= htmlspecialchars($mat['nombre']) ?></h3>
         </a>
      <?php endif; ?>
   <?php endforeach; ?>

   </div>
   <div style="text-align: right; margin: 10px;">
      <a href="subir-evidencia.php" class="btn btn-primary">Subir Evidencia</a>
   </div>

</section>


<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>

