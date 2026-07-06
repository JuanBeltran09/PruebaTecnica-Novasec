<!-- views/auditoria/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Auditorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Listado de Auditorías</h1>
    <a href="index.php?entity=auditoria&action=create" class="btn btn-primary mb-3">Agregar</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Alcance</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                <th>Responsable</th>
                <th>Creador</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planes as $plan): ?>
            <tr>
                <td><?= $plan['codigo'] ?></td>
                <td><?= $plan['nombre'] ?></td>
                <td><?= $plan['tipo_plan'] ?></td>
                <td><?= $plan['alcance'] ?></td>
                <td><?= $plan['fecha_inicio'] ?></td>
                <td><?= $plan['fecha_fin'] ?></td>
                <td><?= $plan['estado_nombre'] ?></td>
                <td><?= $plan['responsable_nombre'] ?></td>
                <td><?= $plan['creador_nombre'] ?></td>
                <td>
                    <a href="index.php?entity=auditoria&action=show&id=<?= $plan['id'] ?>" class="btn btn-info btn-sm">Ver</a>
                    <a href="index.php?entity=auditoria&action=edit&id=<?= $plan['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" class="btn btn-secondary btn-sm">Tareas</a>
                    <a href="index.php?entity=auditoria&action=delete&id=<?= $plan['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
