<!-- views/shared/planes_accion_modal_crear.php -->
<!-- H-6778: Componente reutilizable - Modal para crear un plan de acción -->
<!-- Variables requeridas: $entity, $id_registro, $estados, $usuarios -->
<div class="modal fade" id="modalCrearPlan" tabindex="-1" role="dialog" aria-labelledby="modalCrearPlanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="index.php?entity=<?= $entity ?>&action=planes_accion&id=<?= $id_registro ?>" method="POST">
        <input type="hidden" name="action_plan" value="create">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearPlanLabel">Crear Plan de Acción</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Campos del formulario -->
          <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" name="descripcion" required></textarea>
          </div>
          <div class="form-group">
              <label for="id_usuario">Usuario Responsable</label>
              <select class="form-control" name="id_usuario" required>
                  <?php foreach ($usuarios as $usuario): ?>
                      <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="form-group">
              <label for="fecha_inicio">Fecha Inicio</label>
              <input type="date" class="form-control" name="fecha_inicio" required>
          </div>
          <div class="form-group">
              <label for="fecha_fin">Fecha Fin</label>
              <input type="date" class="form-control" name="fecha_fin" required>
          </div>
          <div class="form-group">
              <label for="id_estado">Estado</label>
              <select class="form-control" name="id_estado" required>
                  <?php foreach ($estados as $estado): ?>
                      <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Crear</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
