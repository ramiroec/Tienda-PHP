<?php
include 'menu.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'])) {
        // Inserción de nueva marca
        $nombre = $_POST['nombre'];
        $stmt = $conexion->prepare("INSERT INTO marca (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre);

        try {
            $stmt = $conexion->prepare("INSERT INTO marca (nombre) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);

            if ($stmt->execute()) {
                echo "<script>
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Marca agregada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                </script>";
            }
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al agregar la marca: " . $e->getMessage() . "',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            </script>";
        }


        
    } elseif (isset($_POST['eliminar_id'])) {
        // Eliminación de una marca
        $id = $_POST['eliminar_id'];
        $stmt = $conexion->prepare("DELETE FROM marca WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Listado de Marcas</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener las marcas
                    $consulta = $conexion->query("SELECT * FROM marca");

                    // Iterar sobre los resultados y mostrar cada marca
                    while ($marca = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $marca['id'] . '</td>';
                        echo '<td>' . $marca['nombre'] . '</td>';
                        echo '<td>';
                        echo '<form action="marca.php" method="POST" class="d-inline">';
                        echo '<input type="hidden" name="eliminar_id" value="' . $marca['id'] . '">';
                        echo '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h2>Agregar Nueva Marca</h2>
            <form action="marca.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Marca</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
