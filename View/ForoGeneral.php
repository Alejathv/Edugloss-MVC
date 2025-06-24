<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/ForoModel.php';

$foroModel = new ForoModel();

// Procesar envío de nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['contenido'])) {
        $contenido = trim($_POST['contenido']);
        $id_usuario = $_SESSION['user_id'];
        $foroModel->insertarComentarioGeneral($id_usuario, $contenido);
    }
    
    // Procesar respuesta a comentario
    if (!empty($_POST['contenido_respuesta']) && isset($_POST['responder_a'])) {
        $contenido = trim($_POST['contenido_respuesta']);
        $id_usuario = $_SESSION['user_id'];
        $id_comentario_padre = (int)$_POST['responder_a'];
        $foroModel->insertarRespuesta($id_usuario, $contenido, $id_comentario_padre);
    }
    
    // Redirigir para evitar reenvío de formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obtener todos los comentarios para mostrar (ahora anidados)
$comentarios = $foroModel->getComentariosAnidados() ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro General</title>
    <link rel="stylesheet" href="css/style_panel.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="./img/LogoEGm.png" class="logo"></a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name"><?= htmlspecialchars($_SESSION['nombre']) ?></h3>
            <p class="role"><?= htmlspecialchars($_SESSION['rol_nombre']) ?></p>
            <a href="./perfil.php" class="btn">Ver Perfil</a>
            <div class="flex-btn">
                <a href="../logout.php" class="option-btn">Cerrar sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
<img src="img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
<h3 class="name"><?= htmlspecialchars($_SESSION['nombre']) ?></h3>
        <p class="role"><?= htmlspecialchars($_SESSION['rol_nombre']) ?></p>
        <a href="./perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="home.html"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="courses.html"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
        <a href="contenido.html"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="estudiantes.html"><i class="fas fa-user-graduate"></i><span>Estudiantes</span></a>
    </nav>
</div>

<div class="foro-container">
    <h2>Chat General de Dudas</h2>

    <div class="info-usuario">
        Bienvenido, <?= 
            (isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario') 
            . ' (' . 
            (isset($_SESSION['rol_nombre']) ? htmlspecialchars($_SESSION['rol_nombre']) : 'Rol no definido') 
            . ')' 
        ?>
    </div>

    <?php 
    function mostrarComentarios($comentarios, $nivel = 0) {
        foreach ($comentarios as $comentario) {
            $clase = $nivel > 0 ? 'respuesta' : '';
            echo '<div class="comentario ' . $clase . '">';
            echo '<strong>' . htmlspecialchars($comentario['nombre'] . ' ' . $comentario['apellido']) . ':</strong><br>';
            echo '<span class="contenido-mensaje">' . nl2br(htmlspecialchars($comentario['contenido'])) . '</span><br>';
            echo '<span class="fecha-mensaje">' . htmlspecialchars($comentario['fecha']) . '</span><br>';
            
            // Botón para responder
            echo '<button class="btn-responder" onclick="mostrarFormRespuesta(' . $comentario['id_comentario'] . ')">
                    <i class="fas fa-reply"></i> Responder
                  </button>';
            
            // Formulario de respuesta (oculto inicialmente)
            echo '<div id="form-respuesta-' . $comentario['id_comentario'] . '" class="form-respuesta">
                    <form method="POST">
                        <input type="hidden" name="responder_a" value="' . $comentario['id_comentario'] . '">
                        <textarea name="contenido_respuesta" placeholder="Escribe tu respuesta..." required></textarea>
                        <button type="submit" class="btn-enviar">Enviar respuesta</button>
                    </form>
                  </div>';
            
            // Mostrar respuestas recursivamente
            if (!empty($comentario['respuestas'])) {
                mostrarComentarios($comentario['respuestas'], $nivel + 1);
            }
            
            echo '</div>';
        }
    }

    if (!empty($comentarios)) {
        mostrarComentarios($comentarios);
    } else {
        echo '<p>No hay comentarios para mostrar.</p>';
    }
    ?>

    <form method="POST" style="margin-top: 20px;">
        <textarea name="contenido" placeholder="Escribe tu comentario aquí..." required></textarea>
        <button type="submit" class="btn-enviar">Enviar comentario</button>
    </form>
</div>

<!-- custom js file link  -->
<script src="../View/js/script.js"></script>
<script src="../View/js/mensajes.js"></script>
<script>
function mostrarFormRespuesta(id) {
    // Oculta todos los formularios primero
    document.querySelectorAll('.form-respuesta').forEach(form => {
        form.style.display = 'none';
    });
    // Muestra el formulario correspondiente
    document.getElementById('form-respuesta-' + id).style.display = 'block';
}
</script>

</body>
</html>