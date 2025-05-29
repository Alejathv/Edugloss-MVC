<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "../../Model/database.php";

// Aquí se crea la instancia correctamente
$database = new Database();
$conn = $database->getConnection();

$query = "SELECT e.*, u.nombre AS estudiante 
          FROM evidencias e 
          JOIN usuarios u ON e.id_usuario = u.id_usuario";
$result = $conn->query($query);
$evidencias = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="../css/style_panel.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-size: 18px;
        }
        .main-content {
            padding: 2rem;
        }
        h2.heading {
            text-align: center;
            font-size: 26px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #f0f0f0;
        }
        .table th, .table td {
            padding: 14px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .table img {
            max-width: 100px;
            border-radius: 8px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table td form {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .option-btn, .delete-btn {
            font-size: 14px;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .option-btn {
            background-color: #ADD8C0;
        }
        .delete-btn {
            background-color: #FF7F7F;
        }
        .option-btn:hover {
            background-color: #91b9a5;
        }
        .delete-btn:hover {
            background-color: #e36464;
        }
    </style>
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
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
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
            </h3>
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
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
        <p class="role">Docente</p>
        <a href="profile.html" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="home.html"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="courses.html"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
        <a href="subir_material.php"><i class="fas fa-chalkboard-user"></i><span>Contenido</span></a>
        <a href="estudiantes.html"><i class="fas fa-user-graduate"></i><span>Estudiantes</span></a>
    </nav>
</div>

<section class="main-content">
    <div class="dashboard">
        <h2 class="heading">Evidencias de los Estudiantes</h2>
        <div class="box-container">
            <div class="box">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Módulo</th>
                                <th>Archivo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evidencias as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['estudiante']) ?></td>
                                <td><?= htmlspecialchars($e['id_curso']) ?></td>
                                <td><?= htmlspecialchars($e['id_modulo']) ?></td>
                                <td>
                                <?php 
                                    $rutaFisica = __DIR__ . '/../../documentos/' . $e['url_archivo'];

                                    if (!empty($e['url_archivo'])): 
                                        $ext = strtolower(pathinfo($e['url_archivo'], PATHINFO_EXTENSION));
                                        $rutaArchivo = "/EDUGLOSS-MVC/documentos/" . rawurlencode($e['url_archivo']);


                                        if (file_exists($rutaFisica)):
                                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                <a href="<?= $rutaArchivo ?>" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?= $rutaArchivo ?>" alt="Evidencia" style="max-width:100px; max-height:100px;">
                                                </a>
                                            <?php elseif ($ext === 'pdf'): ?>
                                                <a href="<?= $rutaArchivo ?>" target="_blank" rel="noopener noreferrer">
                                                    <img src="../assets/pdf-icon.png" alt="PDF" style="max-width:50px;">
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= $rutaArchivo ?>" target="_blank" rel="noopener noreferrer">Descargar archivo</a>
                                            <?php endif;
                                        else: ?>
                                            Archivo no disponible
                                        <?php endif;
                                    else: ?>
                                        No hay archivo
                                    <?php endif; 
                                ?>
                                </td>
                                <td><?= htmlspecialchars($e['fecha_subida']) ?></td>
                                <td><?= ucfirst($e['estado'] ?? 'pendiente') ?></td>
                                <td>
                                <form method="POST" action="../../Controller/EvidenciaController.php">
                                    <input type="hidden" name="id_evidencia" value="<?= $e['id_evidencia'] ?>">
                                    <button type="submit" name="estado" value="aprobado" class="option-btn">Aprobar</button>
                                    <button type="submit" name="estado" value="reprobado" class="delete-btn">Reprobar</button>
                                </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</section>

<script src="../js/script.js"></script>
</body>
</html>
