<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../Model/database.php';
require_once '../Model/PagoModel.php';
require_once '../Model/ClienteModel.php';

if (isset($_POST['ajax']) && $_POST['ajax'] === 'buscar_cliente' && isset($_POST['email'])) {
    require_once '../Model/database.php';
    require_once '../Model/ClienteModel.php';

    $db = new Database();
    $conn = $db->getConnection();
    $clienteModel = new ClienteModel($conn);

    $cliente = $clienteModel->buscarClientePorEmail($_POST['email']);

    if ($cliente) {
        echo json_encode([
            'success' => true,
            'nombre' => $cliente['nombre'],
            'apellido' => $cliente['apellido'],
            'telefono' => $cliente['telefono']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit(); // Finaliza si fue una solicitud AJAX
}

// Procesar cuando se envía el formulario completo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pago'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // 1. Guardar cliente
    $clienteModel = new ClienteModel($conn);
    $cliente = $clienteModel->buscarClientePorEmail($_POST['email']);
    
    if ($cliente) {
        $idCliente = $cliente['id_usuario'];
        $clienteModel->actualizarCliente($idCliente, $_POST['nombre'], $_POST['apellido'], $_POST['telefono']);
    } else {
        $idCliente = $clienteModel->registrarCliente($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono']);
    }

// 2. Guardar pago y comprobante
$pagoModel = new PagoModel($conn);

// Validar archivo subido
$nombreOriginal = $_FILES['comprobante']['name'];
$extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
$permitidos = ['pdf', 'jpg', 'jpeg', 'png'];

if (!in_array($extension, $permitidos)) {
    die("Error: Solo se permiten archivos PDF o imágenes (JPG, JPEG, PNG).");
}

$mime = mime_content_type($_FILES['comprobante']['tmp_name']);
$mime_permitidos = ['application/pdf', 'image/jpeg', 'image/png'];

if (!in_array($mime, $mime_permitidos)) {
    die("Error: El tipo MIME del archivo no es válido.");
}

// Si pasa la validación, mover archivo
$nombreArchivo = 'comprobante_'.time().'.'.$extension;
$rutaDestino = "../documentos/" . $nombreArchivo;
move_uploaded_file($_FILES['comprobante']['tmp_name'], $rutaDestino);

// Registrar pago
if ($_POST['tipo_producto'] === 'curso') {
    $referencia = $_POST['referencia'] ?? null;
    $idPago = $pagoModel->crearPagoCurso(
        $idCliente,
        $_POST['id_producto'],
        $_POST['precio_producto'],
        'transferencia',
        $referencia,
        $nombreArchivo
    );
} else {
    $idPago = $pagoModel->crearPagoModulo(
        $idCliente,
        $_POST['id_producto'],
        $_POST['precio_producto'],
        'transferencia',
        $_POST['referencia'],
        $nombreArchivo
    );
}

// Redirigir a confirmación
sleep(1); 
header("Location: confirmacion_pago.php?id=" . $idPago);
exit();

}

// Si viene de planes.php (primer paso)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $producto = [
        'tipo' => $_POST['tipo_producto'],
        'id' => $_POST['id_producto'],
        'nombre' => $_POST['nombre_producto'],
        'precio' => $_POST['precio_producto']
    ];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Completar Pago | Edugloss</title>
    <link href="../View/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f3ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .contenedor-formulario h3 {
            font-size: 26px;
            color: #5c3fa3;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Etiquetas */
        .contenedor-formulario p {
            margin-bottom: 8px;
            color: #4b2e83;
            font-weight: 600;
            font-size: 15px;
        }

        /* Campos de entrada */
        .campo-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #c9bdf0;
            border-radius: 12px;
            font-size: 15px;
            margin-bottom: 20px;
            background-color: #ffffff;
            transition: border-color 0.3s ease;
        }

        .campo-input:focus {
            outline: none;
            border-color: #8656e9;
        }

        /* Botón de enviar */
        .boton-enviar {
            width: 100%;
            padding: 14px;
            background-color: #8656e9;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
        }

        .boton-enviar:hover {
            background-color: #734bd1;
        }

        /* Área de subida de archivos */
        .file-upload-area {
            border: 2px dashed #c9bdf0;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            margin: 25px 0;
            background-color: #ffffff;
        }

        .file-upload-area:hover {
            border-color: #8656e9;
        }

        /* Link de pago */
        .payment-link-box {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
            border: 1px solid #e0d6ff;
        }

        .payment-link {
            display: inline-block;
            background-color: #5c3fa3;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .payment-link:hover {
            background-color: #4a2f8b;
            color: white;
        }

        /* Info del producto */
        .producto-info {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e0d6ff;
        }

        .producto-info h4 {
            color: #5c3fa3;
            margin-bottom: 15px;
        }

        .producto-info p {
            margin-bottom: 8px;
            color: #4b2e83;
        }
    </style>
</head>
<body>
    <div class="contenedor-formulario">
        <h3>Completar Pago</h3>
        
        <!-- Datos del Producto -->
        <div class="producto-info">
            <h4><?= htmlspecialchars($producto['nombre']) ?></h4>
            <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
            <p><strong>Tipo:</strong> <?= ucfirst($producto['tipo']) ?></p>
        </div>

        <!-- Link de Pago Fijo -->
        <div class="payment-link-box">
            <h5>Realiza tu pago aquí:</h5>
            <a href="https://www.mercadopago.com.mx/checkout/v1/redirect?pref_id=EDUGLOSS123" 
               class="payment-link" 
               target="_blank">
               <i class="fas fa-external-link-alt"></i> Pagar con MercadoPago
            </a>
            <p style="margin-top: 15px; color: #666; font-size: 14px;">ID de referencia: <?= $producto['id'] ?></p>
        </div>

        <!-- Formulario de Datos del Cliente -->
        <form method="POST" enctype="multipart/form-data">
            <h5 style="color: #5c3fa3; margin-bottom: 15px;">Tus Datos</h5>
            
            <div>
                <p>Nombre</p>
                <input type="text" name="nombre" class="campo-input" required>
            </div>
            
            <div>
                <p>Apellido</p>
                <input type="text" name="apellido" class="campo-input" required>
            </div>
            
            <div>
                <p>Email</p>
                <input type="email" name="email" class="campo-input" required>
            </div>
            
            <div>
                <p>Teléfono</p>
                <input type="tel" name="telefono" class="campo-input" required>
            </div>

            <!-- Comprobante de Pago -->
            <div class="file-upload-area">
                <h5 style="color: #5c3fa3; margin-bottom: 15px;">Subir Comprobante</h5>
                <input type="file" name="comprobante" class="campo-input" 
                       accept=".pdf,.jpg,.jpeg,.png" required>
                <p style="color: #666; font-size: 14px; margin-top: 10px;">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</p>
            </div>

            <!-- Campos ocultos -->
            <input type="hidden" name="tipo_producto" value="<?= $producto['tipo'] ?>">
            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
            <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($producto['nombre']) ?>">
            <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
            <input type="hidden" name="confirmar_pago" value="1">

            <button type="submit" class="boton-enviar">Finalizar Proceso</button>
        </form>
    </div>

    <script src="../View/js/bootstrap.bundle.min.js"></script>
    <script>
document.querySelector('input[name="email"]').addEventListener('blur', function () {
    const email = this.value.trim();
    if (!email) return;

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'ajax=buscar_cliente&email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('input[name="nombre"]').value = data.nombre;
            document.querySelector('input[name="apellido"]').value = data.apellido;
            document.querySelector('input[name="telefono"]').value = data.telefono;

            document.querySelector('input[name="nombre"]').readOnly = true;
            document.querySelector('input[name="apellido"]').readOnly = true;
            document.querySelector('input[name="telefono"]').readOnly = true;
        } else {
            document.querySelectorAll('.campo-input').forEach(el => el.readOnly = false);
        }
    });
});
</script>
</body>
</html>
<?php
} else {
    header("Location: planes.php");
    exit();
}