<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'docente') {
    header("Location: ../../login.php");
    exit;
}

require_once "../../Model/database.php";

$database = new Database();
$conn = $database->getConnection();

// Consulta de inscripciones activas con curso y módulo
$query = "
    SELECT 
        u.nombre AS nombre_usuario,
        u.apellido,
        u.correo,
        c.nombre AS nombre_curso,
        m.nombre AS nombre_modulo
    FROM inscripcion i
    INNER JOIN usuarios u ON i.id_usuario = u.id_usuario
    LEFT JOIN curso c ON i.id_curso = c.id_curso
    LEFT JOIN modulo m ON i.id_modulo = m.id_modulo
    WHERE i.estado = 'activa'
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones Activas</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tabla-inscripciones {
            width: 100%;
            max-width: 1200px;
            margin: 40px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .tabla-inscripciones thead {
            background-color: #6f42c1;
            color: white;
        }
        .tabla-inscripciones th, .tabla-inscripciones td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }
        .tabla-inscripciones th {
            padding: 12px 16px;
            text-align: center;
            border: 1px solid #e5e5e5;
            background: #8e4de6;
            color: #fff;
        }
        .tabla-inscripciones tbody tr:hover {
            background-color: #f2f2f2;
        }
        .tabla-inscripciones td i {
            margin-right: 6px;
            color: #6f42c1;
        }
        .sin-resultados {
            text-align: center;
            margin-top: 20px;
            color: #999;
        }
        .main-content {
            padding: 40px 20px;
        }
    </style>
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
            <p class="role">Docente</p>
            <a href="../perfil.php" class="btn">ver perfil</a>
            <div class="flex-btn">
                <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
        <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="./docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
        <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
        <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
    </nav>
</div>

<div class="main-content">
    <h2 style="text-align:center; color:#6f42c1;"> Inscripciones Activas</h2>

    <?php if ($result && $result->num_rows > 0): ?>
    <table class="tabla-inscripciones">
        <thead>
            <tr>
                <th> Estudiante</th>
                <th>Correo</th>
                <th>Curso</th>
                <th>Módulo</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre_usuario'] . ' ' . $row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_curso'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['nombre_modulo'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="sin-resultados"><i class="fas fa-info-circle"></i> No hay inscripciones activas registradas.</p>
    <?php endif; ?>
</div>
<footer class="footer">
   &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>

<script src="../js/script.js"></script>
</body>
</html>
