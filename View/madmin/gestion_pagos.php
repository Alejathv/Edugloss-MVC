<?php include '../session_messages.php'; ?>

<div class="admin-container">
    <h2>Gestión de Pagos</h2>
    
    <table class="table-pagos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagos as $pago): ?>
                <tr>
                    <td><?= $pago['id_pago'] ?></td>
                    <td><?= $pago['nombre'] ?> <?= $pago['apellido'] ?></td>
                    <td><?= $pago['nombre_producto'] ?></td>
                    <td>$<?= number_format($pago['precio'], 2) ?></td>
                    <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                    <td>
                        <span class="estado-<?= $pago['estado'] ?>">
                            <?= ucfirst($pago['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($pago['estado'] === 'pendiente'): ?>
                            <a href="../Controller/PagoController.php?action=aprobar&id=<?= $pago['id_pago'] ?>" 
                               class="btn-aprobar">Aprobar</a>
                            <a href="#" class="btn-rechazar">Rechazar</a>
                        <?php endif; ?>
                        <a href="ver_pago.php?id=<?= $pago['id_pago'] ?>" class="btn-ver">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>