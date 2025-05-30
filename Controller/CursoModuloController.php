<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Model/CursoModel.php';
require_once __DIR__ . '/../Model/ModuloModel.php';
require_once __DIR__ . '/../Model/database.php';

class CursoController {
    private $model;

    public function __construct($db) {
        $this->model = new CursoModel ($db);
    }
    public function crearCurso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_curso'])) {
            // Verificamos los datos
            var_dump($_POST); // Esto para ver que lleguen los datos del formulario
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];
            $imagen = $_POST['imagen'];

            $resultado = $this->model->crearCurso($nombre, $descripcion, $precio, $fecha_inicio, $fecha_fin, $estado, $imagen);
            var_dump($resultado); // Para ver si devolvió true o false

            if ($resultado) {
                header("Location: CursoModuloMaterial.php?success=curso");
                exit;
            } else {
                echo "Error al crear curso.";
            }
        }
    }
    

    public function obtenerCursos() {
        return $this->model->obtenerCursos();
    }
}

class ModuloController {
    private $model;

    public function __construct($db) {
        $this->model = new ModuloModel($db);
    }

    public function crearModulo() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'crear_modulo') {
        $id_curso = $_POST['id_curso'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        if (!empty($id_curso) && !empty($nombre) && !empty($descripcion)) {
            $resultado = $this->model->crearModulo($id_curso, $nombre, $descripcion, $precio);
            if ($resultado) {
                // Redirige después de crear para evitar repost
                header("Location: CursoModuloMaterial.php?success=modulo_creado");
                exit;  // Importante para detener el script
            } else {
                echo "Error al crear módulo.";
            }
        } else {
            echo "Faltan datos.";
        }
    }


}

    public function obtenerModulos() {
        return $this->model->obtenerModulos();
    }
    public function mostrarFormulario() {
    require_once __DIR__ . '/../Model/CursoModel.php';
    $cursoModel = new CursoModel($db);
    $cursos = $cursoModel->obtenerCursos(); // Trae los cursos desde el modelo

    // Carga la vista y le pasa los cursos disponibles
    require_once __DIR__ . '/../View/mdocente/CursoModuloMaterial.php';
}
}
