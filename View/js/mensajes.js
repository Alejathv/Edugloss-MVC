// Script para mostrar mensajes de sesión
document.addEventListener('DOMContentLoaded', function() {
    // Función para obtener parámetros de la URL
    const getUrlParams = () => {
        const params = {};
        const queryString = window.location.search.substring(1);
        const pairs = queryString.split('&');
        
        for (let i = 0; i < pairs.length; i++) {
            const pair = pairs[i].split('=');
            params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
        }
        
        return params;
    };
    
    // Función para mostrar alertas
    const mostrarAlerta = (mensaje, tipo) => {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insertar alerta después del formulario
        const formulario = document.querySelector('.form-container');
        if (formulario) {
            formulario.parentNode.insertBefore(alertDiv, formulario.nextSibling);
            
            // Auto-cerrar después de 5 segundos
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 500);
            }, 5000);
        }
    };
    
    // Verificar si hay mensajes en la sesión (estos se pasan como parámetros en la URL)
    const params = getUrlParams();
    
    if (params.mensaje) {
        mostrarAlerta(decodeURIComponent(params.mensaje), 'success');
    }
    
    if (params.error) {
        mostrarAlerta(decodeURIComponent(params.error), 'danger');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const mensaje = document.getElementById('mensaje-bienvenida');
    if (mensaje) {
        // Esperar 5 segundos y luego eliminar el mensaje
        setTimeout(() => {
            // Opcional: con transición para que se desvanezca
            mensaje.style.transition = 'opacity 0.5s ease';
            mensaje.style.opacity = '0';
            setTimeout(() => {
                mensaje.remove();
            }, 500);
        }, 5000);
    }
});
function mostrarAlerta() {
    Swal.fire({
        title: "Éxito",
        text: "¡Tu comentario se ha publicado correctamente!",
        icon: "success",
        confirmButtonText: "OK"
    });
}
