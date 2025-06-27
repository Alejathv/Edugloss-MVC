<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../Controller/PagoController.php';
require_once __DIR__ . '/../../Model/database.php';

session_start();

$db = new Database();
$pagoController = new PagoController($db->getConnection());

// Procesar inscripción
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'inscribir') {
    $idPago = $_GET['id'];
    
    // Obtener datos del pago
    $pago = $pagoController->obtenerPagoPorId($idPago);
    
    if ($pago['estado'] !== 'completado') {
        $_SESSION['mensaje'] = "No se puede inscribir: el pago no está completado.";
    } else {
        require_once __DIR__ . '/../../Controller/inscripcioncontroller.php';
        $inscripcionController = new InscripcionController($db->getConnection());
        
        $resultado = $inscripcionController->procesarInscripcion($idPago, [
            'id_usuario' => $pago['id_usuario'],
            'id_curso' => $pago['id_curso'],
            'id_modulo' => $pago['id_modulo']
        ]);
        
        $_SESSION['mensaje'] = $resultado['message'];
    }
    
    header("Location: admin_pagos.php");
    exit();
}

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    $idPago = $_GET['id'];
    
    if ($_GET['accion'] === 'aprobar') {
        $resultado = $pagoController->cambiarEstadoPago($idPago, 'completado');
        $_SESSION['mensaje'] = $resultado ? "Pago aprobado correctamente" : "Error al aprobar pago";
    } 
    elseif ($_GET['accion'] === 'rechazar') {
        $resultado = $pagoController->cambiarEstadoPago($idPago, 'cancelado');
        $_SESSION['mensaje'] = $resultado ? "Pago rechazado correctamente" : "Error al rechazar pago";
    }
    
    header("Location: admin_pagos.php");
    exit();
}


// Obtener pagos con filtro
$estadoFiltro = $_GET['estado'] ?? null;
$pagos = $pagoController->obtenerPagosAdmin($estadoFiltro);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style_panel.css">
     <style>
   .icons .fas,
.icons a.fas {
   font-size: 2rem;
   color: #333;
   cursor: pointer;
   margin-left: 1rem;
   transition: color 0.3s;
}

.icons .fas:hover,
.icons a.fas:hover {
   color: #9b9b9b;
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
                <a href="../../documentos/Manual de administrador.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
            </div>
            <div class="profile">
                <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil"><h3 class="name">
                    <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
                </h3>
                <p class="role">Administrador</p>
                <a href="../perfil.php" class="btn">ver perfil</a>
                <div class="flex-btn">
                    <a href="../../logout.php" class="option-btn">Cerrar Sesión</a>
                </div>
            </div>
        </section>
    </header>

    <div class="side-bar">
        <div id="close-btn"><i class="fas fa-times"></i></div>
        <div class="profile">
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?? 'Administrador') ?>
            </h3>
            <p class="role">Administrador</p>
                  <a href="../perfil.php" class="btn">Ver perfil</a>

        </div>
        <nav class="navbar">
            <a href="admin_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
            <a href="admin_pagos.php"><i class="fas fa-money-bill-wave"></i><span>Pagos</span></a>
            <a href="userlist.php"><i class="fas fa-users"></i><span>Gestión de Usuarios</span></a>
        </nav>
    </div>
    <section class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="heading">Gestión de Pagos</h2>
                    
                    <!-- Mostrar mensajes -->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-info"><?= $_SESSION['mensaje'] ?></div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <!-- Filtros -->
                    <div class="filtros">
                        <form method="get" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Filtrar por estado:</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente" <?= ($estadoFiltro === 'pendiente') ? 'selected' : '' ?>>Pendientes</option>
                                    <option value="completado" <?= ($estadoFiltro === 'completado') ? 'selected' : '' ?>>Completados</option>
                                    <option value="cancelado" <?= ($estadoFiltro === 'cancelado') ? 'selected' : '' ?>>Cancelados</option>
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="admin_pagos.php" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de pagos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Producto</th>
                                    <th>Comprobante</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos as $pago): 
                                    // Obtener detalles del pago (incluyendo comprobante)
                                    $detalles = json_decode($pago['detalles_pago'] ?? '{}', true);
                                    $comprobante = $detalles['comprobante'] ?? null;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($pago['id_pago']) ?></td>
                                        <td><?= htmlspecialchars($pago['nombre'] . ' ' . $pago['apellido']) ?></td>
                                        <td><?= htmlspecialchars($pago['nombre_producto']) ?></td>
                                        <td>
                                            <?php if ($comprobante): ?>
                                                <?php $extension = pathinfo($comprobante, PATHINFO_EXTENSION); ?>
                                                
                                                <?php if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                    <img src="../../documentos/<?= htmlspecialchars($comprobante) ?>"
                                                        class="img-thumbnail comprobante-preview"
                                                        alt="Comprobante"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalComprobante"
                                                        data-img-src="../../documentos/<?= htmlspecialchars($comprobante) ?>">
                                                <?php else: ?>
                                                    <a href="../../documentos/<?= htmlspecialchars($comprobante) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-file-alt"></i> Ver PDF
                                                    </a>
                                                <?php endif; ?>
                                                
                                            <?php else: ?>
                                                <span class="text-muted">Sin comprobante</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                        <td>
                                            <span class="estado-badge estado-<?= $pago['estado'] ?>">
                                                <?= ucfirst($pago['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="acciones">
                                                <?php if ($pago['estado'] === 'pendiente'): ?>
                                                    <a href="admin_pagos.php?accion=aprobar&id=<?= $pago['id_pago'] ?>" 
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('¿Confirmas que deseas aprobar este pago?')">
                                                    <i class="fas fa-check"></i> Aprobar
                                                    </a>
                                                    <a href="admin_pagos.php?accion=rechazar&id=<?= $pago['id_pago'] ?>" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('¿Confirmas que deseas rechazar este pago?')">
                                                    <i class="fas fa-times"></i> Rechazar
                                                    </a>
                                                <?php elseif ($pago['estado'] === 'completado' && empty($pago['id_inscripcion'])): ?>
                                                    <a href="admin_pagos.php?accion=inscribir&id=<?= $pago['id_pago'] ?>" 
                                                    class="btn btn-sm btn-primary"
                                                    onclick="return confirm('¿Confirmas que deseas inscribir a este usuario?')">
                                                    <i class="fas fa-user-graduate"></i> Inscribir
                                                    </a>
                                                <?php elseif ($pago['estado'] === 'completado'): ?>
                                                    <span class="badge bg-success">Inscrito</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal para visualización de comprobantes -->
        <div class="modal fade" id="modalComprobante" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprobante de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="imgComprobante" src="" class="img-fluid" style="max-height: 70vh;">
                    </div>
                    <div class="modal-footer">
                        <a id="downloadComprobante" href="#" class="btn btn-primary" download>
                            <i class="fas fa-download"></i> Descargar
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configurar modal para mostrar comprobante
        const modalComprobante = document.getElementById('modalComprobante');
        modalComprobante.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imgSrc = button.getAttribute('data-img-src');
            const imgElement = document.getElementById('imgComprobante');
            const downloadLink = document.getElementById('downloadComprobante');
            
            imgElement.src = imgSrc;
            downloadLink.href = imgSrc;
            downloadLink.download = imgSrc.split('/').pop();
        });

        // Confirmación antes de cambiar estado
        document.querySelectorAll('.btn-success, .btn-danger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de realizar esta acción?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script src="../js/script.js"></script>
    <script src="../js/mensajes.js"></script>
</body>
</html>