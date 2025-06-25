<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../model/Database.php';

$db = new Database();
$conn = $db->getConnection();

$id_usuario = $_SESSION['user_id'] ?? null;

if (!$id_usuario) {
    header("Location: login.php");
    exit;
}

$msg = "";

// Obtener datos del usuario (sin guardar contraseña en texto plano en la variable)
$stmt = $conn->prepare("SELECT nombre, correo, contraseña, rol FROM usuarios WHERE id_usuario = ?");
if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre_actual, $correo_actual, $hash_actual, $rol_actual);
$stmt->fetch();
$stmt->close();

$foto_actual = $_SESSION['foto_perfil'] ?? 'icon1.png';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $nombre = $_POST['name'] ?? '';
    $correo = $_POST['email'] ?? '';
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $c_pass   = $_POST['c_pass'] ?? '';

    if (empty($nombre) || empty($correo)) {
        $msg = "El nombre y el correo son obligatorios.";
    } else {
        $nuevo_pass = $hash_actual; // Por defecto se mantiene la contraseña actual (hasheada)

        if (!empty($new_pass)) {
            if (empty($old_pass)) {
                $msg = "Debe ingresar la contraseña anterior para cambiar la contraseña.";
            } elseif (!password_verify($old_pass, $hash_actual)) {
                $msg = "La contraseña anterior no coincide.";
            } elseif ($new_pass !== $c_pass) {
                $msg = "Las nuevas contraseñas no coinciden.";
            } else {
                $nuevo_pass = password_hash($new_pass, PASSWORD_DEFAULT); // Hashea la nueva contraseña
            }
        } else {
            if (!empty($old_pass)) {
                $msg = "Para cambiar la contraseña debe ingresar una nueva contraseña.";
            }
        }

        if (empty($msg)) {
            $foto_perfil = $_POST['foto_perfil']; // 'icon1.png' o 'icon2.png'

            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ?, foto_perfil = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssssi", $nombre, $correo, $nuevo_pass, $foto_perfil, $id_usuario);

            if ($stmt->execute()) {
                // Actualiza sesión
                $_SESSION['foto_perfil'] = $foto_perfil;
                $_SESSION['nombre'] = $nombre;

                $msg = "Perfil actualizado correctamente.";

                // Actualiza las variables actuales
                $foto_actual = $foto_perfil;
                $nombre_actual = $nombre;
                $correo_actual = $correo;
                $hash_actual = $nuevo_pass;
            } else {
                $msg = "Error al actualizar el perfil.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>Actualizar Perfil</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
   <link rel="stylesheet" href="css/style_panel.css" />
</head>
<style>
/* Contenedor del formulario */
.contenedor-formulario {
    max-width: 600px;
    margin: 40px auto;
    background-color: #f9f7fd;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

/* Título del formulario */
.contenedor-formulario h3 {
    font-size: 26px;
    color: #5c3fa3;
    text-align: center;
    margin-bottom: 30px;
}

/* Etiquetas */
.contenedor-formulario p {
    margin-bottom: 8px;
    color: #4b2e83;
    font-weight: 600;
    font-size: 15px;
}

/* Campos de entrada */
.campo-input {
    width: 100%;
    padding: 14px;
    border: 1px solid #c9bdf0;
    border-radius: 12px;
    font-size: 15px;
    margin-bottom: 20px;
    background-color: #ffffff;
    transition: border-color 0.3s ease;
}

.campo-input:focus {
    outline: none;
    border-color: #8656e9;
}

/* Botón de enviar */
.boton-enviar {
    width: 100%;
    padding: 14px;
    background-color: #8656e9;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.boton-enviar:hover {
    background-color: #734bd1;
}

/* Imagen de selección */
.contenedor-formulario label img {
    border: 2px solid transparent;
    border-radius: 12px;
    transition: border-color 0.3s ease, transform 0.2s ease;
}

/* Radio oculto */
input[type="radio"] {
    display: none;
}

/* Imagen seleccionada */
input[type="radio"]:checked + img,
label input[type="radio"]:checked + img {
    border-color: #8656e9;
    transform: scale(1.05);
}

/* Mensaje de estado */
.mensaje-alerta {
    text-align: center;
    margin-bottom: 20px;
    padding: 10px;
    border-radius: 10px;
    background-color:#b8e5a6;
    color:#298504;
    font-weight: 500;
}
</style>

<body>

<header class="header">
   <section class="flex">
      <a href="home.php" class="logo">
         <img src="../View/img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
      </a>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
      <div class="profile">
         <img src="img/<?= htmlspecialchars($foto_actual) ?>" class="image" alt="Foto perfil" />
         <h3 class="name"><?= htmlspecialchars($nombre_actual) ?></h3>
         <p class="role"><?= htmlspecialchars($rol_actual) ?></p>
         <a href="./perfil.php" class="btn">Ver perfil</a>
         <div class="flex-btn">
            <a href="../logout.php" class="option-btn">Cerrar sesión</a>
         </div>
      </div>
   </section>
</header>

<div class="side-bar">
   <div id="close-btn"><i class="fas fa-times"></i></div>
   <div class="profile">
      <img src="img/<?= htmlspecialchars($foto_actual) ?>" class="image" alt="Foto perfil" />
      <h3 class="name"><?= htmlspecialchars($nombre_actual) ?></h3>
      <p class="role"><?= htmlspecialchars($rol_actual) ?></p>
      <a href="./perfil.php" class="btn">Ver perfil</a>
   </div>
   <nav class="navbar">
      <?php if ($rol_actual == 'estudiante'): ?>
         <!-- Menú para estudiantes -->
         <a href="../View/mestudiante/home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
         <a href="cursos.php"><i class="fas fa-book"></i><span>Mis Cursos</span></a>
         <a href="calificaciones.php"><i class="fas fa-star"></i><span>Calificaciones</span></a>
         <a href="tareas.php"><i class="fas fa-tasks"></i><span>Tareas</span></a>
         <a href="foro.php"><i class="fas fa-comments"></i><span>Foro Estudiantil</span></a>
         
      <?php elseif ($rol_actual == 'docente'): ?>
         <!-- Menú para docentes -->
         <a href="../View/mdocente/docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
         <a href="./ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
         <a href="../View/mdocente/TablasCM.php"><i class="fas fa-book"></i><span>Gestión de Aprendizaje</span></a>
         <a href="../View/mdocente/Contenido.php"><i class="fas fa-upload"></i><span>Subir Materia</span></a>
         <a href="../View/mdocente/evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
         
      <?php elseif ($rol_actual == 'administrador'): ?>
         <!-- Menú para administradores -->
         <a href="../View/madmin/admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
         <a href="../View/madmin/admin_pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
         <a href="../View/madmin/userlist.php"><i class="fas fa-users-cog"></i><span>Gestión de Usuarios</span></a>
         
      <?php else: ?>
         <!-- Menú por defecto (si no coincide ningún rol) -->
         <a href="../View/login.php"><i class="fas fa-question"></i><span>Iniciar Sesion o registrarse</span></a>
      <?php endif; ?>
   </nav>
</div>

<section class="contenedor-formulario">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Actualizar Perfil</h3>

      <?php if (!empty($msg)) : ?>
         <div class="mensaje-alerta"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <p>Actualizar nombre</p>
      <input type="text" name="name" value="<?= htmlspecialchars($nombre_actual) ?>" maxlength="50" class="campo-input" required />

      <p>Actualizar correo</p>
      <input type="email" name="email" value="<?= htmlspecialchars($correo_actual) ?>" maxlength="50" class="campo-input" required />

      <p>Contraseña anterior</p>
      <input type="password" name="old_pass" placeholder="Tu contraseña actual" maxlength="20" class="campo-input" />

      <p>Nueva contraseña</p>
      <input type="password" name="new_pass" placeholder="Tu nueva contraseña" maxlength="20" class="campo-input" />

      <p>Confirmar nueva contraseña</p>
      <input type="password" name="c_pass" placeholder="Confirmar nueva contraseña" maxlength="20" class="campo-input" />

      <p>Seleccionar nueva foto de perfil</p>
      <div style="display: flex; gap: 20px; align-items: center;">
         <label>
            <input type="radio" name="foto_perfil" value="icon1.png" <?= ($foto_actual === 'icon1.png' ? 'checked' : '') ?> />
            <img src="img/icon1.png" alt="Icono 1" width="60" />
         </label>
         <label>
            <input type="radio" name="foto_perfil" value="icon2.png" <?= ($foto_actual === 'icon2.png' ? 'checked' : '') ?> />
            <img src="img/icon2.png" alt="Icono 2" width="60" />
         </label>
      </div>

      <input type="submit" value="Actualizar perfil" name="submit" class="boton-enviar" />
   </form>
</section>


<footer class="footer">
   &copy; <?= date('Y') ?> por <span>Educa</span> | Todos los derechos reservados.
</footer>

<script src="js/script.js"></script>
</body>
</html>