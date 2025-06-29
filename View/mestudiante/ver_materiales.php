<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// playlist.php
require_once "../../Model/database.php";
require_once "../../Model/MaterialModel.php";
 require_once "../../Model/ModuloModel.php";

 $id_modulo = isset($_GET['id_modulo']) ? intval($_GET['id_modulo']) : null;

if (!$id_modulo) {
    die("Error: ID de módulo no especificado.");
}

$db = new Database();
$conn = $db->getConnection();
$materialModel = new MaterialModel($conn);
$moduloModel = new ModuloModel($conn);

$modulo = $moduloModel->obtenerModuloPorId($id_modulo);
$materiales = $materialModel->obtenerMaterialPorModulo($id_modulo); // Usamos tu función exacta
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
   <a href="subir-evidencia.php"><i class="fas fa-tasks"></i><span>Tareas</span></a>
   
</nav>

</div>

<section class="playlist-videos">
   <h1><?= htmlspecialchars($modulo['nombre']) ?></h1>
   <div class="box-container">
      <?php if (empty($materiales)): ?>
         <p>No hay materiales disponibles para este módulo.</p>
      <?php else: ?>
         <div class="box-container">
            <?php foreach ($materiales as $material): ?>
                  <a class="box" href="mostrar.php?id=<?= urlencode($material['id_material']) ?>" >
                     <?php if ($material['tipo'] === 'video'): ?>
                        <i class="fas fa-play"></i>
                        <img class="rotar-preview" src="<?= (function() { $imagenes = glob("../img/videos/*.jpg"); return $imagenes ? $imagenes[array_rand($imagenes)] : '../img/videos/default.jpg'; })() ?>" alt="Vista previa aleatoria"> 
                     <?php elseif ($material['tipo'] === 'pdf'): ?>
                        <i class="fas fa-file"></i>
                        <img class="rotar-preview" src="<?= (function() { $imagenes = glob("../img/pdf/*.jpg"); return $imagenes ? $imagenes[array_rand($imagenes)] : '../img/videos/default.jpg'; })() ?>" alt="Vista previa aleatoria">
                     <?php else: ?>
                        <img src="images/post-unknown.png" alt="Material">
                     <?php endif; ?>
                     <h3><?= htmlspecialchars($material['nombre']) ?></h3>
                  </a>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
</div>
   <div style="text-align: right; margin: 10px;">
      <a href="subir-evidencia.php" class="btn evidencia" style="background-color: #87A2FB; color: white; font-weight: bold;">Subir Evidencia</a>
   </div>

</section>


<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>


   
</body>
</html>

