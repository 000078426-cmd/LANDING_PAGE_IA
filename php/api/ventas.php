<?php
// ============================================
// API - GESTIÓN DE VENTAS
// ============================================

require_once '../config/db.php';
require_once '../auth/session.php';

// Solo admin puede acceder
requiereAdmin();

header('Content-Type: application/json');

$conexion = require '../config/db.php';
$accion = $_GET['accion'] ?? '';

// VENTAS DIARIAS
if ($accion === 'diarias') {
    $fecha = $_GET['fecha'] ?? date('Y-m-d');
    
    $stmt = $conexion->prepare('
        SELECT 
            SUM(monto) as total_ventas,
            COUNT(*) as cantidad_ventas,
            DATE(fecha_venta) as fecha
        FROM ventas 
        WHERE DATE(fecha_venta) = ? AND estado = "completada"
        GROUP BY DATE(fecha_venta)
    ');
    $stmt->bind_param('s', $fecha);
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
    
    $stmt = $conexion->prepare('
        SELECT 
            DATE(fecha_venta) as fecha,
            SUM(monto) as total_ventas,
            COUNT(*) as cantidad_ventas
        FROM ventas 
        WHERE DATE(fecha_venta) BETWEEN ? AND ? AND estado = "completada"
        GROUP BY DATE(fecha_venta)
        ORDER BY fecha DESC
    ');
    $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
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
    $stmt = $conexion->prepare('
        SELECT 
            v.id,
            v.usuario_id,
            u.nombre as usuario_nombre,
            v.servicio,
            v.monto,
            v.fecha_venta,
            v.estado
        FROM ventas v
        LEFT JOIN usuarios u ON v.usuario_id = u.id
        ORDER BY v.fecha_venta DESC
        LIMIT 100
    ');
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
    $stmt = $conexion->prepare('
        SELECT 
            SUM(monto) as total_mes,
            COUNT(*) as cantidad_ventas,
            COUNT(DISTINCT usuario_id) as clientes_unicos
        FROM ventas 
        WHERE MONTH(fecha_venta) = MONTH(NOW()) 
        AND YEAR(fecha_venta) = YEAR(NOW())
        AND estado = "completada"
    ');
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $datos = $resultado->fetch_assoc();
    
    echo json_encode(['suceso' => true, 'datos' => $datos]);
}

else {
    echo json_encode(['suceso' => false, 'error' => 'Acción no válida']);
}

$conexion->close();
