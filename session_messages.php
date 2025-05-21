<?php
session_start();

// Variables para los mensajes
$mensaje = "";
$error = "";

// Comprobar si hay mensajes en la sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = urlencode($_SESSION['mensaje']);
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['error'])) {
    $error = urlencode($_SESSION['error']);
    unset($_SESSION['error']);
}

// Construir la URL de redirección con los parámetros
$redirect_url = "index.html";
$params = [];

if (!empty($mensaje)) {
    $params[] = "mensaje=" . $mensaje;
}

if (!empty($error)) {
    $params[] = "error=" . $error;
}

if (count($params) > 0) {
    $redirect_url .= "?" . implode("&", $params);
}

// Redirigir
header("Location: " . $redirect_url);
exit();
?>