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


//
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
            height: 80vh;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
       .progress-container {
   margin: 20px 0;
   text-align: center;
}

.progress-bar {
   width: 100%;
   background-color: #eee;
   border-radius: 20px;
   overflow: hidden;
   height: 20px;
   margin-top: 10px;
}

.progress {
   height: 100%;
   background-color: #4CAF50;
   transition: width 0.5s ease-in-out;
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
   <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
</nav>

</div>

<section class="watch-video">
    
    <div class="video-container">
        <div class="video">

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
            <h3 class="title"><?= htmlspecialchars($material['nombre']) ?></h3>
            
            <div class="progress-container">
            <label>Progreso del módulo:</label>
            <div class="progress-bar">
                <div class="progress" style="width: 100%;"></div>
            </div>
            <p>100% completado</p>
            <a href="ver_materiales.php?id_modulo=<?= urlencode($id_modulo) ?>" class="btn" style="margin-top: 15px;">
                Completado
            </a>
            </div>



        </div>
    </div>

   
</section>


<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script>
   // Simula cuántos materiales hay por módulo
   const totalMateriales = 3; // por ejemplo, 3 videos o PDF
   const materialActualId = <?= $id ?>;

   // Guardamos el ID del material como visto
   const vistosKey = 'materiales_vistos_modulo';
   let vistos = JSON.parse(localStorage.getItem(vistosKey)) || [];

   if (!vistos.includes(materialActualId)) {
      vistos.push(materialActualId);
      localStorage.setItem(vistosKey, JSON.stringify(vistos));
   }

   // Calcular progreso
   const porcentaje = Math.floor((vistos.length / totalMateriales) * 100);
   document.getElementById('progress').style.width = porcentaje + '%';
   document.getElementById('progress-text').innerText = porcentaje + '% completado';

   // Habilitar el botón si se completaron todos
   const nextBtn = document.getElementById('nextBtn');
   if (vistos.length >= totalMateriales) {
      nextBtn.disabled = false;
      nextBtn.innerText = "¡Acceder al siguiente módulo!";
      nextBtn.onclick = function () {
         window.location.href = "siguiente_material.php"; // o lo que corresponda
      };
   } else {
      nextBtn.innerText = "Completa todos los materiales para continuar";
   }
</script>

</body>
</html>