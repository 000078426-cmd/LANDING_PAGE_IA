<?php
// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

// Detectar entorno (producción por defecto)
$env = getenv('ENVIRONMENT') ?: 'production';

if ($env === 'production') {
    // Configuración para Railway (variables de entorno)
    // Usa las variables estándar de Railway, con compatibilidad hacia atrás
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'railway';
    $db_port = (int)getenv('DB_PORT') ?: 3306;
} else {
    // Configuración local
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'estetica_victoria';
    $db_port = 3306;
}

// Crear conexión con puerto
$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

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
