<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "../../Model/database.php";

// Seguridad: redirige si no es administrador
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'administrador') {
   header("Location: ../../login.php");
   exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Panel del Administrador</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="../css/style_panel.css">
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
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
         <p class="role">Administrador</p>
         <a href="../perfil.php" class="btn">Ver perfil</a>
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
      <p class="role">Administrador</p>
      <a href="../perfil.php" class="btn">Ver perfil</a>
   </div>

   <nav class="navbar">
      <a href="admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="userlist.php"><i class="fas fa-user-cog"></i><span>Gestión de Usuarios</span></a>
      <a href="pagos.php"><i class="fas fa-money-check-alt"></i><span>Pagos</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
   </nav>
</div>

<div class="dashboard-container">
   <div class="welcome-section">
      <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h2>
      <p>Administra usuarios y pagos desde este panel.</p>
   </div>

   <div class="cards-grid">
      <a href="userlist.php" class="dashboard-card">
         <div class="card-icon"><i class="fas fa-users-cog"></i></div>
         <h3>Gestión de Usuarios</h3>
         <p>Administra cuentas de estudiantes y docentes</p>
      </a>
      <a href="admin_pagos.php" class="dashboard-card">
         <div class="card-icon"><i class="fas fa-credit-card"></i></div>
         <h3>Pagos</h3>
         <p>Verificar y autorizar pagos</p>
      </a>
   </div>
</div>

<footer class="footer">
   &copy; 2024 <span>EduGloss</span> | Todos los derechos reservados
</footer>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
