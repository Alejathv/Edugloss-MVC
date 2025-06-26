<?php
require_once '../Model/database.php';
require_once '../Model/CursoModel.php';
require_once '../Model/ModuloModel.php';

// Crear conexión
$db = new Database();
$conn = $db->getConnection();

// Obtener cursos y módulos disponibles
$cursoModel = new CursoModel($conn);
$moduloModel = new ModuloModel($conn);

$cursos = $cursoModel->obtenerCursosDisponibles();
$modulos = $moduloModel->obtenerModulosDisponibles();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Edugloss - Planes</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Libraries Stylesheet -->
    <link rel="stylesheet" href="lib/animate/animate.min.css"/>
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<div id="modal-info" class="modal-curso">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal('modal-info')">&times;</span>
            <h3 class="titulo-modal" id="info-nombre"></h3>
            <p class="info-modal" id="info-descripcion"></p>
        </div>
    </div>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light"> 
                <a href="../index.php" class="navbar-brand p-0">
                    <h1 class="text-primary mb-0"><img src="img/logo.png" alt="Logo"></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-0 mx-lg-auto">
                        <a href="../index.php" class="nav-item nav-link active">Inicio</a>
                        <a href="planes.php" class="nav-item nav-link active">Planes</a>
                        <a href="team.html" class="nav-item nav-link active">Equipo</a>
                        <a href="#contacto" class="nav-item nav-link active">Contacto</a>
                    </div>

                    <div class="d-flex">
                        <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 flex-shrink-0 ms-2">
                            
                            <i class="fa fa-graduation-cap"></i> Campus Virtual
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Planes de Estudio</h4>
        </div>
    </div>
    <!-- Header End -->
  
     <!-- SECCIÓN PLANES  -->
        <div class="container-fluid service pb-5">
            <div class="container pb-5">
                <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;"> <br> <br>
                    <h1 class="display-4 mb-4">Descubre el plan ideal para ti</h1>
                    <p class="mb-0">Brindamos los mejores cursos de manicure, diseñados para todos los niveles. Aprende técnicas profesionales con material actualizado y formación práctica, adaptada a tus necesidades.
                    </p>
                </div>

                <!-- Sección de Cursos Disponibles -->
                <h2 class="text-center mb-4 wow fadeInUp" data-wow-delay="0.3s">Nuestros Cursos</h2>
                <div class="row g-4 justify-content-center">
                    <?php $i = 0; ?>
                    <?php foreach ($cursos as $curso): ?>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="<?= (($i % 4) + 2) * 0.1 ?>s">

                        <div class="service-item producto"
                            data-nombre="<?= htmlspecialchars($curso['nombre']) ?>"
                            data-descripcion="<?= htmlspecialchars($curso['descripcion']) ?>">

                            <div class="service-img">
                                <img src="img/servicio-<?= ($curso['id_curso'] % 4) + 1 ?>.jpg" class="img-fluid rounded-top w-100" alt="<?= htmlspecialchars($curso['nombre']) ?>">
                                <div class="service-icon p-3">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                            </div>

                            <div class="service-content p-4">
                                <div class="service-content-inner">
                                    <h4 class="mb-4">Curso: <?= htmlspecialchars($curso['nombre']) ?></h4>

                                    <!-- Fila con precio y lupa al lado -->
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <p class="text-primary fw-bold mb-0">$<?= number_format($curso['precio'], 2) ?></p>
                                        
                                        <!-- Botón lupa -->
                                        <button type="button" class="service-icon view-icon" onclick="mostrarInfo(this)" aria-label="Ver descripción del curso">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>


                                    <!-- Formulario de compra -->
                                    <form action="View/procesar_pago.php" method="POST">
                                        <input type="hidden" name="tipo_producto" value="curso">
                                        <input type="hidden" name="id_producto" value="<?= $curso['id_curso'] ?>">
                                        <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($curso['nombre']) ?>">
                                        <input type="hidden" name="precio_producto" value="<?= $curso['precio'] ?>">
                                        <button type="submit" class="btn btn-primary rounded-pill py-2 px-4 w-100">Comprar ahora</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Sección de Módulos Disponibles -->
                <?php if (!empty($modulos)): ?>
                <h2 class="text-center mt-5 mb-4 wow fadeInUp" data-wow-delay="0.3s">Módulos Individuales</h2>
                <div class="row g-4 justify-content-center">
                        <?php $j = 0; ?>
                        <?php foreach ($modulos as $modulo): ?>
                        <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="<?= (($j % 4) + 2) * 0.1 ?>s">
                            <div class="service-item producto"
                                data-nombre="<?= htmlspecialchars($modulo['nombre']) ?>"
                                data-descripcion="<?= htmlspecialchars($modulo['descripcion'] ?? 'Módulo especializado') ?>">

                                <div class="service-img">
                                    <img src="img/servicio-<?= ($modulo['id_modulo'] % 4) + 1 ?>.jpg"
                                        class="img-fluid rounded-top w-100"
                                        alt="<?= htmlspecialchars($modulo['nombre']) ?>">
                                    <div class="service-icon p-3">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>
                                </div>

                                <div class="service-content p-4">
                                    <div class="service-content-inner">
                                        <h4 class="mb-2">Módulo: <?= htmlspecialchars($modulo['nombre']) ?></h4>

                                        <!-- Fila con precio y lupa -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <p class="text-primary fw-bold mb-0">$<?= number_format($modulo['precio'], 2) ?></p>

                                            <!-- Botón lupa que abre el modal -->
                                            <button type="button"
                                                    class="service-icon view-icon"
                                                    onclick="mostrarInfo(this)"
                                                    aria-label="Ver descripción del módulo">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>

                                        <form action="procesar_pago.php" method="POST">
                                            <input type="hidden" name="tipo_producto" value="modulo">
                                            <input type="hidden" name="id_producto" value="<?= $modulo['id_modulo'] ?>">
                                            <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($modulo['nombre']) ?>">
                                            <input type="hidden" name="precio_producto" value="<?= $modulo['precio'] ?>">
                                            <button type="submit" class="btn btn-primary rounded-pill py-2 px-4 w-100">Comprar ahora</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $j++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php endif; ?>
                </div>
        </div>

    <!-- SECCIÓN PLANES  -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-12"> 
                    <div class="mb-5">
                        <div class="row g-4 justify-content-between">
                            <!--Edugloss -->
                            <div class="col-6 col-md-3">
                                <div class="footer-item text-start">
                                    <a href="index.html" class="p-0">
                                        <h3 class="text-white mb-2"></i>EduGloss</h3>
                                    </a>
                                    <p class="text-white mb-3">Encuentranos en nuestras difrerentes redes sociales</p>
                                    <div class="footer-btn d-flex">
                                        <a class="btn btn-md-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-2" href="#"><i class="fab fa-twitter"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-2" href="#"><i class="fab fa-instagram"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-0" href="#"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Contacto -->
                            <div id="contacto" class="col-6 col-md-3">
                                <div class="footer-item text-start">
                                    <h4 class="text-white mb-2">Contacto</h4>
                                    <p class="text-white">Si tienes alguna pregunta o necesitas ayuda, contáctanos directamente en WhatsApp.</p>
                                    <a href="https://wa.me/+5491134106985" class="btn btn-whatsapp d-flex align-items-center">
                                        <i class="fab fa-whatsapp me-2"></i> Chatea con nosotros
                                    </a>
                                </div>
                            </div>
                            <!-- Horarios de Atención -->
                            <div class="col-6 col-md-3">
                                <div class="footer-item text-start">
                                    <h4 class="text-white mb-2">Horarios de Atención</h4>
                                    <p class="text-white mb-1">🕘 L-V: 9:00 AM - 6:00 PM</p>
                                    <p class="text-white mb-1">🕙 S: 10:00 AM - 2:00 PM</p>
                                    <p class="text-white">❌ D y F: Cerrado</p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <div class="row g-0">
                            <div class="col-12">
                                <div class="row g-4">
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-xl-square bg-primary text-white rounded p-4 me-3">
                                                <i class="fas fa-map-marker-alt fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white mb-2">Dirección</h4>
                                                <p class="mb-0 text-white">Tte. Gral Domingo Perón 2005</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-xl-square bg-primary text-white rounded p-4 me-3">
                                                <i class="fas fa-envelope fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white mb-2">Correo</h4>
                                                <p class="mb-0 text-white">info@edugloss.com</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-xl-square bg-primary text-white rounded p-4 me-3">
                                                <i class="fa fa-phone-alt fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white mb-2">Teléfono</h4>
                                                <p class="mb-0 text-white">(+54) 9 11 3410-6985</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-end mb-md-0">
                    <span class="text-body"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>Edugloss</a>, Todos los derechos reservados.</span>
                </div>
                <div class="col-md-6 text-center text-md-start text-body">
                    Diseñado por <a class="border-bottom text-white" href="https://htmlcodex.com"> Gliz Craft's</a>  <a>2025</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>