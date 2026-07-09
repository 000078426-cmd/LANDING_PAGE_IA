<?php
// ============================================
// INICIALIZACIÓN DE SESIONES Y SEGURIDAD
// ============================================

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurar opciones de sesión seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// Regenerar ID de sesión para prevenir fixation
function regenerarSesion() {
    session_regenerate_id(true);
}

// Verificar si usuario está autenticado
function estaAutenticado() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['tipo_usuario']);
}

// Verificar si es admin
function esAdmin() {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
}

// Obtener datos del usuario autenticado
function obtenerUsuarioActual() {
    if (estaAutenticado()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nombre' => $_SESSION['nombre_usuario'],
            'email' => $_SESSION['email_usuario'],
            'tipo' => $_SESSION['tipo_usuario']
        ];
    }
    return null;
}

// Cerrar sesión
function cerrarSesion() {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Redirigir si no está autenticado
function requiereAutenticacion() {
    if (!estaAutenticado()) {
        header('Location: index.php');
        exit;
    }
}

// Redirigir si no es admin
function requiereAdmin() {
    if (!esAdmin()) {
        header('Location: landing.php');
        exit;
    }
}
