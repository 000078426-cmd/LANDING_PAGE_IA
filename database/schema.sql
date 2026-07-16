
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contraseña VARCHAR(255) NOT NULL,
  tipo_usuario ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de ventas
CREATE TABLE ventas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  servicio VARCHAR(100) NOT NULL,
  descripcion TEXT,
  monto DECIMAL(10, 2) NOT NULL,
  fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_entrega TIMESTAMP,
  estado ENUM('completada', 'pendiente', 'cancelada') NOT NULL DEFAULT 'completada',
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de servicios
CREATE TABLE servicios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10, 2) NOT NULL,
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de citas
CREATE TABLE citas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT,
  nombre_cliente VARCHAR(100) NOT NULL,
  email_cliente VARCHAR(100) NOT NULL,
  telefono_cliente VARCHAR(20),
  servicio_id INT NOT NULL,
  fecha_cita DATETIME NOT NULL,
  estado ENUM('confirmada', 'pendiente', 'cancelada', 'completada') NOT NULL DEFAULT 'pendiente',
  notas TEXT,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de reportes diarios (para optimizar consultas de ventas)
CREATE TABLE reportes_diarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  fecha DATE NOT NULL UNIQUE,
  total_ventas DECIMAL(10, 2) DEFAULT 0,
  cantidad_transacciones INT DEFAULT 0,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE EJEMPLO
-- ============================================

-- Insertar usuario admin (contraseña: admin123)
INSERT INTO usuarios (nombre, email, contraseña, tipo_usuario) VALUES 
('Admin Victoria', 'admin@esteticavictoria.com', '$2y$10$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 'admin');

-- Insertar servicios
INSERT INTO servicios (nombre, descripcion, precio) VALUES 
('Corte de cabello', 'Corte profesional adaptado a tu estilo', 150.00),
('Manicure', 'Diseños y colores personalizados', 100.00),
('Pedicure', 'Cuidado completo de pies', 120.00),
('Maquillaje profesional', 'Look natural o de ocasión', 200.00),
('Tratamiento capilar', 'Hidratación y cuidado profundo', 180.00),
('Depilación', 'Depilación profesional en diferentes áreas', 80.00);

-- ============================================
-- ÍNDICES PARA OPTIMIZACIÓN
-- ============================================

CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_tipo ON usuarios(tipo_usuario);
CREATE INDEX idx_ventas_usuario ON ventas(usuario_id);
CREATE INDEX idx_ventas_fecha ON ventas(fecha_venta);
CREATE INDEX idx_citas_fecha ON citas(fecha_cita);
CREATE INDEX idx_citas_estado ON citas(estado);
