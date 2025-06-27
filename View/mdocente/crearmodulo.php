<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../../Model/database.php";
require_once "../../Controller/DocenteController.php";
require_once '../../Controller/CursoModuloController.php';

$db = new Database();
$conn = $db->getConnection();

$cursoCtrl = new CursoController($conn);
$moduloCtrl = new ModuloController($conn);

if (isset($_POST['accion']) && $_POST['accion'] === 'crear_modulo') {
    $moduloCtrl->crearModulo();
    echo "<script>
        window.parent.cerrarModalModulo();
        window.parent.location.reload();
    </script>";
    exit;
}


$cursos = $cursoCtrl->obtenerCursos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gestión de Módulos</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="../css/style_panel.css">
   <style>
      body {
         padding: 20px;
         background-color: #f3e8ff;
         font-family: 'Nunito', sans-serif;
      }
      .heading {
         text-align: center;
         font-size: 24px;
         color: #6c2bd9;
         margin-bottom: 20px;
      }
      .formulario-contenedor {
         background-color: #fff;
         border: 1px solid #d1b3ff;
         border-radius: 12px;
         padding: 20px;
         width: 450px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
         margin: 0 auto;
      }
      .formulario-titulo {
         font-size: 18px;
         font-weight: bold;
         color: #6c2bd9;
         margin-bottom: 15px;
         text-align: center;
      }
      .formulario-cuerpo input,
      .formulario-cuerpo select {
         width: 100%;
         padding: 10px;
         margin-bottom: 10px;
         border: 1px solid #ccc;
         border-radius: 8px;
         font-size: 14px;
      }
      .botones-flex {
         display: flex;
         justify-content: center;
         align-items: center;
      }
      .botones-flex button {
         background-color: #6c2bd9;
         color: white;
         padding: 10px 16px;
         border-radius: 8px;
         border: none;
         cursor: pointer;
         font-size: 14px;
      }
      .botones-flex button:hover {
         opacity: 0.9;
      }
   </style>
</head>
<body>

<h2 class="heading">Crear Módulo</h2>

<div class="formulario-contenedor">
   <div class="formulario-titulo">Información del Módulo</div>
   <form method="POST" class="formulario-cuerpo">
      <input type="hidden" name="accion" value="crear_modulo">
      <select name="id_curso" required>
         <option value="">Selecciona un curso</option>
         <?php foreach ($cursos as $curso): ?>
            <option value="<?= $curso['id_curso'] ?>"><?= $curso['nombre'] ?></option>
         <?php endforeach; ?>
      </select>
      <input name="nombre" placeholder="Nombre del módulo" required>
      <input name="descripcion" placeholder="Descripción" required>
      <input name="precio" type="number" step="0.01" placeholder="Precio" required>
      <select name="estado" required>
         <option value="disponible">Disponible</option>
         <option value="cerrado">Cerrado</option>
      </select>
      <div class="botones-flex">
         <button type="submit">Crear</button>
      </div>
   </form>
</div>

</body>
</html>
