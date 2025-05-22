<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../Model/MaterialModel.php';

class DocenteController {
    private $materialModel;

    public function __construct($conn) {
        $this->materialModel = new MaterialModel($conn);
    }

    public function subirMaterial() {
        echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";

    if (!isset($_FILES['archivo'])) {
        echo "⚠️ No se encontró el archivo en \$_FILES.";
        return;
    }

    if ($_FILES['archivo']['error'] !== 0) {
        echo "⚠️ Error al subir: " . $_FILES['archivo']['error'];
        return;
    }
        // Validar datos recibidos
    $id_modulo = $_POST['id_modulo'] ?? null;
    $nombre = $_POST['nombre'] ?? null;

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        if ($_FILES['archivo']['size'] > 100 * 1024 * 1024) {  // 100 MB en bytes
        die("El archivo es demasiado grande. El límite es 100 MB.");
    }
        $archivo = $_FILES['archivo']['name'];
        $tmp_archivo = $_FILES['archivo']['tmp_name'];
        // Obtener extensión
        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
        $permitidos = ['pdf', 'mp4', 'avi', 'mov'];

        if (in_array(strtolower($extension), $permitidos)) {
            // Crear un nombre único para evitar sobreescritura
            $nuevo_nombre = uniqid() . "." . $extension;

            // Carpeta donde se guardan los archivos (debe tener permisos de escritura)
            $ruta_carpeta = "documentos/";  // Por ejemplo, dentro de tu proyecto
            if (!is_dir($ruta_carpeta)) {
                mkdir($ruta_carpeta, 0755, true);
            }

            // Ruta completa en servidor
            $ruta_archivo = $ruta_carpeta . $nuevo_nombre;

            // Mover el archivo temporal a la carpeta definitiva
            if (move_uploaded_file($tmp_archivo, $ruta_archivo)) {
                // Generar la URL pública para acceder
                // Aquí suponemos que tu sitio está en http://localhost/Edugloss-MVC/
                $url = "http://localhost/Edugloss-MVC/" . $ruta_archivo;

                // Guardar $url en la base de datos para asociar con módulo, nombre, tipo, etc.
                // Ejemplo (pseudocódigo):
                // $this->modelo->guardarArchivo($id_modulo, $nombre, $tipo, $url);

                echo "Archivo subido correctamente. URL: " . $url;
            } else {
                echo "Error al mover el archivo.";
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    } else {
        echo "No se seleccionó archivo o hubo error al subir.";
    }
    }

    public function listarMateriales($id_modulo) {
        return $this->materialModel->getMaterialesByModulo($id_modulo);
    }
}
