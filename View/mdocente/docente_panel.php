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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/icon1.png" class="image" alt="">
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
        <img src="../img/icon1.png" class="image" alt="">
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="TablasCM.php"><i class="fas fa-graduation-cap"></i><span>Gestión de Aprendizaje</span></a>
        <a href="Contenido.php"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="evidencias.php"><i class="fas fa-user-graduate"></i><span>Evidencias</span></a>
    </nav>
</div>
<section class="main-content">
    <h2>CREAR PANEL DE DOCENTE</h2>
</section>


<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
