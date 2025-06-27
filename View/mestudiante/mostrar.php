<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
require_once "../../Model/ModuloModel.php";
    require_once "../../Model/database.php";

// Validar si se pasó un ID por GET
if (!isset($_GET['id'])) {
    echo "ID de material no especificado.";
    exit;
}

$id = intval($_GET['id']);
$database = new Database();
$conn = $database->getConnection();

// Obtener el material desde la base de datos
$sql = "SELECT nombre, tipo, url_material, id_modulo FROM material WHERE id_material = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Material no encontrado.";
    exit;
}

$material = $result->fetch_assoc();

$id_modulo = $material['id_modulo'];

// FUNCIONES PARA TRANSFORMAR LAS URLS
//
function transformarYoutube($url) {
    return str_replace("watch?v=", "embed/", $url);
}

function transformarDrive($url) {
    if (preg_match('#https://drive\.google\.com/file/d/([^/]+)/#', $url, $matches)) {
        return "https://drive.google.com/file/d/{$matches[1]}/preview";
    }
    return $url;
}

// Transformar la URL según el tipo
$url = $material['url_material'];

if ($material['tipo'] === 'video') {
    $url = transformarYoutube($url);
} elseif ($material['tipo'] === 'pdf') {
    $url = transformarDrive($url);
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
     iframe, embed {
    width: 100%;
    height: 50vh;
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    margin: 0 auto; /* centra horizontalmente */
    display: block; /* necesario para que funcione margin: auto */
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
      </div>

      <!-- Perfil del usuario, muestra la imagen, nombre y rol -->
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

<section class="watch-video">
    
    <div class="video-container">
        <div class="video">
         <h1 "><?= htmlspecialchars($material['nombre']) ?></h1>
            <?php if ($material['tipo'] === 'video'): ?>
                <iframe 
                    src="<?= htmlspecialchars($url) ?>" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            <?php elseif ($material['tipo'] === 'pdf'): ?>
                <iframe 
                    src="<?= htmlspecialchars($url) ?>" 
                    type="application/pdf">
                </iframe>
                <p>Si no puedes ver el documento, <a href="<?= htmlspecialchars($url) ?>" target="_blank">haz clic aquí para verlo</a>.</p>
            <?php else: ?>
                <p>Tipo de material no compatible.</p>
            <?php endif; ?>
            
         </div>
         <a href="ver_materiales.php?id_modulo=<?= urlencode($id_modulo) ?>" 
            class="btn evidencia" 
            style="background-color: #87A2FB; color: white; font-weight: bold;">
            Ver Materiales
         </a>

    </div>

   
</section>


<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>





</body>
</html>