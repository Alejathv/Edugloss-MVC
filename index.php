<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Llama a los modelos 
require_once __DIR__ . '/Model/database.php';
require_once __DIR__ . '/Model/CursoModel.php';
require_once __DIR__ . '/Model/ModuloModel.php';

//Espara la sesión de php 
session_start();
// Crear conexión
$db = new Database();
$conn = $db->getConnection();
// modelos 
$cursoModel = new CursoModel($conn);
$moduloModel = new ModuloModel($conn);
// Obtener cursos y módulos disponibles
$cursos = $cursoModel->obtenerCursosDisponibles();
$modulos = $moduloModel->obtenerModulosDisponibles();

//Manejo de mensajes (éxito o error)
$mensaje = '';
$tipo = ''; // "exito" o "error"

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo = 'success';
    unset($_SESSION['mensaje']);
} elseif (isset($_SESSION['error'])) {
    $mensaje = $_SESSION['error'];
    $tipo = 'danger';
    unset($_SESSION['error']);
}
//Mostrar el mensaje con JavaScript:
if (!empty($mensaje)) {
    echo "<script>
        window.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('mensaje-container');
            if (container) {
                container.innerHTML = `<div class='alert alert-{$tipo} alert-dismissible fade show'>
                    {$mensaje}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>`;
                setTimeout(() => {
                    container.innerHTML = '';
                }, 5000);
            }
        });
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Edugloss - Educational</title>
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

        <!-- Libraries Stylesheet -->
        <link rel="stylesheet" href="View/lib/animate/animate.min.css"/>
        <link href="View/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="View/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="View/css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="View/css/style.css" rel="stylesheet">
        <!-- font awesome  -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    </head>

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
                <a href="index.php" class="navbar-brand p-0">
                    <h1 class="text-primary mb-0"><img src="View/img/logo.png" alt="Logo"></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-0 mx-lg-auto">
                        <a href="index.php" class="nav-item nav-link active">Inicio</a>
                        <a href="View/planes.php" class="nav-item nav-link active">Planes</a>
                        <a href="View/team.html" class="nav-item nav-link active">Equipo</a>
                        <a href="#contacto" class="nav-item nav-link active">Contacto</a>
                    </div>

                    <!-- Botones con margen pequeño -->
                <div class="d-flex">
                <!--Boton para ir al CAMPUS VIRTUAL O LOGIN-->
                     <a href="View/login.php" class="btn btn-primary rounded-pill py-2 px-4 flex-shrink-0 ms-2">
                        <i class="fa fa-graduation-cap"></i> Campus Virtual
                    </a>
                    
                    </div>
                    
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->


        <!-- Carousel Start -->
         
        <div class="header-carousel owl-carousel" id="carousel">
            <div class="header-carousel-item bg-primary">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-7 animated fadeInLeft">
                                <div class="text-sm-center text-md-start">
                                    <h4 class="text-white text-uppercase fw-bold mb-4">Bienvenid@ a Edugloss</h4>
                                    <h1 class="display-1 text-white mb-4">¡Aprende el arte <br> del manicure profesional!</h1>
                                    <p class="mb-5 fs-5">Deja tus datos y asegura tu lugar en nuestro curso exclusivo.</p> 
                                    <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                                        <a class="btn btn-dark rounded-pill py-3 px-4 px-md-5 ms-2" href="#Nuestra Historia">¡Conócenos!</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 animated fadeInRight">
                                <div class="calrousel-img" style="object-fit: cover;">
                                    <form action="Controller/RegistroController.php" method="post" class="form-container">
                                        <h3>Formulario de Registro</h3>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="hidden" name="id_usuario" value="">
                                                    <div class="mb-3">
                                                        <label for="nombre_usuario" class="form-label">
                                                            <strong>Nombre</strong>
                                                        </label>
                                                        <input type="text" class="form-control" name="nombre_usuario" 
                                                               id="nombre_usuario" placeholder="" required
                                                               pattern="[A-Za-zÀ-ÿ\s]{2,50}"
                                                               title="Solo se permiten letras y espacios, entre 2 y 50 caracteres">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="apellido_usuario" class="form-label">
                                                            <strong>Apellido</strong>
                                                        </label>
                                                        <input type="text" class="form-control" name="apellido_usuario" 
                                                               id="apellido_usuario" placeholder="" required
                                                               pattern="[A-Za-zÀ-ÿ\s]{2,50}"
                                                               title="Solo se permiten letras y espacios, entre 2 y 50 caracteres">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email_usuario" class="form-label">
                                                            <strong>Correo Electrónico</strong>
                                                        </label>
                                                        <input type="email" class="form-control" name="email_usuario" 
                                                               id="email_usuario" placeholder="" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="telefono_usuario" class="form-label">
                                                            <strong>Teléfono</strong>
                                                        </label>
                                                        <input type="text" class="form-control" name="telefono_usuario" 
                                                               id="telefono_usuario" placeholder="" 
                                                               pattern="[0-9]{7,12}" 
                                                               title="Solo se permiten números, entre 7 y 12 dígitos"
                                                               maxlength="12" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn custom-button btn-lg w-100">Registrarme</button>
                                        </div>
                                    </form>  
                                    <!-- Espacio para los mensajes dinámicos -->
                                    <div id="mensaje-container"></div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->

         <!-- About Start -->
        <div class="container-fluid bg-light about pb-5" id="Nuestra Historia">
            <div class="container pb-5">
                <div class="row g-5">
                    <div class="col-xl-12 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="about-item-content bg-white rounded p-5 h-100">
                            <h4 class="text-primary">Nuestra Historia</h4>
                            <h1 class="display-4 mb-4">EduGloss</h1>
                            <p>En Edugloss, nos dedicamos a transformar la pasión por la manicura en una carrera profesional. Nuestra plataforma ofrece cursos especializados diseñados para que aprendas técnicas innovadoras, perfecciones tu estilo y destaques en la industria de la belleza.
                            </p>
                            <p>Con nuestros cursos flexibles, podrás desarrollarte como profesional y ofrecer servicios de calidad a tus clientes. ¡Empieza hoy!
                            </p>
                            <p class="text-dark"><i class="fa fa-check text-primary me-3"></i>Afianza tus conocimientos.</p>
                            <p class="text-dark"><i class="fa fa-check text-primary me-3"></i>Precios asequibles.</p>
                            <p class="text-dark mb-4"><i class="fa fa-check text-primary me-3"></i>Flexibilidad de horarios.</p>
                            <a class="btn btn-primary rounded-pill py-3 px-5" href="planes.html">Más información</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        
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

                        <div class="service-item">
                            <div class="service-img">
                                <img src="View/img/servicio-<?= ($curso['id_curso'] % 4) + 1 ?>.jpg" class="img-fluid rounded-top w-100" alt="<?= htmlspecialchars($curso['nombre']) ?>">
                                <div class="service-icon p-3">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="service-content p-4">
                                <div class="service-content-inner">
                                    <h4 class="mb-4">Curso: <?= htmlspecialchars($curso['nombre']) ?></h4>
                                    <p class="mb-4"><?= htmlspecialchars($curso['descripcion']) ?></p>
                                    <p class="text-primary fw-bold mb-3">$<?= number_format($curso['precio'], 2) ?></p>
                                    <form action="procesar_pago.php" method="POST">
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
                        <div class="service-item">
                            <div class="service-img">
                                <img src="View/img/servicio-<?= ($modulo['id_modulo'] % 4) + 1 ?>.jpg" class="img-fluid rounded-top w-100" alt="<?= htmlspecialchars($modulo['nombre']) ?>">
                                <div class="service-icon p-3">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                            </div>
                            <div class="service-content p-4">
                                <div class="service-content-inner">
                                    <h4 class="mb-4">Módulo: <?= htmlspecialchars($modulo['nombre']) ?></h4>
                                    <p class="mb-4"><?= htmlspecialchars($modulo['descripcion'] ?? 'Módulo especializado') ?></p>
                                    <p class="text-primary fw-bold mb-3">$<?= number_format($modulo['precio'], 2) ?></p>
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
     <!-- SECCIÓN PLANES  -->
    <section id="equipo">
         <div class="container-fluid team pb-5">
            <div class="container pb-5">
                <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                   
                    <h1 class="display-4 mb-4">Conoce nuestro Team </h1> 
                    <p class="mb-0"> Somos un equipo de instructoras apasionadas por el arte de la manicura, dedicadas a compartir nuestros conocimientos y técnicas a través de cursos especializados.
                        Nuestro enfoque combina creatividad, experiencia y enseñanza de calidad para que cada estudiante desarrolle sus habilidades y logre destacarse en el mundo de la belleza.
                        Creemos en el aprendizaje continuo, la innovación en tendencias y el poder de la educación para transformar carreras.
                        
                        
                    </p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="View/img/team-1.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="team-icon">
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-twitter"></i></a> -->
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-linkedin-in"></i></a>
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-0" href=""><i class="fab fa-instagram"></i></a> -->
                                </div>
                            </div>
                            <div class="team-title p-4">
                                <h4 class="mb-0">LIDIA GUTIERREZ</h4>
                                <p class="mb-0">Especialista en Manicure y Pedicure Profesional</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="View/img/team-2.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="team-icon">
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-twitter"></i></a> -->
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href="https://www.linkedin.com/in/paola-gutierrez-438482339?trk=contact-info" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-0" href=""><i class="fab fa-instagram"></i></a> -->
                                </div>
                            </div>
                            <div class="team-title p-4">
                                <h4 class="mb-0">VALERIA DUARTE</h4>
                                <p class="mb-0">Especialista en Cuidado y </br> Salud de Uñas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="View/img/team-3.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="team-icon">
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-facebook-f"></i></a> -->
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-twitter"></i></a> -->
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href="https://www.linkedin.com/in/sara-elizabeth-pulido-beltran-549b2333a/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-0" href=""><i class="fab fa-instagram"></i></a> -->
                                </div>
                            </div>
                            <div class="team-title p-4">
                                <h4 class="mb-0">ANDREA LOPEZ</h4>
                                <p class="mb-0">Especialista en Herramientas y Equipos Profesionales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.8s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="View/img/team-4.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="team-icon">
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href=""><i class="fab fa-twitter"></i></a> -->
                                    <a class="btn btn-primary btn-sm-square rounded-pill mb-2" href="https://www.linkedin.com/in/alejandra-saenz-florez-ab087333a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    <!-- <a class="btn btn-primary btn-sm-square rounded-pill mb-0" href=""><i class="fab fa-instagram"></i></a> -->
                                </div>
                            </div>
                            <div class="team-title p-4">
                                <h4 class="mb-0">CAMILA RODRIGUEZ</h4>
                                <p class="mb-0">Especialista en Estética y Tendencias en Uñas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Footer Start -->
<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-xl-12"> 
                <div class="mb-5">
                    <div class="row g-4 justify-content-between"> <!-- Asegura distribución uniforme -->
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
                        <div id ="contacto"class="col-6 col-md-3">
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
                        <!-- Métodos de Pago -->
                        <div class="col-6 col-md-3">
                            <div class="footer-item text-start">
                                <h4 class="text-white mb-2">Métodos de Pago</h4>
                                <p class="text-white mb-1">💳 Tarjetas: Visa, Mastercard</p>
                                <p class="text-white mb-1">📲 Transferencias Bancarias</p>
                                <p class="text-white">🪙 Efectivo</p>
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
                <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
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
        <script src="View/lib/wow/wow.min.js"></script>
        <script src="View/lib/easing/easing.min.js"></script>
        <script src="View/lib/waypoints/waypoints.min.js"></script>
        <script src="View/lib/counterup/counterup.min.js"></script>
        <script src="View/lib/lightbox/js/lightbox.min.js"></script>
        <script src="View/lib/owlcarousel/owl.carousel.min.js"></script>
        

        <!-- Template Javascript -->
        <script src="View/js/main.js"></script>
        <!-- Mensajes Javascript -->
        <script src="View/js/mensajes.js"></script>
    </body>

</html>