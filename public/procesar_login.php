<?php
/**
 * Endpoint público seguro para procesar login
 * Carga el archivo de autenticación desde la carpeta privada (php/auth/)
 */

// Usar __DIR__ para obtener la ruta segura del archivo actual
$authFile = __DIR__ . '/../php/auth/procesar_login.php';

// Validar que el archivo existe
if (!file_exists($authFile)) {
    http_response_code(500);
    echo 'Error: No se pudo encontrar el archivo de autenticación.';
    exit;
}

// Cargar el archivo de autenticación
require_once $authFile;
?>
