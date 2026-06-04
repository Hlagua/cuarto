<?php
/**
 * Ejecutar una vez: http://localhost/cuarto/database/instalar.php
 * Crea tablas y usuarios de prueba.
 */
require_once __DIR__ . '/../models/conexion.php';

$queries = [
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        rol ENUM('ADMIN', 'CLIENTE') NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT NOT NULL,
        precio DECIMAL(10, 2) NOT NULL,
        imagen VARCHAR(255) NOT NULL DEFAULT 'sin_imagen.jpg'
    )",
    "CREATE TABLE IF NOT EXISTS ventas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        total DECIMAL(10, 2) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS detalle_venta (
        id INT AUTO_INCREMENT PRIMARY KEY,
        venta_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT NOT NULL,
        subtotal DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
        FOREIGN KEY (producto_id) REFERENCES productos(id)
    )"
];

foreach ($queries as $sql) {
    if (!$conn->query($sql)) {
        die('Error SQL: ' . $conn->error);
    }
}

$usuarios = [
    ['admin', 'admin123', 'ADMIN'],
    ['cliente', 'cliente123', 'CLIENTE'],
];

foreach ($usuarios as $u) {
    $hash = password_hash($u[1], PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT IGNORE INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $u[0], $hash, $u[2]);
    $stmt->execute();
    $stmt->close();
}

$count = $conn->query('SELECT COUNT(*) AS c FROM productos')->fetch_assoc()['c'];
if ((int) $count === 0) {
    $productos = [
        ['Laptop UTA', 'Laptop para estudiantes de la FISEI', 850.00, 'sin_imagen.jpg'],
        ['Mouse inalámbrico', 'Mouse ergonómico color negro', 15.50, 'sin_imagen.jpg'],
        ['Teclado mecánico', 'Teclado RGB switches azules', 45.00, 'sin_imagen.jpg'],
    ];
    $stmt = $conn->prepare('INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)');
    foreach ($productos as $p) {
        $stmt->bind_param('ssds', $p[0], $p[1], $p[2], $p[3]);
        $stmt->execute();
    }
    $stmt->close();
}

echo '<h2>Instalación completada</h2>';
echo '<p>Usuarios: admin / admin123 (ADMIN), cliente / cliente123 (CLIENTE)</p>';
echo '<p><a href="../index.php?accion=Nosotros">Ir a Nosotros</a></p>';
