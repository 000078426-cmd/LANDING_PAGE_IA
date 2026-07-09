<?php
require_once '../php/auth/session.php';

// Si ya está autenticado, redirigir
if (estaAutenticado()) {
    if (esAdmin()) {
        header('Location: dashboard.html');
    } else {
        header('Location: landing.php');
    }
    exit;
}

// Si no está autenticado, mostrar login
header('Location: login.html');
exit;
