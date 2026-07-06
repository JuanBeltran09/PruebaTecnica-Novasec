<!-- views/auditoria/pruebas.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pruebas de la Tarea <?= $tarea['id'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h2>Pruebas de la Tarea: <?= $tarea['nombre'] ?></h2>
    <p><strong>Descripción de la Tarea:</strong> <?= $tarea['descripcion'] ?></p>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearPrueba">Agregar Prueba</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Estado</th>
                <th>Creador</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pruebas as $prueba): ?>
            <tr>
                <td><?= $prueba['codigo'] ?></td>
                <td><?= $prueba['nombre'] ?></td>
                <td><?= $prueba['tipo'] ?></td>
                <td><?= $prueba['descripcion'] ?></td>
                <td><?= $prueba['categoria'] ?></td>
                <td><?= $prueba['subcategoria'] ?></td>
                <td><?= $prueba['estado_prueba'] ?></td>
                <td><?= $prueba['creador_nombre'] ?></td>
                <td><?= $prueba['fecha_creacion'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarPrueba<?= $prueba['id'] ?>">Editar</button>
                    <a href="index.php?entity=auditoria&action=resultados&id=<?= $prueba['id'] ?>" class="btn btn-success btn-sm">Resultados</a>
                    <a href="index.php?entity=auditoria&action=pruebas&id=<?= $tarea['id'] ?>&delete_prueba=<?= $prueba['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta prueba?')">Eliminar</a>
                </td>
            </tr>
            <!-- Modal para editar prueba -->
            <div class="modal fade" id="modalEditarPrueba<?= $prueba['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form action="index.php?entity=auditoria&action=pruebas&id=<?= $tarea['id'] ?>" method="POST">
                    <input type="hidden" name="action_prueba" value="edit">
                    <input type="hidden" name="id_prueba" value="<?= $prueba['id'] ?>">
                    <div class="modal-header">
                      <h5 class="modal-title">Editar Prueba</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                          <label>Código</label>
                          <input type="text" class="form-control" name="codigo" value="<?= $prueba['codigo'] ?>">
                      </div>
                      <div class="form-group">
                          <label>Nombre *</label>
                          <input type="text" class="form-control" name="nombre" value="<?= $prueba['nombre'] ?>" required>
                      </div>
                      <div class="form-group">
                          <label>Tipo *</label>
                          <select class="form-control" name="tipo" required>
                              <option value="Funcional" <?= ($prueba['tipo'] == 'Funcional') ? 'selected' : '' ?>>Funcional</option>
                              <option value="Técnica" <?= ($prueba['tipo'] == 'Técnica') ? 'selected' : '' ?>>Técnica</option>
                              <option value="Incursión" <?= ($prueba['tipo'] == 'Incursión') ? 'selected' : '' ?>>Incursión</option>
                          </select>
                      </div>
                      <div class="form-group">
                          <label>Descripción</label>
                          <textarea class="form-control" name="descripcion"><?= $prueba['descripcion'] ?></textarea>
                      </div>
                      <div class="form-group">
                          <label>Categoría</label>
                          <input type="text" class="form-control" name="categoria" value="<?= $prueba['categoria'] ?>">
                      </div>
                      <div class="form-group">
                          <label>Subcategoría</label>
                          <input type="text" class="form-control" name="subcategoria" value="<?= $prueba['subcategoria'] ?>">
                      </div>
                      <div class="form-group">
                          <label>Estado de la prueba</label>
                          <select class="form-control" name="estado_prueba">
                              <option value="Incompleta" <?= ($prueba['estado_prueba'] == 'Incompleta') ? 'selected' : '' ?>>Incompleta</option>
                              <option value="En desarrollo" <?= ($prueba['estado_prueba'] == 'En desarrollo') ? 'selected' : '' ?>>En desarrollo</option>
                              <option value="Finalizada" <?= ($prueba['estado_prueba'] == 'Finalizada') ? 'selected' : '' ?>>Finalizada</option>
                          </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal para crear prueba -->
    <div class="modal fade" id="modalCrearPrueba" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="index.php?entity=auditoria&action=pruebas&id=<?= $tarea['id'] ?>" method="POST">
            <input type="hidden" name="action_prueba" value="create">
            <div class="modal-header">
              <h5 class="modal-title">Crear Prueba</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label>Código</label>
                  <input type="text" class="form-control" name="codigo">
              </div>
              <div class="form-group">
                  <label>Nombre *</label>
                  <input type="text" class="form-control" name="nombre" required>
              </div>
              <div class="form-group">
                  <label>Tipo *</label>
                  <select class="form-control" name="tipo" required>
                      <option value="Funcional">Funcional</option>
                      <option value="Técnica">Técnica</option>
                      <option value="Incursión">Incursión</option>
                  </select>
              </div>
              <div class="form-group">
                  <label>Descripción</label>
                  <textarea class="form-control" name="descripcion"></textarea>
              </div>
              <div class="form-group">
                  <label>Categoría</label>
                  <input type="text" class="form-control" name="categoria">
              </div>
              <div class="form-group">
                  <label>Subcategoría</label>
                  <input type="text" class="form-control" name="subcategoria">
              </div>
              <div class="form-group">
                  <label>Estado de la prueba</label>
                  <select class="form-control" name="estado_prueba">
                      <option value="Incompleta">Incompleta</option>
                      <option value="En desarrollo">En desarrollo</option>
                      <option value="Finalizada">Finalizada</option>
                  </select>
              </div>
              <div class="form-group">
                  <label>Creador</label>
                  <select class="form-control" name="id_creador">
                      <?php foreach ($usuarios as $usuario): ?>
                          <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="index.php?entity=auditoria&action=tareas&id=<?= $tarea['id_plan'] ?>" class="btn btn-secondary">Volver a Tareas</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
