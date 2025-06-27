<?php
error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
require_once __DIR__ . '/../../Controller/AdminController.php';

$adminController = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminController->actualizarUsuario($_POST);
    header("Location: userlist.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$usuario = $adminController->obtenerUsuario($_GET['id']);
if (!$usuario) {
    die("Usuario no encontrado.");
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
         <a href="../../documentos/Manual de administrador.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
      </div>

      <!-- Perfil del usuario, muestra la imagen, nombre y rol -->
      <div class="profile">
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
         <p class="role">Administrador</p>
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
<img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">Administrador</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">

      <a href="./admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="./admin_pagos.php"><i class="fas fa-comments"></i><span>Pagos</span></a>
      <a href="./userlist.php"><i class="fas fa-graduation-cap"></i><span>Gestión de Usuarios</span></a>
   </nav>

</div>

    <h2 class="heading">Editar Usuario</h2>
    <form method="POST" class="formulario-edicion" action="editarusuario.php">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $usuario['apellido'] ?>" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $usuario['telefono'] ?>"><br>

        <label>Correo:</label>
        <input type="email" name="correo" value="<?= $usuario['correo'] ?>" required><br>

        <label>Rol:</label>
        <select name="rol" required>
            <?php foreach (['estudiante', 'docente', 'administrador', 'cliente'] as $rol): ?>
                <option value="<?= $rol ?>" <?= $usuario['rol'] === $rol ? 'selected' : '' ?>>
                    <?= ucfirst($rol) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select><br>
        <button type="submit">Actualizar Usuario</button>
    </form>
<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
