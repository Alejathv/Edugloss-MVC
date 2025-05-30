<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../Model/database.php";
require_once "../../Controller/DocenteController.php";
require_once '../../Controller/CursoModuloController.php';
require_once '../../Model/database.php';

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);

$cursoCtrl = new CursoController($db);
$moduloCtrl = new ModuloController($db);
$docenteCtrl = new DocenteController($conn);

// Procesar formularios si hay envío POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear_curso') {
        $cursoCtrl->crearCurso();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'crear_modulo') {
        $moduloCtrl->crearModulo();
    }
}

$cursos = $cursoCtrl->obtenerCursos();
$modulos = $moduloCtrl->obtenerModulos();

$materiales = [];
foreach ($modulos as $m) {
    $materiales[$m['id_modulo']] = $docenteCtrl->listarMateriales($m['id_modulo']);
}

$id_modulo = $_GET['id_modulo'] ?? 0;
$materiales = $controller->listarMateriales($id_modulo);
?>

<H2>CREACION DE CURSO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="crear_curso"> <!-- ESTA ES LA LÍNEA QUE FALTABA -->

    <input name="nombre" placeholder="Nombre del curso" required>
    <input name="descripcion" placeholder="Descripción" required>
    <input name="precio" type="number" step="0.01" placeholder="Precio" required>
    <input name="fecha_inicio" type="date" required>
    <input name="fecha_fin" type="date" required>
    
    <select name="estado" required>
        <option value="disponible">Disponible</option>
        <option value="cerrado">Cerrado</option>
    </select>

    <input name="imagen" placeholder="Imagen (URL o nombre)" required>

    <button type="submit" name="crear_curso">Crear Curso</button>
</form>




<H2>CREACION DE MODULO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
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

    <button type="submit" name="crear_modulo">Crear Módulo</button>
</form>




<H2>Subir por MODULO</H2> 
<form method="POST" action="" enctype="multipart/form-data">
    <select name="id_modulo" required>
        <?php foreach ($modulos as $modulo): ?>
            <option value="<?= $modulo['id_modulo'] ?>"><?= $modulo['nombre'] ?></option>
        <?php endforeach; ?>
    </select>
    <input name="nombre" placeholder="Nombre del material" required>
    <input type="file" name="archivo" required>
    <button type="submit">Subir</button>
</form>



<h3>Materiales por módulo:</h3>
<?php foreach ($modulos as $modulo): ?>
    <h4><?= $modulo['nombre'] ?></h4>
    <ul>
        <?php foreach ($materiales[$modulo['id_modulo']] ?? [] as $material): ?>
            <li>
                <?= $material['nombre'] ?> |
                <a href="<?= $material['url_video'] ?>" target="_blank">Ver</a>
                (<?= strtoupper($material['tipo']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
