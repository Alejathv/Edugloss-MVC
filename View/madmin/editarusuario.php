<?php
require_once __DIR__ . '/../../Controller/AdminController.php';

$adminController = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminController->actualizarUsuario($_POST);
    header("Location: userlist.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$usuario = $adminController->obtenerUsuario($_GET['id']);
if (!$usuario) {
    die("Usuario no encontrado.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="POST" action="editar_usuario.php">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $usuario['apellido'] ?>" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $usuario['telefono'] ?>"><br>

        <label>Correo:</label>
        <input type="email" name="correo" value="<?= $usuario['correo'] ?>" required><br>

        <label>Rol:</label>
        <select name="rol" required>
            <?php foreach (['estudiante', 'docente', 'administrador', 'cliente'] as $rol): ?>
                <option value="<?= $rol ?>" <?= $usuario['rol'] === $rol ? 'selected' : '' ?>>
                    <?= ucfirst($rol) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select><br>

        <label>Especialidad:</label>
        <input type="text" name="especialidad" value="<?= $usuario['especialidad'] ?>"><br><br>

        <button type="submit">Actualizar Usuario</button>
    </form>
</body>
</html>
