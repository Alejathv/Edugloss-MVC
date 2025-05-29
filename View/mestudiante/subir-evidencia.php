<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../../Model/database.php';

$database = new Database();
$conexion = $database->getConnection();

$id_usuario = $_SESSION['user_id'];

$cursos = mysqli_query($conexion, "SELECT c.id_curso, c.nombre FROM curso c INNER JOIN inscripcion i ON c.id_curso = i.id_curso WHERE i.id_usuario = $id_usuario");
$modulos = mysqli_query($conexion, "
    SELECT m.id_modulo, m.nombre AS nombre_modulo 
    FROM modulo m
    INNER JOIN curso c ON m.id_curso = c.id_curso
    INNER JOIN inscripcion i ON c.id_curso = i.id_curso
    WHERE i.id_usuario = $id_usuario AND i.estado = 'activa'
");
$evidencias = mysqli_query($conexion, "
    SELECT e.id_evidencia, e.url_archivo, e.fecha_subida, e.estado,
           c.nombre AS nombre_curso, m.nombre AS nombre_modulo
    FROM evidencias e
    INNER JOIN curso c ON e.id_curso = c.id_curso
    INNER JOIN modulo m ON e.id_modulo = m.id_modulo
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

   <!-- custom css -->
   <link rel="stylesheet" href="../css/style_panel.css" />
</head>
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
<body>

<!-- HEADER -->
<header class="header">
   <section class="flex">
      <a href="home.html" class="logo">
         <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;" />
      </a>
      <form action="search.html" method="post" class="search-form d-none d-md-flex">
         <input
            type="text"
            name="search_box"
            required
            placeholder="buscar cursos..."
            maxlength="100"
            class="form-control"
         />
         <button type="submit" class="btn btn-primary ms-2"><i class="fas fa-search"></i></button>
      </form>
      <div class="icons d-flex align-items-center gap-3">
         <div id="menu-btn" class="fas fa-bars fs-4"></div>
         <div id="search-btn" class="fas fa-search fs-4 d-md-none"></div>
         <div id="user-btn" class="fas fa-user fs-4"></div>
         <div id="toggle-btn" class="fas fa-sun fs-4"></div>
      </div>

      <div class="profile d-flex flex-column align-items-center ms-3">
         <img src="images/Estud1.jpeg" class="image rounded-circle mb-2" alt="imagen de estudiante" style="width: 60px; height: 60px;" />
         <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
         <p class="role mb-2">Estudiante</p>
         <a href="profile.html" class="btn btn-outline-primary btn-sm">ver perfil</a>
      </div>
   </section>
</header>

<!-- SIDEBAR -->
<div class="side-bar bg-light shadow-sm">
   <div id="close-btn" class="text-end p-2">
      <i class="fas fa-times fs-4"></i>
   </div>

   <div class="profile text-center mb-4">
      <img src="images/Estud1.jpeg" class="image rounded-circle mb-2" alt="" style="width: 80px; height: 80px;" />
      <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
      <p class="role mb-2">Estudiante</p>
      <a href="profile.html" class="btn btn-outline-primary btn-sm">ver perfil</a>
   </div>

   <nav class="navbar d-flex flex-column gap-3 px-3">
      <a href="home.php" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
         <i class="fas fa-home fs-5"></i><span>Inicio</span>
      </a>
      <a href="../ForoGeneral.php" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
         <i class="fas fa-comments fs-5"></i><span>Foro General</span>
      </a>
      <a href="courses.html" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
         <i class="fas fa-graduation-cap fs-5"></i><span>Cursos</span>
      </a>
      <a href="teachers.html" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
         <i class="fas fa-chalkboard-user fs-5"></i><span>Docentes</span>
      </a>
      <a href="contact.html" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
         <i class="fas fa-headset fs-5"></i><span>Contáctanos</span>
      </a>
   </nav>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container my-5" style="margin-left: 40px; padding-right: 40px;">

   <h2 class="mb-4">Subir Evidencia</h2>
   <form
      action="../../Controller/EvidenciaController.php"
      method="POST"
      enctype="multipart/form-data"
      class="formularioevidencia"
   >
      <div class="row g-3 align-items-center">
         <div class="col-md-4">
            <label for="curso">Curso:</label>
            <select name="curso" id="curso" required>
               <?php while ($curso = mysqli_fetch_assoc($cursos)) { ?>
               <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="col-md-4">
            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" required>
               <?php while ($modulo = mysqli_fetch_assoc($modulos)) { ?>
               <option value="<?= $modulo['id_modulo'] ?>"><?= htmlspecialchars($modulo['nombre_modulo']) ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="col-md-4">
            <label for="archivo">Archivo:</label>
            <input type="file" name="archivo" id="archivo" required />
         </div>
      </div>
      <div class="mt-3">
         <button type="submit" name="subir_evidencia" class="btn btn-primary">Subir</button>
      </div>
   </form>

   <h2 class="mb-4">Mis Evidencias</h2>
   <div class="table-responsive">
      <table class="tablaevidencia">
         <thead>
            <tr>
               <th style="width: 20%;">Curso</th>
               <th style="width: 20%;">Módulo</th>
               <th style="width: 20%;">Archivo</th>
               <th style="width: 15%;">Fecha subida</th>
               <th style="width: 15%;">Estado</th>
               <th style="width: 10%;">Acción</th>
            </tr>
         </thead>
         <tbody>
            <?php while ($evidencia = mysqli_fetch_assoc($evidencias)) { ?>
            <tr>
               <td><?= htmlspecialchars($evidencia['nombre_curso']) ?></td>
               <td><?= htmlspecialchars($evidencia['nombre_modulo']) ?></td>
               <td>
                  <a
                     href="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>"
                     target="_blank"
                  >
                     <img
                        src="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>"
                        alt="Archivo"
                        class="img-thumbnail"
                        style="max-width: 120px; max-height: 80px; object-fit: contain;"
                     />
                  </a>
               </td>
               <td><?= htmlspecialchars($evidencia['fecha_subida']) ?></td>
               <td><?= htmlspecialchars(ucfirst($evidencia['estado'])) ?></td>
               <td>
                  <form
                     action="../../Controller/EvidenciaController.php"
                     method="POST"
                     onsubmit="return confirm('¿Estás seguro de eliminar esta evidencia?');"
                  >
                     <input
                        type="hidden"
                        name="id_evidencia"
                        value="<?= $evidencia['id_evidencia'] ?>"
                     />
                     <button
                        type="submit"
                        name="eliminar_evidencia"
                        class="btn btn-danger btn-sm"
                     >
                        Eliminar
                     </button>
                  </form>
               </td>
            </tr>
            <?php } ?>
            <?php if (mysqli_num_rows($evidencias) === 0) { ?>
            <tr>
               <td colspan="6" class="text-center">No hay evidencias subidas aún.</td>
            </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>

</body>
</html>
