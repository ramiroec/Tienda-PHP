<?php
// Nombre del archivo de la base de datos
$dbFile = 'tienda.sqlite';

try {
    // Conexión a la base de datos SQLite
    $db = new PDO("sqlite:$dbFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear las tablas
    $db->exec("
        CREATE TABLE IF NOT EXISTS marca (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS producto (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            precio REAL NOT NULL,
            foto TEXT NOT NULL,
            disponibilidad INTEGER NOT NULL CHECK (disponibilidad IN (0, 1)),            id_marca INTEGER,
            FOREIGN KEY (id_marca) REFERENCES marca(id)
        );

        CREATE TABLE IF NOT EXISTS cliente (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            apellido TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            telefono TEXT
        );

        CREATE TABLE IF NOT EXISTS pedido (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            cliente_id INTEGER,
            fecha DATE NOT NULL,
            total REAL NOT NULL,
            FOREIGN KEY (cliente_id) REFERENCES cliente(id)
        );

        CREATE TABLE IF NOT EXISTS detalles_pedido (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            id_pedido INTEGER,
            id_producto INTEGER,
            cantidad INTEGER NOT NULL,
            precio REAL NOT NULL,
            FOREIGN KEY (id_pedido) REFERENCES pedido(id),
            FOREIGN KEY (id_producto) REFERENCES producto(id)
        );

        CREATE TABLE IF NOT EXISTS usuario (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre_usuario TEXT NOT NULL UNIQUE,
            contrasena TEXT NOT NULL,
            nombre TEXT NOT NULL,
            apellido TEXT NOT NULL,
            rol TEXT NOT NULL
        );
    ");

    // Insertar un usuario
    $nombreUsuario = 'admin';
    $contrasena = 'admin123';
    $nombre = 'Admin';
    $apellido = 'User';
    $rol = 'administrador';

    $stmt = $db->prepare("
        INSERT INTO usuario (nombre_usuario, contrasena, nombre, apellido, rol)
        VALUES (:nombre_usuario, :contrasena, :nombre, :apellido, :rol)
    ");
    $stmt->bindParam(':nombre_usuario', $nombreUsuario);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':rol', $rol);

    $stmt->execute();

    echo "Base de datos y tablas creadas exitosamente. Usuario insertado.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
