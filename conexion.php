<?php
// Nombre del archivo de la base de datos SQLite
$database_file = 'tienda.sqlite';

// Intentar la conexión a la base de datos SQLite
$conexion = new PDO("sqlite:$database_file");
?>