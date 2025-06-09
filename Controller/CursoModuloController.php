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

            $resultado = $this->model->crearCurso($nombre, $descripcion, $precio, $fecha_inicio, $fecha_fin, $estado);
            var_dump($resultado); // Para ver si devolvió true o false

            if ($resultado) {
                header("Location: TablasCM.php?success=curso");
                exit;
            } else {
                echo "Error al crear curso.";
            }
        }
    }
    

    public function obtenerCursos() {
        return $this->model->obtenerCursos();
    }
    public function eliminarCurso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar_curso') {
            $id_curso = $_POST['id_curso'];
            $this->model->eliminarCurso($id_curso);
            header("Location: TablasCM.php?deleted=curso");
            exit;
        }
    }
    public function obtenerCursoPorId($id) {
    return $this->model->obtenerCursoPorId($id);
    }

    public function actualizarCurso($data) {
        $this->model->actualizarCurso($data);
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
            $estado = $_POST['estado'];

            if (!empty($id_curso) && !empty($nombre) && !empty($descripcion)) {
                $resultado = $this->model->crearModulo($id_curso, $nombre, $descripcion, $precio);
                if ($resultado) {
                    // Redirige después de crear para evitar repost
                    header("Location: TablasCM.php?success=modulo_creado");
                    exit;  // Importante para detener el script
                } else {
                    echo "Error al crear módulo.";
                }
            } else {
                echo "Faltan datos.";
            }
        }
    }

    public function eliminarModulo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar_modulo') {
            $id_modulo = $_POST['id_modulo'];
            $this->model->eliminarModulo($id_modulo);
            header("Location: TablasCM.php?deleted=modulo");
            exit;
        }
    }
    public function obtenerModulosPorCurso($idCurso){
        return $this-> model->obtenerModulosPorCurso($idCurso);
    }

    public function obtenerModulos() {
        return $this->model->obtenerModulos();
    }
    public function obtenerModuloPorId($id) {
    return $this->model->obtenerModuloPorId($id);
    }

    public function actualizarModulo($data) {
        $this->model->actualizarModulo($data);
    }


    public function mostrarFormulario() {
    require_once __DIR__ . '/../Model/CursoModel.php';
    $cursoModel = new CursoModel($db);
    $cursos = $cursoModel->obtenerCursos(); // Trae los cursos desde el modelo

    // Carga la vista y le pasa los cursos disponibles
    require_once __DIR__ . '/../View/mdocente/CursoModulo.php';

    
}
}
