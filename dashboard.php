<?php
include 'menu.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success text-center">
                <h4>Bienvenido, <?php print $_SESSION['usuario'] ?></h4>
            </div>
        </div>
    </div>

    <div class="row">
        <?php
        include "conexion.php";
        // Consulta para obtener los productos
        $consulta = $conexion->query("SELECT * FROM producto");

        // Iterar sobre los resultados y mostrar cada producto
        while ($producto = $consulta->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card">';
            echo '<img src="' . $producto['foto'] . '" class="card-img-top" alt="' . $producto['nombre'] . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $producto['nombre'] . '</h5>';
            echo '<p class="card-text"><strong>Precio: </strong>$' . number_format($producto['precio']) . '</p>';
            echo '<p class="card-text"><strong>Disponibilidad: </strong>' . ($producto['disponibilidad'] ? 'Disponible' : 'No disponible') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>
</body>
</html>


