<?php
// ============================================
// API - GESTIÓN DE VENTAS
// ============================================

require_once '../config/db.php';
require_once '../auth/session.php';

if (!estaAutenticado()) {
    http_response_code(403);
    echo json_encode(['suceso' => false, 'error' => 'No autenticado']);
    exit;
}

$esAdmin = esAdmin();
$usuarioActualId = intval($_SESSION['usuario_id'] ?? 0);

header('Content-Type: application/json');

$conexion = require '../config/db.php';
$accion = $_GET['accion'] ?? '';

// VENTAS DIARIAS
if ($accion === 'diarias') {
    $fecha = $_GET['fecha'] ?? date('Y-m-d');
    $query = '
        SELECT 
            SUM(monto) as total_ventas,
            COUNT(*) as cantidad_ventas,
            DATE(fecha_venta) as fecha
        FROM ventas 
        WHERE DATE(fecha_venta) = ? AND estado = "completada"';
    $params = [$fecha];
    $tipos = 's';

    if (!$esAdmin) {
        $query .= ' AND usuario_id = ?';
        $params[] = $usuarioActualId;
        $tipos .= 'i';
    }

    $query .= ' GROUP BY DATE(fecha_venta)';

    $stmt = $conexion->prepare($query);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        echo json_encode(['suceso' => true, 'datos' => $datos]);
    } else {
        echo json_encode(['suceso' => true, 'datos' => [
            'total_ventas' => 0,
            'cantidad_ventas' => 0,
            'fecha' => $fecha
        ]]);
    }
}

// VENTAS POR RANGO DE FECHAS
elseif ($accion === 'rango') {
    $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
    $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
    $query = '
        SELECT 
            DATE(fecha_venta) as fecha,
            SUM(monto) as total_ventas,
            COUNT(*) as cantidad_ventas
        FROM ventas 
        WHERE DATE(fecha_venta) BETWEEN ? AND ? AND estado = "completada"';
    $params = [$fecha_inicio, $fecha_fin];
    $tipos = 'ss';

    if (!$esAdmin) {
        $query .= ' AND usuario_id = ?';
        $params[] = $usuarioActualId;
        $tipos .= 'i';
    }

    $query .= ' GROUP BY DATE(fecha_venta) ORDER BY fecha DESC';

    $stmt = $conexion->prepare($query);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $ventas = [];
    while ($fila = $resultado->fetch_assoc()) {
        $ventas[] = $fila;
    }

    echo json_encode(['suceso' => true, 'datos' => $ventas]);
}

// TODAS LAS VENTAS
elseif ($accion === 'listar') {
    $query = '
        SELECT 
            v.id,
            v.usuario_id,
            u.nombre as usuario_nombre,
            v.servicio,
            v.monto,
            v.fecha_venta,
            v.estado
        FROM ventas v
        LEFT JOIN usuarios u ON v.usuario_id = u.id';
    $params = [];
    $tipos = '';

    if (!$esAdmin) {
        $query .= ' WHERE v.usuario_id = ?';
        $params[] = $usuarioActualId;
        $tipos = 'i';
    }

    $query .= ' ORDER BY v.fecha_venta DESC LIMIT 100';

    $stmt = $conexion->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($tipos, ...$params);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();

    $ventas = [];
    while ($fila = $resultado->fetch_assoc()) {
        $ventas[] = $fila;
    }

    echo json_encode(['suceso' => true, 'datos' => $ventas]);
}

// TOTAL DE VENTAS DEL MES
elseif ($accion === 'mes') {
    $query = '
        SELECT 
            SUM(monto) as total_mes,
            COUNT(*) as cantidad_ventas,
            COUNT(DISTINCT usuario_id) as clientes_unicos
        FROM ventas 
        WHERE MONTH(fecha_venta) = MONTH(NOW()) 
        AND YEAR(fecha_venta) = YEAR(NOW())
        AND estado = "completada"';
    $params = [];
    $tipos = '';

    if (!$esAdmin) {
        $query .= ' AND usuario_id = ?';
        $params[] = $usuarioActualId;
        $tipos = 'i';
    }

    $stmt = $conexion->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($tipos, ...$params);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();

    $datos = $resultado->fetch_assoc();

    echo json_encode(['suceso' => true, 'datos' => $datos]);
}

elseif ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicio = trim($_POST['servicio'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $monto = floatval($_POST['monto'] ?? 0);
    $estado = $_POST['estado'] ?? 'completada';

    if (empty($servicio) || $monto <= 0) {
        echo json_encode(['suceso' => false, 'error' => 'Datos inválidos']);
        exit;
    }

    $stmt = $conexion->prepare('INSERT INTO ventas (usuario_id, servicio, descripcion, monto, estado) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issds', $usuarioActualId, $servicio, $descripcion, $monto, $estado);

    if ($stmt->execute()) {
        echo json_encode(['suceso' => true, 'mensaje' => 'Venta registrada correctamente']);
    } else {
        echo json_encode(['suceso' => false, 'error' => 'Error al registrar venta']);
    }
}

elseif (in_array($accion, ['actualizar', 'eliminar'], true)) {
    if (!$esAdmin) {
        http_response_code(403);
        echo json_encode(['suceso' => false, 'error' => 'No autorizado']);
        exit;
    }

    echo json_encode(['suceso' => false, 'error' => 'Acción no válida']);
}

else {
    echo json_encode(['suceso' => false, 'error' => 'Acción no válida']);
}

$conexion->close();
