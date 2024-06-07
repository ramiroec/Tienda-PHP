<?php
include 'menu.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['foto']) && isset($_POST['disponibilidad']) && isset($_POST['id_marca'])) {
        // Inserción de nuevo producto
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $foto = $_POST['foto'];
        $disponibilidad = $_POST['disponibilidad'];
        $id_marca = $_POST['id_marca'];

        // Insertar el producto
        $stmt = $conexion->prepare("INSERT INTO producto (nombre, precio, foto, disponibilidad, id_marca) VALUES (:nombre, :precio, :foto, :disponibilidad, :id_marca)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':disponibilidad', $disponibilidad);
        $stmt->bindParam(':id_marca', $id_marca);
        $stmt->execute();
    } elseif (isset($_POST['eliminar_id'])) {
        // Eliminación de un producto
        $id = $_POST['eliminar_id'];
        $stmt = $conexion->prepare("DELETE FROM producto WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Listado de Productos</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Foto</th>
                        <th>Disponibilidad</th>
                        <th>Marca</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener los productos
                    $consulta = $conexion->query("SELECT p.id, p.nombre, p.precio, p.foto, p.disponibilidad, m.nombre AS marca FROM producto p LEFT JOIN marca m ON p.id_marca = m.id");
                    // Iterar sobre los resultados y mostrar cada producto
                    while ($producto = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $producto['id'] . '</td>';
                        echo '<td>' . $producto['nombre'] . '</td>';
                        echo '<td>' . $producto['precio'] . '</td>';
                        echo '<td><img src="' . $producto['foto'] . '" alt="' . $producto['nombre'] . '" style="width: 50px; height: 50px;"></td>';
                        echo '<td>' . ($producto['disponibilidad'] ? 'Disponible' : 'No disponible') . '</td>';
                        echo '<td>' . $producto['marca'] . '</td>';
                        echo '<td>';
                        echo '<form action="producto.php" method="POST" class="d-inline">';
                        echo '<input type="hidden" name="eliminar_id" value="' . $producto['id'] . '">';
                        echo '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <h2>Agregar Nuevo Producto</h2>
            <form action="producto.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" id="precio" name="precio" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">URL de la foto:</label>
                    <input type="text" id="foto" name="foto" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="disponibilidad" class="form-label">Disponibilidad:</label>
                    <select id="disponibilidad" name="disponibilidad" class="form-select" required>
                        <option value="1">Disponible</option>
                        <option value="0">No disponible</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_marca" class="form-label">Marca:</label>
                    <select id="id_marca" name="id_marca" class="form-select" required>
                        <?php
                        // Consulta para obtener las marcas
                        $marcas = $conexion->query("SELECT id, nombre FROM marca");
                        while ($marca = $marcas->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $marca['id'] . '">' . $marca['nombre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>
    </div>
</div>
