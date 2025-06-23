<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../Model/database.php';

$database = new Database();
$conexion = $database->getConnection();

// SUBIR EVIDENCIA (Estudiante)
if (isset($_POST['subir_evidencia'])) {
    $id_usuario = $_SESSION['user_id'] ?? null;
    $id_curso = isset($_POST['curso']) ? intval($_POST['curso']) : null;
    $id_modulo = isset($_POST['modulo']) && $_POST['modulo'] !== '' ? intval($_POST['modulo']) : null;
    $archivo = $_FILES['archivo'] ?? null;

    if (!$id_usuario || !$id_curso || !$archivo) {
        echo "Faltan datos obligatorios.";
        exit();
    }

    $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
    $ruta = '../documentos/' . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
        // Verifica si se pasa módulo o no, para insertar correctamente
        if ($id_modulo !== null) {
            $stmt = $conexion->prepare("INSERT INTO evidencias (id_usuario, id_curso, id_modulo, url_archivo, estado) VALUES (?, ?, ?, ?, 'pendiente')");
            $stmt->bind_param('iiis', $id_usuario, $id_curso, $id_modulo, $nombreArchivo);
        } else {
            $stmt = $conexion->prepare("INSERT INTO evidencias (id_usuario, id_curso, url_archivo, estado) VALUES (?, ?, ?, 'pendiente')");
            $stmt->bind_param('iis', $id_usuario, $id_curso, $nombreArchivo);
        }

        $stmt->execute();
        header("Location: ../View/mestudiante/subir-evidencia.php?msg=ok");
        exit();
    } else {
        echo "Error al subir el archivo.";
        exit();
    }
}

// ELIMINAR EVIDENCIA (Estudiante)
if (isset($_POST['eliminar_evidencia'])) {
    $id_evidencia = intval($_POST['id_evidencia']);
    $id_usuario = $_SESSION['user_id'] ?? null;

    if (!$id_usuario || !$id_evidencia) {
        echo "Datos inválidos.";
        exit();
    }

    $stmt = $conexion->prepare("SELECT url_archivo FROM evidencias WHERE id_evidencia = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $id_evidencia, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $archivoEliminar = '../documentos/' . $fila['url_archivo'];

        if (file_exists($archivoEliminar)) {
            unlink($archivoEliminar);
        }

        $stmtDelete = $conexion->prepare("DELETE FROM evidencias WHERE id_evidencia = ? AND id_usuario = ?");
        $stmtDelete->bind_param('ii', $id_evidencia, $id_usuario);
        $stmtDelete->execute();
    }

    header("Location: ../View/mestudiante/subir-evidencia.php?deleted=ok");
    exit();
}

// CALIFICAR EVIDENCIA (Docente)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_evidencia"], $_POST["estado"])) {
    $id = intval($_POST["id_evidencia"]);
    $estado = $_POST["estado"];
    $estados_validos = ['aprobado', 'reprobado', 'pendiente'];

    if (!in_array($estado, $estados_validos)) {
        $_SESSION['error'] = "Estado inválido.";
        header("Location: ../View/mdocente/docente_panel.php");
        exit();
    }

    $stmt = $conexion->prepare("UPDATE evidencias SET estado = ? WHERE id_evidencia = ?");
    $stmt->bind_param("si", $estado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Estado actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el estado.";
    }

    header("Location: ../View/mdocente/docente_panel.php");
    exit();
}
