<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../Model/database.php';

$database = new Database();
$conexion = $database->getConnection();

// Subir evidencia (estudiante)
if (isset($_POST['subir_evidencia'])) {
    $id_usuario = $_SESSION['user_id'];
    $id_curso = intval($_POST['curso']);
    $modulo = intval($_POST['modulo']);
    $archivo = $_FILES['archivo'];

    $nombreArchivo = uniqid() . '_' . $archivo['name'];
    $ruta = '../documentos/' . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
        $stmt = $conexion->prepare("INSERT INTO evidencias (id_usuario, id_curso, id_modulo, url_archivo, estado) VALUES (?, ?, ?, ?, 'pendiente')");
        $stmt->bind_param('iiis', $id_usuario, $id_curso, $modulo, $nombreArchivo);
        $stmt->execute();

        header("Location: ../View/mestudiante/subir-evidencia.php?msg=ok");
        exit();
    } else {
        echo "Error al subir archivo.";
        exit();
    }
}

// Eliminar evidencia (estudiante)
if (isset($_POST['eliminar_evidencia'])) {
    $id_evidencia = intval($_POST['id_evidencia']);
    $id_usuario = $_SESSION['user_id'];

    // Primero obtener el nombre del archivo para eliminarlo del servidor
    $stmt = $conexion->prepare("SELECT url_archivo FROM evidencias WHERE id_evidencia = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $id_evidencia, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $archivoEliminar = '../documentos/' . $fila['url_archivo'];

        // Eliminar el archivo si existe
        if (file_exists($archivoEliminar)) {
            unlink($archivoEliminar);
        }

        // Eliminar la entrada en la base de datos
        $stmtDelete = $conexion->prepare("DELETE FROM evidencias WHERE id_evidencia = ? AND id_usuario = ?");
        $stmtDelete->bind_param('ii', $id_evidencia, $id_usuario);
        $stmtDelete->execute();
    }

    // Redirigir al mismo lugar después de eliminar
    header("Location: ../View/mestudiante/home.php");
    exit();
}

// Calificar evidencia (docente)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_evidencia"], $_POST["estado"])) {
    $conn = $conexion;

    $id = intval($_POST["id_evidencia"]);
    $estado = $_POST["estado"];
    $estados_validos = ['aprobado', 'reprobado', 'pendiente'];

    if (!in_array($estado, $estados_validos)) {
        $_SESSION['error'] = "Estado inválido.";
        header("Location: ../View/mdocente/docente_panel.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE evidencias SET estado = ? WHERE id_evidencia = ?");
    $stmt->bind_param("si", $estado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Estado actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el estado.";
    }

    header("Location: ../View/mdocente/docente_panel.php");
    exit();
}

