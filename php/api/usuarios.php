<?php
// ============================================
// API - GESTIÓN DE USUARIOS
// ============================================

require_once '../config/db.php';
require_once '../auth/session.php';

// Solo admin puede acceder
requiereAdmin();

header('Content-Type: application/json');

$conexion = require '../config/db.php';
$accion = $_GET['accion'] ?? '';

// OBTENER TODOS LOS USUARIOS
if ($accion === 'listar') {
    $stmt = $conexion->prepare('SELECT id, nombre, email, tipo_usuario, estado, fecha_registro FROM usuarios ORDER BY fecha_registro DESC');
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $usuarios = [];
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }
    
    echo json_encode(['suceso' => true, 'datos' => $usuarios]);
}

// OBTENER UN USUARIO
elseif ($accion === 'obtener') {
    $id = intval($_GET['id'] ?? 0);
    
    $stmt = $conexion->prepare('SELECT id, nombre, email, tipo_usuario, estado, fecha_registro FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        echo json_encode(['suceso' => true, 'datos' => $resultado->fetch_assoc()]);
    } else {
        echo json_encode(['suceso' => false, 'error' => 'Usuario no encontrado']);
    }
}

// CREAR NUEVO USUARIO
elseif ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'user';
    
    // Validar
    if (empty($nombre) || empty($email) || empty($contraseña) || strlen($contraseña) < 6) {
        echo json_encode(['suceso' => false, 'error' => 'Datos inválidos']);
        exit;
    }
    
    // Verificar que no exista
    $check = $conexion->prepare('SELECT id FROM usuarios WHERE email = ?');
    $check->bind_param('s', $email);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['suceso' => false, 'error' => 'El email ya está registrado']);
        exit;
    }
    
    // Encriptar contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
    
    // Insertar
    $stmt = $conexion->prepare('INSERT INTO usuarios (nombre, email, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $nombre, $email, $contraseña_hash, $tipo_usuario);
    
    if ($stmt->execute()) {
        echo json_encode(['suceso' => true, 'id' => $conexion->insert_id, 'mensaje' => 'Usuario creado correctamente']);
    } else {
        echo json_encode(['suceso' => false, 'error' => 'Error al crear usuario']);
    }
}

// ACTUALIZAR USUARIO
elseif ($accion === 'actualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'user';
    $estado = $_POST['estado'] ?? 'activo';
    $contraseña = $_POST['contraseña'] ?? '';
    
    // Validar
    if ($id === 0 || empty($nombre) || empty($email)) {
        echo json_encode(['suceso' => false, 'error' => 'Datos inválidos']);
        exit;
    }
    
    // Verificar que el email no esté en uso (para otro usuario)
    $check = $conexion->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
    $check->bind_param('si', $email, $id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['suceso' => false, 'error' => 'El email ya está en uso']);
        exit;
    }
    
    // Actualizar
    if (!empty($contraseña) && strlen($contraseña) >= 6) {
        $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
        $stmt = $conexion->prepare('UPDATE usuarios SET nombre = ?, email = ?, tipo_usuario = ?, estado = ?, contraseña = ? WHERE id = ?');
        $stmt->bind_param('sssssi', $nombre, $email, $tipo_usuario, $estado, $contraseña_hash, $id);
    } else {
        $stmt = $conexion->prepare('UPDATE usuarios SET nombre = ?, email = ?, tipo_usuario = ?, estado = ? WHERE id = ?');
        $stmt->bind_param('ssssi', $nombre, $email, $tipo_usuario, $estado, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['suceso' => true, 'mensaje' => 'Usuario actualizado correctamente']);
    } else {
        echo json_encode(['suceso' => false, 'error' => 'Error al actualizar usuario']);
    }
}

// ELIMINAR USUARIO
elseif ($accion === 'eliminar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    
    // No permitir eliminar a uno mismo
    if ($id === $_SESSION['usuario_id']) {
        echo json_encode(['suceso' => false, 'error' => 'No puedes eliminar tu propia cuenta']);
        exit;
    }
    
    $stmt = $conexion->prepare('DELETE FROM usuarios WHERE id = ? AND tipo_usuario != "admin"');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['suceso' => true, 'mensaje' => 'Usuario eliminado correctamente']);
    } else {
        echo json_encode(['suceso' => false, 'error' => 'No se pudo eliminar el usuario']);
    }
}

else {
    echo json_encode(['suceso' => false, 'error' => 'Acción no válida']);
}

$conexion->close();
