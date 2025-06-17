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
    <style>
       .foro-container {
          max-width: 800px;
          margin: 40px auto;
          background: #fff;
          padding: 30px;
          border-radius: 16px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
       }

       .foro-container h2 {
          font-size: 28px;
          color: #6610f2;
          margin-bottom: 20px;
          text-align: center;
       }

       .comentario {
          background-color: #f8f8ff;
          border-radius: 12px;
          padding: 15px 20px;
          margin-bottom: 16px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.05);
       }

       .comentario strong {
          font-size: 20px;
          color: #4c00b4;
       }

       .contenido-mensaje {
          font-size: 18px;
          color: #333;
          margin-top: 8px;
          line-height: 1.5;
       }

       .fecha-mensaje {
          font-size: 13px;
          color: #777;
          text-align: right;
          display: block;
          margin-top: 6px;
          font-style: italic;
       }

       .info-usuario {
          font-size: 16px;
          font-weight: bold;
          color: #444;
          margin-bottom: 20px;
       }

       textarea[name="contenido"], textarea[name="contenido_respuesta"] {
          width: 100%;
          height: 120px;
          padding: 15px;
          font-size: 16px;
          border: 1px solid #ccc;
          border-radius: 10px;
          margin-bottom: 12px;
          background: #fdfdfd;
          box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
          resize: vertical;
       }

       .btn-enviar {
          display: inline-block;
          background: #6610f2;
          color: white;
          padding: 12px 24px;
          border: none;
          border-radius: 10px;
          font-size: 16px;
          cursor: pointer;
          transition: background 0.3s;
       }

       .btn-enviar:hover {
          background: #510ebd;
       }

       form {
          margin-top: 30px;
       }

       /* Estilos para comentarios anidados */
       .respuesta {
          margin-left: 40px;
          border-left: 3px solid #6610f2;
          padding-left: 15px;
          margin-top: 10px;
       }
       .btn-responder {
          background: none;
          border: none;
          color: #6610f2;
          cursor: pointer;
          font-size: 14px;
          margin-top: 5px;
       }
       .form-respuesta {
          display: none;
          margin-top: 10px;
       }
    </style>
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="./img/LogoEGm.png" class="logo"></a>
        <form action="search.html" method="post" class="search-form">
            <input type="text" name="search_box" required placeholder="Buscar cursos..." maxlength="100">
            <button type="submit" class="fas fa-search"></button>
        </form>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="./img/icon1.png" class="image" alt="">
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
        <img src="./img/icon1.png" class="image" alt="">
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