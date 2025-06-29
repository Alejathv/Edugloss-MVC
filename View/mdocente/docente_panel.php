<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}

require_once "../../Model/database.php";

// Aquí se crea la instancia correctamente
$database = new Database();
$conn = $database->getConnection();
// TOTAL DE ESTUDIANTES
$sqlEstudiantes = "SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'estudiante'";
$resultado = $conn->query($sqlEstudiantes);
$estudiantes = ($resultado && $row = $resultado->fetch_assoc()) ? $row['total'] : 0;

// CURSOS ACTIVOS
$sqlCursos = "SELECT COUNT(*) AS total FROM curso WHERE estado = 'disponible'";
$resultado = $conn->query($sqlCursos);
$cursos = ($resultado && $row = $resultado->fetch_assoc()) ? $row['total'] : 0;

// EVIDENCIAS PENDIENTES
$sqlTareas = "SELECT COUNT(*) AS total FROM evidencias WHERE estado = 'pendiente'";
$resultado = $conn->query($sqlTareas);
$tareas = ($resultado && $row = $resultado->fetch_assoc()) ? $row['total'] : 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
            <a href="../../documentos/Manual de uso Docente.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
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
<div class="dashboard-container">
    <!-- Bienvenida -->
    <div class="welcome-section">
        <h2>Bienvenida, Profesora <?= htmlspecialchars($_SESSION['nombre']) . ' ' . htmlspecialchars($_SESSION['apellido']) ?></h2>
        <p>Gestiona tus estudiantes y recursos desde este panel</p>
    </div>
<!-- Resumen Rápido -->
    <div class="quick-stats">
        <a href="./TablasCM.php"  class="stat-card">
            <h4>Cursos Activos</h4>
            <p class="stat-number"><?= $cursos ?></p>
        </a>
        <a  href="./ver_inscripciones.php" class="stat-card">
            <h4>Estudiantes</h4>
            <p class="stat-number"><?= $estudiantes ?></p>
        </a>
    </div>
    <!-- Tarjetas de Acceso Rápido (sin duplicar lo del sidebar) -->
    <div class="cards-grid">
        <!-- Acceso directo a evidencias -->
        <a href="evidencias.php" class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3>Evidencias</h3>
            <p>Revisa trabajos y actividades</p>
            
        </a>

        <!-- Acceso al perfil -->
        <a href="../perfil.php" class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-user"></i>
            </div>
            <h3>Mi Perfil</h3>
            <p>Consulta y edita tu información</p>
        </a>
    </div>

</div>
<footer class="footer">
   &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
