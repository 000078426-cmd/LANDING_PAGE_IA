<?php
// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

// Detectar entorno
$env = getenv('ENVIRONMENT') ?: 'local';

if ($env === 'production') {
    // Configuración para Railway (variables de entorno)
    $db_host = getenv('DB_HOST');
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    $db_name = getenv('DB_NAME');
} else {
    // Configuración local
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'estetica_victoria';
}

// Crear conexión
$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]));
}

// Establecer charset UTF-8
$conexion->set_charset('utf8mb4');

// Definir zona horaria
date_default_timezone_set('America/Mexico_City');

// Retornar conexión
return $conexion;
