<!-- views/auditoria/resultados.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de la Prueba <?= $prueba['id'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h2>Resultados de la Prueba: <?= $prueba['nombre'] ?></h2>
    <p><strong>Prueba aplicada:</strong> <?= $prueba['codigo'] ?> - <?= $prueba['descripcion'] ?></p>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearResultado">Agregar Resultado</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Observaciones</th>
                <th>Fecha Observación</th>
                <th>Realizado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $resultado): ?>
            <tr>
                <td><?= $resultado['id'] ?></td>
                <td><?= $resultado['tipo'] ?></td>
                <td><?= $resultado['descripcion'] ?></td>
                <td><?= $resultado['observaciones'] ?></td>
                <td><?= $resultado['fecha_observacion'] ?></td>
                <td><?= $resultado['usuario_nombre'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarResultado<?= $resultado['id'] ?>">Editar</button>
                    <a href="index.php?entity=auditoria&action=resultados&id=<?= $prueba['id'] ?>&delete_resultado=<?= $resultado['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este resultado?')">Eliminar</a>
                </td>
            </tr>
            <!-- Modal para editar resultado -->
            <div class="modal fade" id="modalEditarResultado<?= $resultado['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form action="index.php?entity=auditoria&action=resultados&id=<?= $prueba['id'] ?>" method="POST">
                    <input type="hidden" name="action_resultado" value="edit">
                    <input type="hidden" name="id_resultado" value="<?= $resultado['id'] ?>">
                    <div class="modal-header">
                      <h5 class="modal-title">Editar Resultado</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                          <label>Tipo</label>
                          <select class="form-control" name="tipo">
                              <option value="Resultado" <?= ($resultado['tipo'] == 'Resultado') ? 'selected' : '' ?>>Resultado</option>
                              <option value="Observación" <?= ($resultado['tipo'] == 'Observación') ? 'selected' : '' ?>>Observación</option>
                              <option value="Hallazgo" <?= ($resultado['tipo'] == 'Hallazgo') ? 'selected' : '' ?>>Hallazgo</option>
                              <option value="Seguimiento" <?= ($resultado['tipo'] == 'Seguimiento') ? 'selected' : '' ?>>Seguimiento</option>
                          </select>
                      </div>
                      <div class="form-group">
                          <label>Descripción</label>
                          <textarea class="form-control" name="descripcion"><?= $resultado['descripcion'] ?></textarea>
                      </div>
                      <div class="form-group">
                          <label>Observaciones</label>
                          <textarea class="form-control" name="observaciones"><?= $resultado['observaciones'] ?></textarea>
                      </div>
                      <div class="form-group">
                          <label>Fecha de Observación</label>
                          <input type="date" class="form-control" name="fecha_observacion" value="<?= $resultado['fecha_observacion'] ?>">
                      </div>
                      <div class="form-group">
                          <label>Realizado por</label>
                          <select class="form-control" name="id_usuario">
                              <?php foreach ($usuarios as $usuario): ?>
                                  <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] == $resultado['id_usuario']) ? 'selected' : '' ?>><?= $usuario['nombre'] ?></option>
                              <?php endforeach; ?>
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

    <!-- Modal para crear resultado -->
    <div class="modal fade" id="modalCrearResultado" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="index.php?entity=auditoria&action=resultados&id=<?= $prueba['id'] ?>" method="POST">
            <input type="hidden" name="action_resultado" value="create">
            <div class="modal-header">
              <h5 class="modal-title">Crear Resultado</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label>Tipo</label>
                  <select class="form-control" name="tipo">
                      <option value="Resultado">Resultado</option>
                      <option value="Observación">Observación</option>
                      <option value="Hallazgo">Hallazgo</option>
                      <option value="Seguimiento">Seguimiento</option>
                  </select>
              </div>
              <div class="form-group">
                  <label>Descripción</label>
                  <textarea class="form-control" name="descripcion"></textarea>
              </div>
              <div class="form-group">
                  <label>Observaciones</label>
                  <textarea class="form-control" name="observaciones"></textarea>
              </div>
              <div class="form-group">
                  <label>Fecha de Observación</label>
                  <input type="date" class="form-control" name="fecha_observacion">
              </div>
              <div class="form-group">
                  <label>Realizado por</label>
                  <select class="form-control" name="id_usuario">
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

    <a href="index.php?entity=auditoria&action=pruebas&id=<?= $prueba['id_tarea'] ?>" class="btn btn-secondary">Volver a Pruebas</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
