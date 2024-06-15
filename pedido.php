<?php
include 'menu.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cliente_id']) && isset($_POST['fecha']) && isset($_POST['total']) && isset($_POST['productos']) && isset($_POST['cantidades'])) {
        // Inserción de nuevo pedido
        $cliente_id = $_POST['cliente_id'];
        $fecha = $_POST['fecha'];
        $total = $_POST['total'];
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidades'];

        // Insertar el pedido
        $stmt = $conexion->prepare("INSERT INTO pedido (cliente_id, fecha, total) VALUES (:cliente_id, :fecha, :total)");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':total', $total);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Pedido agregado correctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            </script>";
        }   
        
        // Obtener el ID del pedido insertado
        $pedido_id = $conexion->lastInsertId();

        // Insertar los detalles del pedido
        foreach ($productos as $index => $producto_id) {
            $cantidad = $cantidades[$index];
            $stmt = $conexion->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio) VALUES (:id_pedido, :id_producto, :cantidad, (SELECT precio FROM producto WHERE id = :producto_id))");
            $stmt->bindParam(':id_pedido', $pedido_id);
            $stmt->bindParam(':id_producto', $producto_id);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':producto_id', $producto_id);
            $stmt->execute();
        }
    } elseif (isset($_POST['eliminar_id'])) {
        // Eliminación de un pedido
        $id = $_POST['eliminar_id'];
        $stmt = $conexion->prepare("DELETE FROM pedido WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php
            // Consulta para obtener los pedidos
            $consulta = $conexion->query("SELECT p.id, c.nombre || ' ' || c.apellido AS cliente, p.fecha, p.total FROM pedido p INNER JOIN cliente c ON p.cliente_id = c.id");
            ?>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Listado de Pedidos</h1>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Iterar sobre los resultados y mostrar cada pedido
                                while ($pedido = $consulta->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<td>' . $pedido['id'] . '</td>';
                                    echo '<td>' . $pedido['cliente'] . '</td>';
                                    echo '<td>' . $pedido['fecha'] . '</td>';
                                    echo '<td>' . $pedido['total'] . '</td>';
                                    echo '<td>';
                                    echo '<form action="pedido.php" method="POST" class="d-inline">';
                                    echo '<input type="hidden" name="eliminar_id" value="' . $pedido['id'] . '">';
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
            </div>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2>Cabecera del Pedido</h2>
            <form action="pedido.php" method="POST">
                <!-- Campos para cliente, fecha y total -->
                <div class="mb-3">
                    <label for="cliente_id" class="form-label">Cliente:</label>
                    <select id="cliente_id" name="cliente_id" class="form-select" required>
                        <?php
                        // Consulta para obtener los clientes
                        $clientes = $conexion->query("SELECT id, nombre || ' ' || apellido AS nombre_completo FROM cliente");
                        while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $cliente['id'] . '">' . $cliente['nombre_completo'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total:</label>
                    <input type="number" id="total" name="total" class="form-control" step="0.01" required>
                </div>
        </div>
        <div class="col-md-6">
            <h2>Detalles del Pedido</h2>
            <div class="mb-3">
                <label for="productos" class="form-label">Productos:</label>
                <select id="productos" name="productos[]" class="form-select" multiple required>
                    <?php
                    // Consulta para obtener los productos
                    $productos = $conexion->query("SELECT id, nombre FROM producto");
                    while ($producto = $productos->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $producto['id'] . '">' . $producto['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidades" class="form-label">Cantidades:</label>
                <input type="text" id="cantidades" name="cantidades[]" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Pedido</button>
        </form>
    </div>
</div>
</div>