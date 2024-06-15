<?php
include 'menu.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre_usuario']) && isset($_POST['contrasena']) && isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['rol'])) {
        // Inserción de nuevo usuario
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];
        //$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $rol = $_POST['rol'];

        $stmt = $conexion->prepare("INSERT INTO usuario (nombre_usuario, contrasena, nombre, apellido, rol) VALUES (:nombre_usuario, :contrasena, :nombre, :apellido, :rol)");
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
    } elseif (isset($_POST['eliminar_id'])) {
        // Eliminación de un usuario
        $id = $_POST['eliminar_id'];
        $stmt = $conexion->prepare("DELETE FROM usuario WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Listado de Usuarios</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Contraseña</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener los usuarios
                    $consulta = $conexion->query("SELECT * FROM usuario");

                    // Iterar sobre los resultados y mostrar cada usuario
                    while ($usuario = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $usuario['id'] . '</td>';
                        echo '<td>' . $usuario['nombre_usuario'] . '</td>';
                        echo '<td>' . $usuario['contrasena'] . '</td>';
                        echo '<td>' . $usuario['nombre'] . '</td>';
                        echo '<td>' . $usuario['apellido'] . '</td>';
                        echo '<td>' . $usuario['rol'] . '</td>';
                        echo '<td>';
                        echo '<form action="usuario.php" method="POST" class="d-inline">';
                        echo '<input type="hidden" name="eliminar_id" value="' . $usuario['id'] . '">';
                        echo '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h2>Agregar Nuevo Usuario</h2>
            <form action="usuario.php" method="POST">
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol:</label>
                    <select id="rol" name="rol" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
