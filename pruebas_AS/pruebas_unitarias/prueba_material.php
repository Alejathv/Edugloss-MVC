<?php
function agregarMaterial($id_modulo, $nombre, $url, $tipo) {
    if (empty($id_modulo) || empty($nombre) || empty($url) || empty($tipo)) {
        return false; // Fallará si hay algún dato vacío
    }

    return true; // Simula que se agrego de manera exitosa
}

// Se ejecutan pruebas manuales
echo agregarMaterial(1, "Introducción a la manicura", "https://edugloss.com/video1", "video") ? "Material agregado\n" : " Fallo en la inserción\n";
echo agregarMaterial(1, "", "https://edugloss.com/video1", "video") ? "Material agregado\n" : " Fallo en la inserción\n";

?>