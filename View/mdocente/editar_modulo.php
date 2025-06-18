<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../../Controller/CursoModuloController.php';
$db = new Database();
$conn = $db->getConnection();

$moduloCtrl = new ModuloController($conn);


if (!isset($_GET['id'])) {
    echo "Curso no especificado.";
    exit;
}

$id_modulo= $_GET['id'];
$modulo = $moduloCtrl->obtenerModuloPorId($id_modulo);

if (!$modulo) {
    echo "Modulo no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduloCtrl->actualizarModulo($_POST);
    header("Location: TablasCM.php?editado=modulo");
    exit;
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
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
         <p class="role">Estudiante</p>
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
      <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>

      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
   </nav>

</div>
<h2 class="heading">Editar Modulo</h2>

<form method="POST" class="formulario-edicion">
    <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($modulo['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($modulo['descripcion']) ?></textarea>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($modulo['precio']) ?>" required>

    <label>Estado:</label>
    <select name="estado">
        <option value="disponible" <?= $modulo['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
        <option value="cerrado" <?= $modulo['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
    </select>

    <button type="submit">Actualizar Módulo</button>
</form>

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>