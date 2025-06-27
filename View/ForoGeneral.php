<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/ForoModel.php';
require_once '../Controller/EstudianteController.php';
$database = new Database();
$db = $database->getConnection();
$controller = new EstudianteController($db);
$foroModel = new ForoModel();

$redireccionCurso = $controller->mostrarInscripciones();
$hayInscripciones = !empty($redireccionCurso);
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
        /* Estilos existentes del foro */
        .foro-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .comentario {
            margin-bottom: 15px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
            border-left: 4px solid #5c3fa3;
        }
        
        .respuesta {
            margin-left: 30px;
            border-left-color: #8656e9;
        }
        
        .fecha-mensaje {
            color: #666;
            font-size: 0.8em;
        }
        
        .btn-responder {
            background-color: #5c3fa3;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
        }
        
        .form-respuesta {
            display: none;
            margin-top: 10px;
        }
        
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .btn-enviar {
            background-color: #5c3fa3;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .info-usuario {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e9e1ff;
            border-radius: 5px;
        }
    </style>
</head>
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
            <img src="img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
            <p class="role"><?= htmlspecialchars($_SESSION['rol_nombre']) ?></p>
            <a href="./perfil.php" class="btn">Ver Perfil</a>
            <div class="flex-btn">
                <a href="../logout.php" class="option-btn">Cerrar sesión</a>
            </div>
        </div>
    </section>
</header>

<!-- Sidebar dinámico según rol -->
<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
        <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
        <p class="role"><?= htmlspecialchars($_SESSION['rol_nombre']) ?></p>
        <a href="./perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <?php if ($_SESSION['rol_nombre'] == 'estudiante'): ?>
            <!-- Menú para estudiantes -->
            <a href="mestudiante/home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
            <a href="ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
            <a href="mestudiante/<?= htmlspecialchars($redireccionCurso) ?>"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
            <a href="mestudiante/tareas.php"><i class="fas fa-tasks"></i><span>Tareas</span></a>
            
        <?php elseif ($_SESSION['rol_nombre'] == 'docente'): ?>
            <!-- Menú para docentes -->
            <a href="../View/mdocente/docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
            <a href="./ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
            <a href="../View/mdocente/TablasCM.php"><i class="fas fa-book"></i><span>Gestión de Aprendizaje</span></a>
            <a href="../View/mdocente/Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
            <a href="../View/mdocente/evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
            
        <?php elseif ($_SESSION['rol_nombre'] == 'administrador'): ?>
            <!-- Menú para administradores -->
            <a href="../View/madmin/admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
            <a href="../View/madmin/admin_pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
            <a href="../View/madmin/userlist.php"><i class="fas fa-users-cog"></i><span>Gestión de Usuarios</span></a>
            
        <?php else: ?>
            <!-- Menú por defecto (si no coincide ningún rol) -->
            <a href="../View/login.php"><i class="fas fa-question"></i><span>Iniciar Sesión</span></a>
        <?php endif; ?>
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