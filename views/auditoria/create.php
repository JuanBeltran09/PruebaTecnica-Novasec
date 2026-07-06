<!-- views/auditoria/create.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Plan de Auditoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Crear Plan de Auditoría</h1>
    <form action="index.php?entity=auditoria&action=create" method="POST">
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="alcance">Alcance *</label>
            <textarea class="form-control" id="alcance" name="alcance" required></textarea>
        </div>
        <div class="form-group">
            <label for="justificacion">Justificación</label>
            <textarea class="form-control" id="justificacion" name="justificacion"></textarea>
        </div>
        <div class="form-group">
            <label for="tipo_plan">Tipo de plan *</label>
            <select class="form-control" id="tipo_plan" name="tipo_plan" required>
                <option value="Interna">Interna</option>
                <option value="Externa">Externa</option>
                <option value="Seguimiento">Seguimiento</option>
            </select>
        </div>
        <div class="form-group">
            <label for="costo_estimado">Costo estimado</label>
            <input type="number" step="0.01" class="form-control" id="costo_estimado" name="costo_estimado">
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha de inicio *</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha de fin *</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
        </div>
        <div class="form-group">
            <label for="id_estado">Estado *</label>
            <select class="form-control" id="id_estado" name="id_estado" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_responsable">Responsable *</label>
            <select class="form-control" id="id_responsable" name="id_responsable" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php?entity=auditoria&action=index" class="btn btn-secondary">Regresar</a>
    </form>
</div>
</body>
</html>
