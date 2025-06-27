<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../Model/database.php';
require_once __DIR__ . '/../../Controller/DocenteController.php';

$db = new Database();
$conn = $db->getConnection();
$controller = new DocenteController($conn);

// Obtener el material a editar
$id_material = $_GET['id'] ?? 0;
$material = $controller->obtenerMaterialPorId($id_material);

if (!$material) {
    $_SESSION['error'] = "Material no encontrado";
    header("Location: Contenido.php");
    exit;
}

// Obtener módulos directamente desde la base de datos
$sql = "SELECT * FROM modulo";
$stmt = $conn->prepare($sql);
$stmt->execute();
$modulos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevosDatos = [
        'nombre' => $_POST['nombre'],
        'url_material' => $_POST['url'],
        'tipo' => $_POST['tipo'],
        'id_modulo' => $_POST['id_modulo']
    ];
    
    if ($controller->editarMaterial($id_material, $nuevosDatos)) {
        $_SESSION['success'] = "Material actualizado correctamente";
        header("Location: Contenido.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al actualizar el material";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Material</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style_panel.css">
    <style>
        /* Contenedor principal */
        .contenedor-principal {
            display: flex;
            justify-content: center;
            padding: 30px 20px;
            background-color: #f0f0f5;
            margin-left: 100px; /* Espacio para el menú lateral */
            min-height: calc(100vh - 60px);
        }
        
        /* Caja del formulario */
        .caja-formulario {
            background-color: #f9f7fd;
            border: 1px solid #c9bdf0;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 700px;
        }
        
        /* Título */
        .titulo-seccion {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .titulo-seccion h2 {
            font-size: 28px;
            color: #5c3fa3;
            margin: 0;
            font-weight: 600;
        }
        
        .titulo-seccion::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(to right, transparent, #8656e9, transparent);
            border-radius: 3px;
        }
        
        /* Grupos de campos */
        .grupo-campo {
            margin-bottom: 25px;
        }
        
        .grupo-campo label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #4b2e83;
            font-size: 16px;
        }
        
        .grupo-campo input, 
        .grupo-campo select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e3d7f7;
            border-radius: 8px;
            font-size: 16px;
            background-color: #fff;
            transition: all 0.3s ease;
        }
        
        .grupo-campo input:focus, 
        .grupo-campo select:focus {
            border-color: #8656e9;
            outline: none;
            box-shadow: 0 0 0 3px rgba(134, 86, 233, 0.15);
        }
        
        /* Botones */
        .contenedor-botones {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .boton-guardar {
            background-color: #8656e9;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .boton-guardar:hover {
            background-color: #734bd1;
            transform: translateY(-2px);
        }
        
        .boton-cancelar {
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .boton-cancelar:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        
        /* Mensajes */
        .mensaje {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }
        
        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
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
        <a href="docente_panel.php" class="logo">
            <img src="../img/LogoEGm.png" alt="EduGloss" style="height: 80px;">
        </a>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
            <a href="../../documentos/Manual de uso Docente.pdf" target="_blank" id="help-btn" class="fas fa-question"></a>
        </div>
        <div class="profile">
            <img src="../img/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'icon1.png') ?>" class="image" alt="Foto de perfil">
            <h3 class="name">
                <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
            </h3>
            <p class="role">Docente</p>
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
            <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?>
        </h3>
        <p class="role">Docente</p>
        <a href="../perfil.php" class="btn">Ver Perfil</a>
    </div>
    <nav class="navbar">
        <a href="./docente_panel.php"><i class="fas fa-home"></i><span>Inicio</span></a>
        <a href="../ForoGeneral.php"><i class="fas fa-comments"></i><span>Foro General</span></a>
        <a href="./TablasCM.php"><i class="fas fa-book"></i><span>Gestion de Aprendizaje</span></a>
        <a href="./Contenido.php"><i class="fas fa-upload"></i><span>Subir Material</span></a>
        <a href="./evidencias.php"><i class="fas fa-file-alt"></i><span>Evidencias</span></a>
    </nav>
</div>

<section class="contenedor-principal">
    <div class="caja-formulario">
        <!-- Título centrado arriba del formulario -->
        <div class="titulo-seccion">
            <h2><i class="fas fa-edit"></i> Editar Material Educativo</h2>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mensaje mensaje-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="grupo-campo">
                <label for="nombre"></i> Nombre del material:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($material['nombre']) ?>" required>
            </div>
            
            <div class="grupo-campo">
                <label for="url"><i class="fas fa-link"></i> URL del material:</label>
                <input type="url" id="url" name="url" value="<?= htmlspecialchars($material['url_material']) ?>" required>
            </div>
            
            <div class="grupo-campo">
                <label for="tipo"><i class="fas fa-file-alt"></i> Tipo de material:</label>
                <select id="tipo" name="tipo" required>
                    <option value="video" <?= $material['tipo'] === 'video' ? 'selected' : '' ?>>Video</option>
                    <option value="pdf" <?= $material['tipo'] === 'pdf' ? 'selected' : '' ?>>Documento PDF</option>
                </select>
            </div>
            
            <div class="grupo-campo">
                <label for="id_modulo"><i class="fas fa-book-open"></i> Módulo asociado:</label>
                <select id="id_modulo" name="id_modulo" required>
                    <?php foreach ($modulos as $modulo): ?>
                        <option value="<?= $modulo['id_modulo'] ?>" <?= $modulo['id_modulo'] == $material['id_modulo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($modulo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="contenedor-botones">
                <button type="submit" class="boton-guardar"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="Contenido.php" class="boton-cancelar"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</section>


<script src="../js/script.js"></script>
</body>
</html>