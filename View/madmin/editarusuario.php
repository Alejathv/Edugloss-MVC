<?php
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

</head>
<body>

<header class="header">
   
   <section class="flex">

        <a href="docente_panel.php" class="logo">
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
         <img src="./View/img/icon1.png" class="image" alt="imagen de estudiante">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
         <p class="role">Administrador</p>
         <a href="./View/perfil.php" class="btn">ver perfil</a>
         <div class="flex-btn">
         <a href="logout.php" class="option-btn">Cerrar Sesión</a>
         </div>
      </div>   

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="./View/img/icon1.png" class="image" alt="">
      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">Administrador</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">

      <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
      <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>

      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
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

        <label>Especialidad:</label>
        <input type="text" name="especialidad" value="<?= $usuario['especialidad'] ?>"><br><br>

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
