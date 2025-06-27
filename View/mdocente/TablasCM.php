<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../../Model/database.php";
require_once "../../Controller/DocenteController.php";
require_once '../../Controller/CursoModuloController.php';

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);

$cursoCtrl = new CursoController($conn);
$moduloCtrl = new ModuloController($conn);
$docenteCtrl = new DocenteController($conn);

// Procesar formularios si hay envío POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear_curso') {
        $cursoCtrl->crearCurso();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'crear_modulo') {
        $moduloCtrl->crearModulo();
    }
} elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_curso') {
    $cursoCtrl->eliminarCurso();
} elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_modulo') {
    $moduloCtrl->eliminarModulo();
}

$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();
$cursoCtrl->eliminarCurso(); 
$moduloCtrl->eliminarModulo();

$id_modulo = $_GET['id_modulo'] ?? 0;
$materiales = $controller->listarMateriales($id_modulo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inicio</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="../css/style_panel.css">

   <style>
      /* Botones de acción */
      .acciones {
         display: flex;
         gap: 5px;
         justify-content: center;
      }

      .boton-estilo {
         margin: 0;
         padding: 0;
         border: none;
         background: none;
      }

      .boton-estilo button,
      .boton-estilo a {
         padding: 5px 10px;
         border-radius: 4px;
         color: white;
         cursor: pointer;
         display: inline-flex;
         align-items: center;
         justify-content: center;
         text-decoration: none;
         font-size: 14px;
      }

      .acciones form:first-child button {
         background-color: #28a745 !important;
      }

      .boton-estilo a {
         background-color: #007bff;
      }

      .boton-estilo button[type="submit"]:last-child {
         background-color: #dc3545;
      }

      .boton-estilo button:hover,
      .boton-estilo a:hover {
         opacity: 0.8;
         transform: scale(1.05);
      }

      .boton-estilo i {
         margin: 0;
      }

      /* Contenedor estilo formulario */
      .contenedor-estilo {
         max-width: 95%;
         background-color: #f3e8ff;
         border: 1px solid #d1b3ff;
         border-radius: 16px;
         padding: 2rem;
         margin: 2rem auto;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
         font-family: 'Nunito', sans-serif;
      }

      .titulo-morado {
         background-color: #e2cfff;
         color: #6c2bd9;
         padding: 1rem 1.5rem;
         border-radius: 12px;
         font-size: 2rem;
         text-align: center;
         font-weight: bold;
         margin-bottom: 1.5rem;
      }

      .tabla-contenedor {
         overflow-x: auto;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         background-color: #fff;
         border-radius: 12px;
         overflow: hidden;
         box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      }

      th {
         background-color: #d5c5f4;
         color: #44237a;
         font-size: 16px;
         font-weight: 700;
         padding: 12px 15px;
         text-align: left;
      }

      td {
         padding: 12px 15px;
         border-bottom: 1px solid #ddd;
         text-align: left;
         font-size: 16px;
      }

      tr:nth-child(even) {
         background-color: #f9f5ff;
      }

      tr:hover {
         background-color: #f2ecfd;
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
      <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name"><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h3>
      <p class="role">Docente</p>
      <a href="profile.html" class="btn">Ver Perfil</a>
   </div>
   <nav class="navbar">
      <a href="docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
      <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
      <?php if (isset($_SESSION['rol_nombre'])) { ?>
         <?php if ($_SESSION['rol_nombre'] == 'docente') { ?>
            <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion De Aprendizaje</span></a>
            <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
            <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
         <?php } elseif ($_SESSION['rol_nombre'] == 'administrador') { ?>
            <a href="gestion_usuarios.php"><i class="fas fa-user-cog"></i><span>Gestión de Usuarios</span></a>
            <a href="gestion_cursos_admin.php"><i class="fas fa-book"></i><span>Gestión de Cursos</span></a>
            <a href="reportes.php"><i class="fas fa-chart-bar"></i><span>Reportes</span></a>
         <?php } ?>
      <?php } else { ?>
         <a href="login.php"><i class="fas fa-sign-in-alt"></i><span>Iniciar Sesión</span></a>
      <?php } ?>
   </nav>
</div>

<!-- CURSOS -->
<div class="contenedor-estilo">
   <div class="titulo-morado">Cursos Registrados</div>
   <div class="tabla-contenedor">
      <table>
         <thead>
            <tr>
               <th>Curso</th>
               <th>Descripción</th>
               <th>Precio</th>
               <th>Fechas</th>
               <th>Estado</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($cursos as $curso): ?>
            <tr>
               <td><?= htmlspecialchars($curso['nombre']) ?></td>
               <td><?= htmlspecialchars($curso['descripcion']) ?></td>
               <td>$<?= number_format($curso['precio'], 2) ?></td>
               <td><?= $curso['fecha_inicio'] ?> - <?= $curso['fecha_fin'] ?></td>
               <td><?= ucfirst($curso['estado']) ?></td>
               <td>
                  <div class="acciones">
                     <form method="GET" action="CursoModulo.php" class="boton-estilo">
                        <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                        <button type="submit"><i class="fa-solid fa-plus"></i></button>
                     </form>
                     <a href="editar_curso.php?id=<?= $curso['id_curso'] ?>" class="boton-estilo"><i class="fas fa-edit"></i></a>
                     <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este curso y sus módulos?')" class="boton-estilo">
                        <input type="hidden" name="accion" value="eliminar_curso">
                        <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                        <button type="submit"><i class="fas fa-trash"></i></button>
                     </form>
                  </div>
               </td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- MÓDULOS -->
<div class="contenedor-estilo">
   <div class="titulo-morado">Módulos por Curso</div>
   <div class="tabla-contenedor">
      <table>
         <thead>
            <tr>
               <th>Curso</th>
               <th>Módulo</th>
               <th>Descripción</th>
               <th>Precio</th>
               <th>Estado</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($cursos as $curso): ?>
               <?php
                  $modulosCurso = $moduloCtrl->obtenerModulosPorCurso($curso['id_curso']);
                  foreach ($modulosCurso as $modulo):
               ?>
               <tr>
                  <td><?= htmlspecialchars($curso['nombre']) ?></td>
                  <td><?= htmlspecialchars($modulo['nombre']) ?></td>
                  <td><?= htmlspecialchars($modulo['descripcion']) ?></td>
                  <td>$<?= number_format($modulo['precio'], 2) ?></td>
                  <td><?= ucfirst($modulo['estado']) ?></td>
                  <td>
                     <div class="acciones">
                        <form method="GET" action="CursoModulo.php" class="boton-estilo">
                           <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">
                           <button type="submit"><i class="fa-solid fa-plus"></i></button>
                        </form>
                        <a href="editar_modulo.php?id=<?= $modulo['id_modulo'] ?>" class="boton-estilo"><i class="fas fa-edit"></i></a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este módulo?')" class="boton-estilo">
                           <input type="hidden" name="accion" value="eliminar_modulo">
                           <input type="hidden" name="id_modulo" value="<?= $modulo['id_modulo'] ?>">
                           <button type="submit"><i class="fas fa-trash"></i></button>
                        </form>
                     </div>
                  </td>
               </tr>
               <?php endforeach; ?>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>

<footer class="footer">
   &copy; copyright 2024 <span>EduGloss</span> | Todos los derechos reservados!
</footer>

<script src="../js/script.js"></script>
</body>
</html>
