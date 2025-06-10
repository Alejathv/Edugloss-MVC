<?php
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // o donde vaya tu login
    exit;
}

// Conexión a la base de datos
require_once '../model/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener datos del usuario desde la BD para mostrar nombre y rol actualizados
$id_usuario = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nombre, rol FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    $nombre = $usuario['nombre'];
    $rol = $usuario['rol'];
} else {
    // Usuario no encontrado, cerrar sesión o redirigir
    session_destroy();
    header("Location: login.php");
    exit;
}

// Foto de perfil de la sesión (por defecto icon1.png)
$foto_perfil = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : 'icon1.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Perfil</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style_panel.css" />
   <style>
      /* Estilo pequeño para el icono lápiz sobre la imagen */
      .profile-image-container {
        position: relative;
        display: inline-block;
      }
      .edit-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: #fff;
        border-radius: 50%;
        padding: 4px;
        cursor: pointer;
        border: 1px solid #ccc;
        color: #333;
      }
      .edit-icon:hover {
        background-color: #f0f0f0;
      }
   </style>
</head>
<body>

<header class="header">
   <section class="flex">

      <a href="home.php" class="logo">
         <img src="../View/img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
      </a>

      <form action="search.html" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="search courses..." maxlength="100" />
         <button type="submit" class="fas fa-search"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <div class="profile-image-container">
           <img src="../View/img/<?php echo htmlspecialchars($foto_perfil); ?>" class="image" alt="Foto de perfil" />
           <a href="cambiar_foto.php" class="edit-icon" title="Cambiar foto de perfil"><i class="fas fa-pencil"></i></a>
         </div>
         <h3 class="name"><?php echo htmlspecialchars($nombre); ?></h3>
         <p class="role"><?php echo htmlspecialchars($rol); ?></p>
         <a href="./perfil.php" class="btn">ver perfil</a>
         <!-- Botones para iniciar sesión o registrarse -->
         <div class="flex-btn">
         <a href="../logout.php" class="option-btn">Cerrar Sesión</a>

         </div>
      </div>

   </section>
</header>

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="../View/img/<?php echo htmlspecialchars($foto_perfil); ?>" class="image" alt="Foto de perfil" />
      <h3 class="name"><?php echo htmlspecialchars($nombre); ?></h3>
      <p class="role"><?php echo htmlspecialchars($rol); ?></p>
      <a href="../View/perfil.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="home.html"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.html"><i class="fas fa-question"></i><span>about</span></a>
      <a href="courses.html"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Preguntas Frecuentes</span></a>
   </nav>

</div>

<section class="user-profile">

   <h1 class="heading">Tu perfil</h1>

   <div class="info">

      <div class="user">
         <img src="../View/img/<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de perfil" />
         <h3><?php echo htmlspecialchars($nombre); ?></h3>
         <p><?php echo htmlspecialchars($rol); ?></p>
         <a href="actualizar_perfil.php" class="inline-btn">Actualizar Perfil</a>
      </div>
   </div>

</section>

<footer class="footer">
   &copy; copyright @ 2022 by <span>mr. web designer</span> | all rights reserved!
</footer>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
