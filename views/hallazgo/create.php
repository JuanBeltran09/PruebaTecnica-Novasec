<!-- views/hallazgo/create.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Hallazgo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <!-- H-6336: Choices.js CSS para la interfaz de etiquetas visuales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        /* H-6336: Ajustes para integrar Choices.js con el estilo Bootstrap del proyecto */
        .choices__inner {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            min-height: 38px;
            background-color: #fff;
            padding: 4px 7.5px 0;
        }
        .choices__list--multiple .choices__item {
            background-color: #007bff;
            border: 1px solid #0069d9;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.875rem;
            margin-bottom: 4px;
        }
        .choices__list--multiple .choices__item .choices__button {
            border-left: 1px solid rgba(255,255,255,0.4);
            margin-left: 6px;
            padding-left: 6px;
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #007bff;
            color: #fff;
        }
        .choices__input {
            background-color: transparent;
        }
        .choices[data-type*=select-multiple] .choices__inner {
            cursor: text;
        }
    </style>
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Crear Hallazgo</h1>
    <form action="index.php?entity=hallazgo&action=create" method="POST">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="form-group">
            <label for="id_estado">Estado</label>
            <select class="form-control" id="id_estado" name="id_estado" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_usuario">Usuario Responsable</label>
            <select class="form-control" id="id_usuario" name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- H-6336: Se mantiene el select multiple con el mismo name="procesos[]" para compatibilidad con el backend.
             Choices.js lo transforma visualmente en etiquetas sin alterar la estructura de datos enviada. -->
        <div class="form-group">
            <label for="procesos">Procesos</label>
            <select multiple class="form-control" id="procesos" name="procesos[]">
                <?php foreach ($procesos as $proceso): ?>
                    <option value="<?= $proceso['id'] ?>"><?= $proceso['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Escriba para buscar o haga clic para seleccionar los procesos asociados.</small>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<!-- H-6336: Choices.js - Librería ligera para transformar el select múltiple en etiquetas visuales -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    // H-6336: Inicialización de Choices.js sobre el selector de procesos
    document.addEventListener('DOMContentLoaded', function() {
        var selectProcesos = document.getElementById('procesos');
        new Choices(selectProcesos, {
            removeItemButton: true,          // Botón X para quitar etiquetas
            searchEnabled: true,             // Búsqueda por texto
            placeholderValue: 'Seleccione los procesos...',
            searchPlaceholderValue: 'Buscar proceso...',
            noResultsText: 'No se encontraron procesos',
            noChoicesText: 'No hay más procesos disponibles',
            itemSelectText: 'Clic para seleccionar',
            shouldSort: false                // Mantener el orden original de la BD
        });
    });
</script>
</body>
</html>