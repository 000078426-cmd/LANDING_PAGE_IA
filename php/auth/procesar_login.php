<?php
// ============================================
// PROCESAMIENTO DE LOGIN
// ============================================

require_once '../config/db.php';
require_once '../auth/session.php';

// Si ya está autenticado, redirigir
if (estaAutenticado()) {
    if (esAdmin()) {
        header('Location: /dashboard.html');
    } else {
        header('Location: /landing.php');
    }
    exit;
}

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'] ?? '';
    
    if (empty($email) || empty($contraseña)) {
        $error = 'Por favor complete todos los campos';
    } else {
        // Obtener conexión
        $conexion = require '../config/db.php';
        
        // Buscar usuario
        $stmt = $conexion->prepare('SELECT id, nombre, email, contraseña, tipo_usuario FROM usuarios WHERE email = ? AND estado = "activo"');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar contraseña
            if (password_verify($contraseña, $usuario['contraseña'])) {
                // Login exitoso
                regenerarSesion();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre_usuario'] = $usuario['nombre'];
                $_SESSION['email_usuario'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                
                // Registrar último login
                $updateStmt = $conexion->prepare('UPDATE usuarios SET fecha_actualizacion = NOW() WHERE id = ?');
                $updateStmt->bind_param('i', $usuario['id']);
                $updateStmt->execute();
                
                // Redirigir según tipo de usuario
                if ($usuario['tipo_usuario'] === 'admin') {
                    header('Location: /dashboard.html');
                } else {
                    header('Location: /landing.php');
                }
                exit;
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Email no registrado';
        }
        
        $stmt->close();
        $conexion->close();
    }
}
?>
