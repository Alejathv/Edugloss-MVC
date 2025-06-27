<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Espara la sesión de php 
session_start();
require_once __DIR__ . '/../../Controller/AdminController.php';


$adminController = new AdminController();
$usuarios = $adminController->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inicio</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style_panel.css">
    <style>
   .icons .fas,
.icons a.fas {
   font-size: 2rem;
   color: #333;
   cursor: pointer;
   margin-left: 1rem;
   transition: color 0.3s;
}

.icons .fas:hover,
.icons a.fas:hover {
   color: #9b9b9b;
}

</style>

   <!-- Metadatos básicos para la página web, que especifican la codificación de caracteres, compatibilidad y diseño responsive -->
    <!-- DataTables y extensiones necesarias -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Botones de exportación -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- Bibliotecas para PDF y Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Iniciar Sesión</title>

   <!-- Enlace para utilizar los iconos de Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- Enlace al archivo CSS personalizado -->
   <link rel="stylesheet" href="css/lista.css">

</head>
<body>




<script src="js/script.js"></script>
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
         <a href="../../documentos/Manual de administrador.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
      </div>

      <!-- Perfil del usuario, muestra la imagen, nombre y rol -->
      <div class="profile">
         <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
         <h3 class="name">
         <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
         </h3>
         <p class="role">Administrador</p>
         <a href="../perfil.php" class="btn">ver perfil</a>
         <div class="flex-btn">
         <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
         </div>
      </div>   

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
      <h3 class="name">
      <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
      </h3>
      <p class="role">Administrador</p>
      <a href="../perfil.php" class="btn">ver perfil</a>
   </div>

   <nav class="navbar">
        <a href="admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="admin_pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
        <a href="userlist.php"><i class="fas fa-users"></i><span>Gestión de Usuarios</span></a>
    </nav>

</div>

<h2 class="heading">Lista de Usuarios</h2>
<div class="contenedor-tabla-usuarios">
   <table id="tablaUsuarios" class="tablausuarios">
      <thead>
         <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
         </tr>
      </thead>
      <tbody>
         <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
               <tr>
                  <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                  <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                  <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                  <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                  <td><?= htmlspecialchars($usuario['correo']) ?></td>
                  <td><?= htmlspecialchars($usuario['rol']) ?></td>
                  <td><?= htmlspecialchars($usuario['estado']) ?></td>
                  <td>
                     <a href="editarusuario.php?id=<?= $usuario['id_usuario'] ?>" class="boton-estilo">
                        <i class="fas fa-edit"></i>
                     </a>
                  </td>
               </tr>
            <?php endforeach; ?>
         <?php else: ?>
            <tr>
               <td colspan="9" class="text-center">No hay usuarios disponibles.</td>
            </tr>
         <?php endif; ?>
      </tbody>
   </table>
</div>

<!-- SCRIPT: Esto debe ir fuera del <tbody> -->
<script>
   $(document).ready(function () {
      $('#tablaUsuarios').DataTable({
         dom: 'Bfrtip',
         buttons: [
            {
               extend: 'pdfHtml5',
               text: 'Exportar PDF',
               title: 'Lista de Usuarios',
               exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7]
               }
            },
            {
               extend: 'excelHtml5',
               text: 'Exportar Excel',
               exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7]
               }
            },
            {
               extend: 'print',
               text: 'Imprimir',
               exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5, 6, 7]
               }
            }
         ],
         language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
         }
      });
   });
</script>


<footer class="footer">

   &copy; copyright  2024 <span>EduGloss</span> | Todos los derechos reservados!

</footer>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>