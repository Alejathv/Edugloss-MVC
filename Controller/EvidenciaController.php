<?php
session_start();
require_once __DIR__ . '/../Model/database.php';

require_once __DIR__ . '/../Model/database.php';

$database = new Database(); // Instanciamos la clase 
$conn = $database->getConnection();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_evidencia"], $_POST["estado"])) {
    $id = $_POST["id_evidencia"];
    $estado = $_POST["estado"];

    $stmt = $conn->prepare("UPDATE evidencias SET estado = ? WHERE id_evidencia = ?");
    $stmt->bind_param("si", $estado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Estado actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el estado.";
    }
}

// Redirigir de nuevo al panel del docente (vista)
header("Location: ../View/docente_panel.php");
exit();