<?php
require_once '../config/db.php';
require_once '../auth/session.php';

header('Content-Type: application/json');

if (!estaAutenticado()) {
    http_response_code(401);
    echo json_encode(['suceso' => false, 'error' => 'No autenticado']);
    exit;
}

$usuario = obtenerUsuarioActual();

echo json_encode([
    'suceso' => true,
    'datos' => [
        'id' => $usuario['id'],
        'nombre' => $usuario['nombre'],
        'email' => $usuario['email'],
        'rol' => $usuario['tipo']
    ]
]);
