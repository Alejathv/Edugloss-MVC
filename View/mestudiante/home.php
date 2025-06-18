<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}
require_once "../../Model/database.php";
require_once '../../Controller/EstudianteController.php';
$database = new Database();
$db = $database->getConnection();

$controller = new EstudianteController($db);


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
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
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
         <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
         <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'docente') { ?>
         <!-- Enlaces para docentes -->
         <a href="gestion_cursos.php"><i class="fas fa-book"></i><span>Mis Cursos</span></a>
         <a href="crear_contenido.php"><i class="fas fa-edit"></i><span>Crear Contenido</span></a>
         <a href="estudiantes_inscritos.php"><i class="fas fa-users"></i><span>Estudiantes</span></a>
      
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
   <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
</nav>

</div>

<section class="home-grid">

   <h1 class="heading">Acceso Principal </h1>

   <div class="box-container">

      <div class="box">
         <h3 class="title">Tus Cursos</h3>
         <div class="flex">
            <?php echo $controller->mostrarInscripciones(); ?>
            
         </div>
      </div>

      <div class="box">
         <h3 class="title">Temas Populares</h3>
         <div class="flex">
            <a href="#"><i class="fa-solid fa-book-open-reader"></i><span>Uñas en Gel: Técnicas Profesionales</span></a>
            <a href="#"><i class="fa-solid fa-book-open-reader"></i><span>Manicura y Pedicura Spa</span></a>
            <a href="#"><i class="fa-solid fa-book-open-reader"></i><span>Cuidado y Reparación de Uñas: Consejos para un Saludable Crecimiento</span></a>
            <a href="#"><i class="fa-solid fa-book-open-reader"></i><span>Principales Técnicas</span></a>
         </div>
      </div>
   </div>
</section>

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>