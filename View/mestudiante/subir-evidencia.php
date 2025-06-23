<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../../Model/database.php';

$database = new Database();
$conexion = $database->getConnection();

$id_usuario = $_SESSION['user_id'];

// Cursos donde está inscrito
$cursos = mysqli_query($conexion, "
    SELECT c.id_curso, c.nombre 
    FROM curso c 
    INNER JOIN inscripcion i ON c.id_curso = i.id_curso 
    WHERE i.id_usuario = $id_usuario
");

// Módulos de esos cursos
$modulos = mysqli_query($conexion, "
    SELECT m.id_modulo, m.nombre AS nombre_modulo 
    FROM modulo m
    INNER JOIN curso c ON m.id_curso = c.id_curso
    INNER JOIN inscripcion i ON c.id_curso = i.id_curso
    WHERE i.id_usuario = $id_usuario AND i.estado = 'activa'
");

// Evidencias ya subidas por el usuario
$evidencias = mysqli_query($conexion, "
    SELECT e.id_evidencia, e.url_archivo, e.fecha_subida, e.estado,
           c.nombre AS nombre_curso, m.nombre AS nombre_modulo
    FROM evidencias e
    INNER JOIN curso c ON e.id_curso = c.id_curso
    LEFT JOIN modulo m ON e.id_modulo = m.id_modulo
    WHERE e.id_usuario = $id_usuario
    ORDER BY e.fecha_subida DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>Subir Evidencias</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
   <link rel="stylesheet" href="../css/style_panel.css" />
   <style> body { font-size: 18px; } </style>
</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.html" class="logo"><img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;" /></a>
      <div class="icons d-flex align-items-center gap-3">
         <div id="menu-btn" class="fas fa-bars fs-4"></div>
         <div id="user-btn" class="fas fa-user fs-4"></div>
         <div id="toggle-btn" class="fas fa-sun fs-4"></div>
      </div>
      <div class="profile d-flex flex-column align-items-center ms-3">
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
         <p class="role mb-2">Estudiante</p>
         <a href="../perfil.php" class="btn">ver perfil</a>
         <div class="flex-btn"><a href="../../logout.php" class="option-btn">Cerrar Sesión</a></div>
   </section>
</header>

<div class="side-bar bg-light shadow-sm">
   <div id="close-btn" class="text-end p-2"><i class="fas fa-times fs-4"></i></div>
   <div class="profile text-center mb-4">
      <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
      <p class="role mb-2">Estudiante</p>
      <a href="../perfil.php" class="btn btn-outline-primary btn-sm">ver perfil</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
      <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
   </nav>
</div>

<div class="container my-5" style="margin-left: 40px; padding-right: 40px;">
   <div class="d-flex justify-content-center mt-5">
      <div class="contenedor-centro">
         <div class="formulario-subida">
            <h2 class="mb-4 text-center">Subir Evidencia</h2>
            <form action="../../Controller/EvidenciaController.php" method="POST" enctype="multipart/form-data">
               <div class="mb-3">
                  <label for="curso">Curso:</label>
                  <select name="curso" id="curso" class="form-control" required>
                     <?php while ($curso = mysqli_fetch_assoc($cursos)) { ?>
                        <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
                     <?php } ?>
                  </select>
               </div>

               <?php if (mysqli_num_rows($modulos) > 0): ?>
               <div class="mb-3">
                  <label for="modulo">Módulo:</label>
                  <select name="modulo" id="modulo" class="form-control" required>
                     <option value="">-- Selecciona un módulo --</option>
                     <?php while ($modulo = mysqli_fetch_assoc($modulos)) { ?>
                        <option value="<?= $modulo['id_modulo'] ?>"><?= htmlspecialchars($modulo['nombre_modulo']) ?></option>
                     <?php } ?>
                  </select>
               </div>
               <?php endif; ?>

               <div class="mb-3">
                  <label for="archivo">Archivo:</label>
                  <input type="file" name="archivo" id="archivo" class="form-control" required />
               </div>

               <div class="text-center">
                  <button type="submit" name="subir_evidencia" class="btn btn-primary w-100">Subir</button>
               </div>
            </form>
         </div>
      </div>
   </div>

   <div class="tabla-evidencias p-4 border rounded bg-white mt-5">
      <h2 class="mb-4">Mis Evidencias</h2>
      <div class="table-responsive">
         <table class="tablaevidencia table table-striped">
            <thead>
               <tr>
                  <th>Curso</th>
                  <th>Módulo</th>
                  <th>Archivo</th>
                  <th>Fecha subida</th>
                  <th>Estado</th>
                  <th>Acción</th>
               </tr>
            </thead>
            <tbody>
               <?php while ($evidencia = mysqli_fetch_assoc($evidencias)) { ?>
                  <tr>
                     <td><?= htmlspecialchars($evidencia['nombre_curso']) ?></td>
                     <td><?= htmlspecialchars($evidencia['nombre_modulo'] ?? 'Sin módulo') ?></td>
                     <td>
                        <a href="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>" target="_blank">
                           <img src="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>" alt="Archivo"
                              class="img-thumbnail" style="max-width: 120px; max-height: 80px; object-fit: contain;" />
                        </a>
                     </td>
                     <td><?= htmlspecialchars($evidencia['fecha_subida']) ?></td>
                     <td>
                        <?php
                           $estado = strtolower($evidencia['estado']);
                           $claseEstado = match ($estado) {
                              'aprobado' => 'estado-aprobado',
                              'reprobado' => 'estado-reprobado',
                              default => 'estado-pendiente',
                           };
                        ?>
                        <span class="<?= $claseEstado ?>"><?= ucfirst($estado) ?></span>
                     </td>
                     <td>
                        <form action="../../Controller/EvidenciaController.php" method="POST"
                           onsubmit="return confirm('¿Estás seguro de eliminar esta evidencia?');">
                           <input type="hidden" name="id_evidencia" value="<?= $evidencia['id_evidencia'] ?>" />
                           <button type="submit" name="eliminar_evidencia" class="btn delete-btn btn-sm">Eliminar</button>
                        </form>
                     </td>
                  </tr>
               <?php } ?>
               <?php if (mysqli_num_rows($evidencias) === 0): ?>
                  <tr>
                     <td colspan="6" class="text-center">No hay evidencias subidas aún.</td>
                  </tr>
               <?php endif; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>
