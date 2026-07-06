<!-- views/auditoria/show.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Plan de Auditoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Detalle del Plan de Auditoría</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= $plan['codigo'] ?> - <?= $plan['nombre'] ?></h5>
            <p class="card-text">Alcance: <?= $plan['alcance'] ?></p>
            <p class="card-text">Justificación: <?= $plan['justificacion'] ?></p>
            <p class="card-text">Tipo de plan: <?= $plan['tipo_plan'] ?></p>
            <p class="card-text">Costo estimado: <?= $plan['costo_estimado'] ?></p>
            <p class="card-text">Fecha de inicio: <?= $plan['fecha_inicio'] ?></p>
            <p class="card-text">Fecha de fin: <?= $plan['fecha_fin'] ?></p>
            <p class="card-text">Estado: <?= $plan['estado_nombre'] ?></p>
            <p class="card-text">Responsable: <?= $plan['responsable_nombre'] ?></p>
            <p class="card-text">Creador: <?= $plan['creador_nombre'] ?></p>
            <a href="index.php?entity=auditoria&action=edit&id=<?= $plan['id'] ?>" class="btn btn-warning">Editar</a>
            <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" class="btn btn-secondary">Tareas</a>
            <a href="index.php?entity=auditoria&action=index" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
</div>
</body>
</html>
