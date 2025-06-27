// Modal para CREAR CURSO
function abrirModalCurso() {
   const modal = document.getElementById("modal-curso");
   const iframe = document.getElementById("iframe-curso");
   modal.style.display = "block";
   iframe.src = "crearcurso.php";
}

function cerrarModalCurso() {
   const modal = document.getElementById("modal-curso");
   const iframe = document.getElementById("iframe-curso");
   modal.style.display = "none";
   iframe.src = "";
}

// Modal para CREAR MÓDULO
function abrirModalModulo() {
   const modal = document.getElementById("modal-modulo");
   const iframe = document.getElementById("iframe-modulo");
   modal.style.display = "block";
   iframe.src = "crearmodulo.php";
}

function cerrarModalModulo() {
   const modal = document.getElementById("modal-modulo");
   const iframe = document.getElementById("iframe-modulo");
   modal.style.display = "none";
   iframe.src = "";
}

// Modal para EDITAR CURSO
function abrirModalEditarCurso(idCurso) {
   const modal = document.getElementById("modal-editar-curso");
   const iframe = document.getElementById("iframe-editar-curso");
   modal.style.display = "block";
   iframe.src = `editar_curso.php?id=${idCurso}`;
}

function cerrarModalEditarCurso() {
   const modal = document.getElementById("modal-editar-curso");
   const iframe = document.getElementById("iframe-editar-curso");
   modal.style.display = "none";
   iframe.src = "";
}

// Modal para EDITAR MÓDULO
function abrirModalEditarModulo(idModulo) {
   const modal = document.getElementById("modal-editar-modulo");
   const iframe = document.getElementById("iframe-editar-modulo");
   modal.style.display = "block";
   iframe.src = `editar_modulo.php?id=${idModulo}`;
}

function cerrarModalEditarModulo() {
   const modal = document.getElementById("modal-editar-modulo");
   const iframe = document.getElementById("iframe-editar-modulo");
   modal.style.display = "none";
   iframe.src = "";
}

// Cierre de cualquier modal al hacer clic fuera
window.onclick = function(event) {
   const modales = [
      "modal-curso",
      "modal-modulo",
      "modal-editar-curso",
      "modal-editar-modulo"
   ];
   modales.forEach(id => {
      const modal = document.getElementById(id);
      if (event.target === modal) {
         modal.style.display = "none";
         modal.querySelector("iframe").src = "";
      }
   });
};
