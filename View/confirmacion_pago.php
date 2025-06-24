<?php
// Activar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Registrar errores en un archivo
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');

require_once '../Model/database.php';
require_once '../Model/PagoModel.php';

try {
    $db = new Database();
    $pagoModel = new PagoModel($db->getConnection());
    
    if(!isset($_GET['id'])) {
        throw new Exception("ID de pago no proporcionado");
    }

    $idPago = (int)$_GET['id'];
    $pago = $pagoModel->obtenerPagoPorId($idPago);

    if(!$pago) {
        throw new Exception("Pago $idPago no encontrado");
    }
} catch (Exception $e) {
    error_log("Error en confirmacion_pago: ".$e->getMessage());
    header("Location: error_pago.php?msg=".urlencode($e->getMessage()));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pago #<?= $idPago ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../View/css/style_panel.css">
    <style>
        body {
            background-color: #f5f3ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="contenedor-formulario">
        <h2>¡Pago Registrado Exitosamente!</h2>
        <p style="text-align: center; font-size: 18px; color: #5c3fa3;">ID de transacción: <strong>#<?= $idPago ?></strong></p>
        
        <div class="datos-pago">
            <h3>Detalles del pago:</h3>
            <p><strong>Producto:</strong> <?= htmlspecialchars($pago['nombre_producto'] ?? 'N/A') ?></p>
            <p><strong>Monto:</strong> $<?= number_format($pago['monto'] ?? 0, 2) ?></p>
            <p><strong>Método:</strong> <?= $pago['metodo_pago'] ?? 'N/A' ?></p>
        </div>

        <div class="mensaje-confirmacion">
            <i class="fas fa-check-circle"></i> Gracias por realizar tu registro. En los próximos días, recibirás un correo electrónico con tus credenciales de acceso. Ten en cuenta que primero el administrador debe confirmar tu pago para completar el proceso de inscripción.
        </div>

        <div style="text-align: center;">
            <a href="../index.php" class="boton-principal">
                <i class="fas fa-home"></i> Volver a inicio
            </a>
        </div>
    </div>

    <?php
    // Debug final (solo en desarrollo)
    if(isset($_GET['debug'])) {
        echo '<pre style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e0d6ff;">';
        print_r($pago);
        echo '</pre>';
    }
    ?>
</body>
</html>