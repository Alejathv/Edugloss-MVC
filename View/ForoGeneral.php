<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/ForoModel.php';

$foroModel = new ForoModel();

// Procesar envío de nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenido'])) {
    $contenido = trim($_POST['contenido']);
    $id_usuario = $_SESSION['user_id'];

    $foroModel->insertarComentarioGeneral($id_usuario, $contenido);

    // Redirigir para evitar reenvío de formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obtener todos los comentarios para mostrar
$comentarios = $foroModel->getTodosLosComentariosGenerales() ?? [];
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
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
      .comentario {
    font-size: 16px; /* o 18px si quieres más grande */
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.4;
}
        .comentario:last-child {
            border-bottom: none;
        }
        .comentario strong {
            color: #333;
            font-size: 20px;
        }
        textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            resize: vertical;
            border-radius: 8px;
            border: 1px solid #aaa;
        }
        .btn-enviar {
            margin-top: 10px;
            background: #4CAF50;
            color: #fff;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-enviar:hover {
            background: #45a049;
        }
        .info-usuario {
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
            font-size: 15px;
        }
    </style>
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="#" class="logo">EduGloss</a>
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
            <img src="../img/pic-1.jpg" class="image" alt="">
            <h3 class="name"><?= htmlspecialchars($_SESSION['nombre']) ?></h3>
            <p class="role">Docente</p>
            <a href="profile.html" class="btn">Ver Perfil</a>
            <div class="flex-btn">
                <a href="../logout.php" class="option-btn">Cerrar sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/pic-1.jpg" class="image" alt="">
        <h3 class="name"><?= htmlspecialchars($_SESSION['nombre']) ?></h3>
        <p class="role">Docente</p>
        <a href="profile.html" class="btn">Ver Perfil</a>
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
    <h2>Foro General de Dudas</h2>

    <div class="info-usuario">
        Bienvenido, <?= 
            (isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario') 
            . ' (' . 
            (isset($_SESSION['rol_nombre']) ? htmlspecialchars($_SESSION['rol_nombre']) : 'Rol no definido') 
            . ')' 
        ?>
    </div>

    <?php if (!empty($comentarios)): ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <strong><?= htmlspecialchars($comentario['nombre'] . ' ' . $comentario['apellido']) ?>:</strong><br>
                <?= nl2br(htmlspecialchars($comentario['contenido'])) ?><br>
                <?= $comentario['fecha'] ?><br>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay comentarios para mostrar.</p>
    <?php endif; ?>

    <form method="POST" style="margin-top: 20px;">
        <textarea name="contenido" placeholder="Escribe tu comentario aquí..." required></textarea>
        <button type="submit" class="btn-enviar">Enviar comentario</button>
    </form>
</div>
<script src="../js/script.js"></script>

</body>
</html>
