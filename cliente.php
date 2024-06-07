<?php
include 'menu.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['email']) && isset($_POST['telefono'])) {
        // Inserción de nuevo cliente
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];

        $stmt = $conexion->prepare("INSERT INTO cliente (nombre, apellido, email, telefono) VALUES (:nombre, :apellido, :email, :telefono)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
    } elseif (isset($_POST['eliminar_id'])) {
        // Eliminación de un cliente
        $id = $_POST['eliminar_id'];
        $stmt = $conexion->prepare("DELETE FROM cliente WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Listado de Clientes</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener los clientes
                    $consulta = $conexion->query("SELECT * FROM cliente");

                    // Iterar sobre los resultados y mostrar cada cliente
                    while ($cliente = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $cliente['id'] . '</td>';
                        echo '<td>' . $cliente['nombre'] . '</td>';
                        echo '<td>' . $cliente['apellido'] . '</td>';
                        echo '<td>' . $cliente['email'] . '</td>';
                        echo '<td>' . $cliente['telefono'] . '</td>';
                        echo '<td>';
                        echo '<form action="cliente.php" method="POST" class="d-inline">';
                        echo '<input type="hidden" name="eliminar_id" value="' . $cliente['id'] . '">';
                        echo '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h2>Agregar Nuevo Cliente</h2>
            <form action="cliente.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Agregar Cliente</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
