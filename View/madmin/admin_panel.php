<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Verificar si la sesión no está iniciada antes de llamar a session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar rol de administrador
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'administrador') {
    $_SESSION['error_acceso'] = "Acceso no autorizado";
    header('Location: /Edugloss-MVC/login.php');
    exit;
}

if (isset($_SESSION['mensaje_bienvenida'])) {
    echo '<div id="mensaje-bienvenida" style="color: green; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['mensaje_bienvenida']) . '</div>';
    unset($_SESSION['mensaje_bienvenida']);
}

require_once "../../Model/database.php";
require_once "../../Controller/PagoController.php";

$database = new Database();
$conn = $database->getConnection();
$pagoController = new PagoController($database);

// Obtener pagos pendientes
$pagos = $pagoController->obtenerPagosPendientes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Pagos</title>
    <link rel="stylesheet" href="../css/style_panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-size: 18px;
        }
        .contenedor-centro {
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: #f0f0f5;
        }

        .tabla-pagos {
            background-color: #f9f7fd;
            border: 1px solid #c9bdf0;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .tabla-pagos h2 {
            text-align: center;
            font-size: 24px;
            color: #5c3fa3;
            margin-bottom: 25px;
        }

        .tablapagos {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            font-size: 16px;
            border-radius: 12px;
            overflow: hidden;
        }

        .tablapagos thead {
            background-color: #e9defb;
            color: #4b2e83;
            font-weight: bold;
        }

        .tablapagos th,
        .tablapagos td {
            padding: 14px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #e3d7f7;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: white;
        }

        .btn-icon.aprobar {
            background-color: #28a745;
        }

        .btn-icon.aprobar:hover {
            background-color: #218838;
        }

        .btn-icon.rechazar {
            background-color: #dc3545;
        }

        .btn-icon.rechazar:hover {
            background-color: #c82333;
        }

        .comprobante-link {
            color: #5c3fa3;
            text-decoration: none;
            font-weight: bold;
        }

        .comprobante-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="admin_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <img src="../img/icon1.png" class="image" alt="">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
            </h3>
            <p class="role">Administrador</p>
            <div class="flex-btn">
                <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
            </div>
        </div>
    </section>
</header>

<div class="side-bar">
    <div id="close-btn"><i class="fas fa-times"></i></div>
    <div class="profile">
        <img src="../img/icon1.png" class="image" alt="">
        <h3 class="name">
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
        </h3>
        <p class="role">Administrador</p>
    </div>
    <nav class="navbar">
        <a href="admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
        <a href="usuarios.php"><i class="fas fa-users"></i><span>Usuarios</span></a>
        <a href="cursos.php"><i class="fas fa-graduation-cap"></i><span>Cursos</span></a>
        <a href="reportes.php"><i class="fas fa-chart-bar"></i><span>Reportes</span></a>
    </nav>
</div>

<section class="main-content">
    <div class="dashboard contenedor-centro">
        <div class="tabla-pagos">
            <h2>Pagos Pendientes</h2>
            <div class="table-responsive">
                <table class="tablapagos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Comprobante</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pagos)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No hay pagos pendientes</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pagos as $pago): 
                                $detalles = json_decode($pago['detalles_pago'], true);
                                $montoTotal = array_reduce($detalles, function($carry, $item) {
                                    return $carry + $item['precio'];
                                }, 0);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($pago['id_pago']) ?></td>
                                <td><?= htmlspecialchars($pago['nombre'] . ' ' . $pago['apellido']) ?></td>
                                <td><?= htmlspecialchars($pago['correo']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                <td>$<?= number_format($montoTotal, 2) ?></td>
                                <td>
                                    <a href="../documentos/<?= htmlspecialchars($pago['referencia_pago']) ?>" 
                                       target="_blank" 
                                       class="comprobante-link">
                                        Ver comprobante
                                    </a>
                                </td>
                                <td>
                                    <a href="aprobar_pago.php?id=<?= $pago['id_pago'] ?>&estado=completado" 
                                       class="btn-icon aprobar" 
                                       title="Aprobar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="aprobar_pago.php?id=<?= $pago['id_pago'] ?>&estado=cancelado" 
                                       class="btn-icon rechazar" 
                                       title="Rechazar">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</section>

<script src="../js/script.js"></script>
<script src="../js/mensajes.js"></script>
</body>
</html>