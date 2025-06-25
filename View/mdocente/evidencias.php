<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}

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
            <img src="../img/icon1.png" class="image" alt="">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
            </h3>
            <p class="role">Docente</p>
            <a href="../perfil.php" class="btn">ver perfil</a>
            <div class="flex-btn">
            <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>

        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/icon1.png" class="image" alt="">
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
   <a href="./docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
   <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
   
   <?php if (isset($_SESSION['rol_nombre'])) { ?>
      <?php if ($_SESSION['rol_nombre'] == 'estudiante') { ?>
         <!-- Enlaces para estudiantes -->
         <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
         <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'docente') { ?>
         <!-- Enlaces para docentes -->
         <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
         <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
         <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'administrador') { ?>
         <!-- Enlaces para administradores -->
         <a href="gestion_usuarios.php"><i class="fas fa-user-cog"></i><span>Gestión de Usuarios</span></a>
         <a href="gestion_cursos_admin.php"><i class="fas fa-book"></i><span>Gestión de Cursos</span></a>
         <a href="reportes.php"><i class="fas fa-chart-bar"></i><span>Reportes</span></a>
      <?php } ?>
   <?php } else { ?>
      <!-- Enlace para invitados/no logueados -->
      <a href="login.php"><i class="fas fa-sign-in-alt"></i><span>Iniciar Sesión</span></a>
   <?php } ?>

</nav>
</div>
<section class="main-content">
    <div class="dashboard contenedor-centro">
        <div class="tabla-evidencias">
            <h2>Evidencias de los Estudiantes</h2>
            <div class="table-responsive">
                <table class="tablaevidencia">
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
                                            <a href="<?= $rutaArchivo ?>" target="_blank">
                                                <img src="<?= $rutaArchivo ?>" alt="Evidencia">
                                            </a>
                                        <?php elseif ($ext === 'pdf'): ?>
                                            <a href="<?= $rutaArchivo ?>" target="_blank">
                                                <img src="../assets/pdf-icon.png" alt="PDF" style="max-width:50px;">
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $rutaArchivo ?>" target="_blank">Descargar archivo</a>
                                        <?php endif;
                                    else: ?>
                                        Archivo no disponible
                                    <?php endif;
                                else: ?>
                                    No hay archivo
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($e['fecha_subida']) ?></td>
                            <td>
                                <span class="estado-<?= htmlspecialchars(strtolower($e['estado'])) ?>">
                                    <?= ucfirst($e['estado'] ?? 'pendiente') ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="../../Controller/EvidenciaController.php" style="display: flex; gap: 8px; justify-content: center;">
                                    <input type="hidden" name="id_evidencia" value="<?= $e['id_evidencia'] ?>">
                                    <button type="submit" name="estado" value="aprobado" class="btn-icon aprobar" title="Aprobar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="submit" name="estado" value="reprobado" class="btn-icon reprobar" title="Reprobar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</section>


<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
