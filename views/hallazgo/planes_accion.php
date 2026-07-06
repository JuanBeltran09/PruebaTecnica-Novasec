<!-- views/hallazgo/planes_accion.php -->
<!-- H-6778: Vista de planes de acción para Hallazgos, reutilizando componentes compartidos -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planes de Acción del Hallazgo <?= $hallazgo['id'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h2>Planes de Acción para el Hallazgo ID: <?= $hallazgo['id'] ?></h2>
    <p><strong>Título del Hallazgo:</strong> <?= $hallazgo['titulo'] ?></p>
    <p><strong>Descripción del Hallazgo:</strong> <?= $hallazgo['descripcion'] ?></p>
    <!-- Botón para crear un nuevo plan de acción -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearPlan">Crear Plan de Acción</button>

    <!-- H-6778: Inclusión del componente compartido de tabla de planes de acción -->
    <?php
        $id_registro = $hallazgo['id'];
        include 'views/shared/planes_accion_tabla.php';
    ?>

    <!-- H-6778: Inclusión del componente compartido del modal de creación -->
    <?php include 'views/shared/planes_accion_modal_crear.php'; ?>

    <!-- Botón para regresar al listado de hallazgos -->
    <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Volver a Hallazgos</a>
</div>
<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
