(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);
    
    // WOW.js
    new WOW().init();
    
    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.nav-bar').addClass('sticky-top shadow-sm').css('top', '0px');
        } else {
            $('.nav-bar').removeClass('sticky-top shadow-sm').css('top', '-100px');
        }
    });

    // Testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: false,
        dots: false,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="fa fa-arrow-right"></i>',
            '<i class="fa fa-arrow-left"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{ items:1 },
            576:{ items:1 },
            768:{ items:2 },
            992:{ items:2 },
            1200:{ items:2 }
        }
    });

    // Counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 5,
        time: 2000
    });

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });

    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });

    // Modal functions
    window.mostrarInfo = function (btn) {
        const producto = btn.closest('.producto');
        document.getElementById("info-nombre").innerText = producto.dataset.nombre;
        document.getElementById("info-descripcion").innerText = producto.dataset.descripcion;
        document.getElementById("modal-info").style.display = "block";
    }

    window.cerrarModal = function (id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function (event) {
        const modal = document.getElementById("modal-info");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

})(jQuery);
