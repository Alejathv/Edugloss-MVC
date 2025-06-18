<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../../Controller/CursoModuloController.php';
$db = new Database();
$conn = $db->getConnection();

$cursoCtrl = new CursoController($conn);


if (!isset($_GET['id'])) {
    echo "Curso no especificado.";
    exit;
}

$idCurso = $_GET['id'];
$curso = $cursoCtrl->obtenerCursoPorId($idCurso);

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cursoCtrl->actualizarCurso($_POST);
    header("Location: TablasCM.php?editado=curso");
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
      <img src="./View/img/icon1.png" class="image" alt="">
      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">estudiante</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">
        <a href="docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="TablasCM.php"><i class="fas fa-graduation-cap"></i><span>Gestión de Aprendizaje</span></a>
        <a href="Contenido.php"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="evidencias.php"><i class="fas fa-user-graduate"></i><span>Evidencias</span></a>
    </nav>

</div>
<h2 class="heading">Editar Curso</h2>

<form method="POST" class="formulario-edicion">
    <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($curso['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($curso['descripcion']) ?></textarea>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($curso['precio']) ?>" required>

    <label>Fecha Inicio:</label>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($curso['fecha_inicio']) ?>" required>

    <label>Fecha Fin:</label>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($curso['fecha_fin']) ?>" required>

    <label>Estado:</label>
    <select name="estado">
        <option value="disponible" <?= $curso['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
        <option value="cerrado" <?= $curso['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
    </select>

    <button type="submit">Actualizar Curso</button>
</form>

<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>