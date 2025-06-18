<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Verificar si la sesión no está iniciada antes de llamar a session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar rol de administrador
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'administrador') {
    $_SESSION['error_acceso'] = "Acceso no autorizado";
    header('Location: /Edugloss-MVC/login.php');
    exit;
}

if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Pagos</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="admin_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/icon1.png" class="image" alt="">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
            </h3>
            <p class="role">Administrador</p>
            <div class="flex-btn">
                <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/icon1.png" class="image" alt="">
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
        </h3>
        <p class="role">Administrador</p>
    </div>
    <nav class="navbar">
        <a href="admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="admin_pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
        <a href="userlist.php"><i class="fas fa-users"></i><span>Usuarios</span></a>
        <a href="cursos.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
    </nav>
</div>

<section class="main-content">
   
</section>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>