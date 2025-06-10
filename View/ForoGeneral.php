<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// if (!isset($_SESSION['id_usuario'])) {
//     echo "<script>alert('Debes iniciar sesión para comentar.'); window.location.href='/Edugloss-MVC/login.php';</script>";
//     exit;
// }

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

/* Tamaño adecuado para los mensajes */
.comentario {
    font-size: 18px; /* Ligera mejora para mejor lectura */
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.5;
}

/* Mejor visibilidad del nombre */
.comentario strong {
    color: #333;
    font-size: 22px; /* Nombre más prominente */
}

/* Área de escritura */
textarea {
    width: 100%;
    height: 100px; /* Más espacio para escribir */
    padding: 12px;
    resize: vertical;
    border-radius: 8px;
    border: 1px solid #aaa;
    font-size: 16px; /* Tamaño más cómodo al escribir */
}

/* Botón de enviar */
.btn-enviar {
    margin-top: 10px;
    background: #6610f2;
    color: #fff;
    padding: 12px 18px; /* Botón un poco más grande */
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px; /* Tamaño del texto en el botón */
}

.btn-enviar:hover {
    background:#510ebd;
}

/* Información del usuario */
.info-usuario {
    margin-bottom: 10px;
    font-weight: bold;
    color: #555;
    font-size: 16px; /* Un poco más grande para mejorar la visibilidad */
}

/* Tamaño de los mensajes */
.contenido-mensaje {
    font-size: 20px; /* Más grande para lectura fluida */
    color: #333;
    line-height: 1.6;
}

/* Tamaño de la fecha sin afectar otros elementos */
.fecha-mensaje {
    font-size: 12px; /* Pequeño para no destacar demasiado */
    color: #777; /* Más discreto */
    font-style: italic;
    text-align: right; /* Alinear al lado derecho */
    display: block;
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

    <?php if (!empty($comentarios)): ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <strong><?= htmlspecialchars($comentario['nombre'] . ' ' . $comentario['apellido']) ?>:</strong><br>
                <span class="contenido-mensaje"><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></span><br>
                <span class="fecha-mensaje"><?= htmlspecialchars($comentario['fecha']) ?></span><br>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay comentarios para mostrar.</p>
    <?php endif; ?>

    <form method="POST" style="margin-top: 20px;">
        <textarea name="contenido" placeholder="Escribe tu comentario aquí..." required></textarea>
        <button type="submit" class="btn-enviar" onclick="mostrarAlerta()">Enviar comentario</button>
<!--         <textarea name="contenido" placeholder="Escribe tu comentario aquí..." required></textarea> -->
    </form>
</div>

<!-- custom js file link  -->
<script src="../View/js/script.js"></script>
<script src="../View/js/mensajes.js"></script>

</body>
</html>
