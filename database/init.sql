CREATE DATABASE tienda_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tienda_app;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    correo VARCHAR(100),
    rol VARCHAR(100) not null,
    estado VARCHAR(1) DEFAULT'A',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio_compra DECIMAL(10,2) NOT NULL, -- 8 CIFRAS ENTERAS Y 2 DECIMALES
    precio_venta DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    estado TINYINT(1) DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inventarios (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    tipo ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    descripcion VARCHAR(255),
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,
    cliente VARCHAR(150),
    total DECIMAL(10,2),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
CREATE TABLE detalle_ventas (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);


CREATE VIEW vista_ventas AS
SELECT v.id_venta, v.fecha, v.cliente, u.nombre AS vendedor, 
       v.total
FROM ventas v
JOIN usuarios u
 ON v.id_usuario = u.id_usuario;

CREATE VIEW vista_stock AS
SELECT p.id_producto, p.nombre,
       SUM(CASE WHEN i.tipo='entrada' THEN i.cantidad ELSE -i.cantidad END) AS stock_actual
FROM productos p
LEFT JOIN inventarios i ON p.id_producto = i.id_producto
GROUP BY p.id_producto;

