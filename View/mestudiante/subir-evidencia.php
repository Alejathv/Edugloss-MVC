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
        /* FORMULARIO DE SUBIDA DE EVIDENCIA */
.formulario-subida {
    background-color: #f5edff;
    border: 1px solid #a875e7;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    width: 100%;
}

.formulario-subida h2 {
    color: #5e3ea1;
    font-weight: 600;
}

.formulario-subida select,
.formulario-subida input[type="file"] {
    border-radius: 8px;
    padding: 10px;
    border: 1px solid #ccc;
    width: 100%; /* Esto hace que se vea más largo */
    box-sizing: border-box; /* Para que el padding no desborde */
}

.formulario-subida .btn-primary {
    background-color: #8656e9;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 18px;
}

.formulario-subida .btn-primary:hover {
    background-color: #734bd1;
}
.contenedor-centro {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 50px; /* 100% alto de la pantalla */
    background-color: #f0f0f5; /* color de fondo opcional */
    padding: 20px;
}
/* Contenedor general de la tabla */
.tabla-evidencias {
    background-color: #f9f7fd;
    border: 1px solid #c9bdf0;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

/* Título */
.tabla-evidencias h2 {
    text-align: center;
    font-size: 24px;
    color: #5c3fa3;
    margin-bottom: 25px;
}

/* Tabla */
.tablaevidencia {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    font-size: 16px;
    border-radius: 12px;
    overflow: hidden;
}

/* Encabezado */
.tablaevidencia thead {
    background-color: #e9defb;
    color: #4b2e83;
    font-weight: bold;
}

.tablaevidencia th,
.tablaevidencia td {
    padding: 14px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #e3d7f7;
}

/* Imagen de archivo */
.tablaevidencia img {
    max-width: 120px;
    max-height: 80px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Estado */
.tablaevidencia td:nth-child(5) {
    font-weight: bold;
    text-transform: capitalize;
}

/* Botón Eliminar */
.delete-btn {
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.delete-btn:hover {
    background-color: #d94a4a;
}

/* Responsive */
@media (max-width: 768px) {
    .tablaevidencia th,
    .tablaevidencia td {
        font-size: 14px;
        padding: 10px;
    }

    .tablaevidencia img {
        max-width: 90px;
        max-height: 60px;
    }
}
/* Estados visuales */
.estado-aprobado {
    background-color: #d4edda;
    color: #155724;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: bold;
    display: inline-block;
}

.estado-reprobado {
    background-color: #f8d7da;
    color: #721c24;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: bold;
    display: inline-block;
}

.estado-pendiente {
    background-color: #fff3cd;
    color: #856404;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: bold;
    display: inline-block;
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
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
         <p class="role mb-2">Estudiante</p>
         <a href="../perfil.php" class="btn">ver perfil</a>
         <div class="flex-btn">
         <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
         </div>
   </section>
</header>

<!-- SIDEBAR -->
<div class="side-bar bg-light shadow-sm">
   <div id="close-btn" class="text-end p-2">
      <i class="fas fa-times fs-4"></i>
   </div>

   <div class="profile text-center mb-4">
      <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name mb-0"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
      <p class="role mb-2">Estudiante</p>
      <a href="../perfil.php" class="btn btn-outline-primary btn-sm">ver perfil</a>
   </div>

   <nav class="navbar">
   <a href="home.php"><i class="fas fa-home"></i><span>Inicio</span></a>
   <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
   
   <?php if (isset($_SESSION['rol_nombre'])) { ?>
      <?php if ($_SESSION['rol_nombre'] == 'estudiante') { ?>
         <!-- Enlaces para estudiantes -->
         <a href="ver_materiales.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
         <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Docentes</span></a>
      
      <?php } elseif ($_SESSION['rol_nombre'] == 'docente') { ?>
         <!-- Enlaces para docentes -->
         <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
         <a href="./Contenido.php"><i class="fas fa-edit"></i><span>Contenido</span></a>
         <a href="./evidencias.php"><i class="fas fa-users"></i><span>Evidencias</span></a>
      
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
   
   <!-- Enlace común para todos -->
   <a href="contact.html"><i class="fas fa-headset"></i><span>Contáctanos</span></a>
</nav>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container my-5" style="margin-left: 40px; padding-right: 40px;">

<!-- SECCIÓN: SUBIR EVIDENCIA -->

   <div class="d-flex justify-content-center mt-5">
   <div class="contenedor-centro">
  <div class="formulario-subida">
    <h2 class="mb-4 text-center">Subir Evidencia</h2>
    <form
      action="../../Controller/EvidenciaController.php"
      method="POST"
      enctype="multipart/form-data"
    >
      <div class="mb-3">
        <label for="curso">Curso:</label>
        <select name="curso" id="curso" class="form-control" required>
          <?php while ($curso = mysqli_fetch_assoc($cursos)) { ?>
            <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="modulo">Módulo:</label>
        <select name="modulo" id="modulo" class="form-control" required>
          <?php while ($modulo = mysqli_fetch_assoc($modulos)) { ?>
            <option value="<?= $modulo['id_modulo'] ?>"><?= htmlspecialchars($modulo['nombre_modulo']) ?></option>
          <?php } ?>
        </select>
      </div>
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


   <!-- SECCIÓN: TABLA DE EVIDENCIAS -->
   <div class="tabla-evidencias p-4 border rounded bg-white">
      <h2 class="mb-4">Mis Evidencias</h2>
      <div class="table-responsive">
         <table class="tablaevidencia table table-striped">
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
                     <a href="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>" target="_blank">
                        <img
                           src="../../documentos/<?= htmlspecialchars($evidencia['url_archivo']) ?>"
                           alt="Archivo"
                           class="img-thumbnail"
                           style="max-width: 120px; max-height: 80px; object-fit: contain;"
                        />
                     </a>
                  </td>
                  <td><?= htmlspecialchars($evidencia['fecha_subida']) ?></td>
                  <td>
   <?php
      $estado = strtolower($evidencia['estado']);
      $claseEstado = '';
      switch ($estado) {
         case 'aprobado':
            $claseEstado = 'estado-aprobado';
            break;
         case 'reprobado':
            $claseEstado = 'estado-reprobado';
            break;
         default:
            $claseEstado = 'estado-pendiente';
      }
   ?>
   <span class="<?= $claseEstado ?>">
      <?= ucfirst($estado) ?>
   </span>
</td>

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
                           class="btn delete-btn btn-sm"
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

</div>


<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>

</body>
</html>
