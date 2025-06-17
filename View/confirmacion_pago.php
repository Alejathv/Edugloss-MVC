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
    <style>
        body {
            background-color: #f5f3ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .contenedor-formulario {
            max-width: 600px;
            margin: 40px auto;
            background-color: #f9f7fd;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        /* Título del formulario */
        .contenedor-formulario h2 {
            font-size: 26px;
            color: #5c3fa3;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Etiquetas */
        .contenedor-formulario p {
            margin-bottom: 8px;
            color: #4b2e83;
            font-size: 15px;
        }

        /* Datos del pago */
        .datos-pago {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e0d6ff;
        }

        .datos-pago h3 {
            color: #5c3fa3;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 1px solid #e0d6ff;
            padding-bottom: 10px;
        }

        .datos-pago p strong {
            color: #4b2e83;
            font-weight: 600;
            min-width: 120px;
            display: inline-block;
        }

        /* Botón */
        .boton-principal {
            display: inline-block;
            padding: 14px 30px;
            background-color: #8656e9;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            text-align: center;
        }

        .boton-principal:hover {
            background-color: #734bd1;
            color: white;
        }

        /* Mensaje de confirmación */
        .mensaje-confirmacion {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            color: #2e7d32;
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
            <p><strong>Referencia:</strong> <?= $pago['referencia_pago'] ?? 'N/A' ?></p>
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