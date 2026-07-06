<!-- views/auditoria/tareas.php -->
<?php
// Mapa por id y cálculo de nivel jerárquico (para indentar el listado)
$byId = [];
foreach ($tareas as $t) { $byId[$t['id']] = $t; }
if (!function_exists('nivelTarea')) {
    function nivelTarea($t, $byId) {
        $n = 0; $p = $t['id_padre']; $g = 0;
        while (!empty($p) && isset($byId[$p]) && $g < 50) { $n++; $p = $byId[$p]['id_padre']; $g++; }
        return $n;
    }
}
// Colores de estado (paleta del manual)
$colores = [
    'Activa' => '#28a745', 'Completada' => '#007bff', 'Fallida' => '#6f42c1',
    'Indefinida' => '#adb5bd', 'Suspendida' => '#fd7e14'
];
// Rango de fechas para el Gantt
$min = null; $max = null;
foreach ($tareas as $t) {
    if (!empty($t['fecha_inicio'])) { $d = strtotime($t['fecha_inicio']); if ($min === null || $d < $min) $min = $d; }
    if (!empty($t['fecha_fin']))    { $d = strtotime($t['fecha_fin']);    if ($max === null || $d > $max) $max = $d; }
}
$totalDias = ($min !== null && $max !== null) ? max(1, round(($max - $min) / 86400) + 1) : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas del Plan <?= $plan['id'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <style>
        .gantt-row { display: flex; align-items: center; border-bottom: 1px solid #eee; }
        .gantt-label { width: 320px; flex: 0 0 320px; padding: 4px 8px; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .gantt-track { position: relative; flex: 1 1 auto; height: 26px; background:
            repeating-linear-gradient(to right, #f6f6f6, #f6f6f6 13px, #efefef 13px, #efefef 14px); }
        .gantt-bar { position: absolute; top: 5px; height: 16px; border-radius: 3px; color: #fff; font-size: 11px; line-height: 16px; padding: 0 4px; overflow: hidden; }
        .gantt-bar .progreso { position: absolute; left: 0; top: 0; height: 100%; background: rgba(0,0,0,0.25); border-radius: 3px 0 0 3px; }
        .gantt-header { display: flex; font-weight: bold; background: #f1f1f1; border-bottom: 2px solid #ccc; }
        .estado-cuadro { display: inline-block; width: 14px; height: 14px; border-radius: 3px; margin-right: 6px; vertical-align: middle; border: 1px solid #999; }
        .leyenda span { margin-right: 14px; font-size: 12px; }
        .modal-lg-custom { max-width: 800px; }
    </style>
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container-fluid mt-4">
    <h2>Tareas del Plan: <?= $plan['nombre'] ?></h2>
    <p><strong>Alcance del Plan:</strong> <?= $plan['alcance'] ?></p>

    <div class="leyenda mb-2">
        <strong>Estados:</strong>
        <?php foreach ($colores as $est => $col): ?>
            <span><span class="estado-cuadro" style="background: <?= $col ?>"></span><?= $est ?></span>
        <?php endforeach; ?>
    </div>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearTarea">Agregar Tarea</button>

    <!-- ====================== GANTT ====================== -->
    <h4>Cronograma (Gantt)</h4>
    <div class="border mb-4">
        <div class="gantt-header">
            <div class="gantt-label">Tarea</div>
            <div class="gantt-track" style="background:#f1f1f1;">
                <span style="position:absolute;left:4px;"><?= $min ? date('d/m/Y', $min) : '' ?></span>
                <span style="position:absolute;right:4px;"><?= $max ? date('d/m/Y', $max) : '' ?></span>
            </div>
        </div>
        <?php foreach ($tareas as $t): ?>
            <?php
                $nivel = nivelTarea($t, $byId);
                $color = $colores[$t['estado_tarea']] ?? '#6c757d';
                $left = 0; $width = 0;
                if (!empty($t['fecha_inicio']) && !empty($t['fecha_fin']) && $min !== null) {
                    $ini = strtotime($t['fecha_inicio']); $fin = strtotime($t['fecha_fin']);
                    $off = round(($ini - $min) / 86400);
                    $dur = max(1, round(($fin - $ini) / 86400) + 1);
                    $left = ($off / $totalDias) * 100;
                    $width = ($dur / $totalDias) * 100;
                }
            ?>
            <div class="gantt-row">
                <div class="gantt-label" style="padding-left: <?= 8 + $nivel * 20 ?>px;">
                    <?php if ($nivel > 0): ?>↳ <?php endif; ?><?= $t['codigo'] ?> <?= $t['nombre'] ?>
                </div>
                <div class="gantt-track">
                    <?php if ($width > 0): ?>
                    <div class="gantt-bar" style="left: <?= $left ?>%; width: <?= $width ?>%; background: <?= $color ?>;" title="<?= $t['nombre'] ?> (<?= $t['progreso'] ?>%)">
                        <div class="progreso" style="width: <?= (int)$t['progreso'] ?>%;"></div>
                        <span style="position:relative;"><?= $t['progreso'] ?>%</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ====================== LISTADO JERÁRQUICO ====================== -->
    <h4>Listado jerárquico de tareas</h4>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Responsable</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Dur. est.</th>
                <th>Dur. real</th>
                <th>Prioridad</th>
                <th>Progreso</th>
                <th>Estructura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tareas as $t): ?>
            <?php $nivel = nivelTarea($t, $byId); $color = $colores[$t['estado_tarea']] ?? '#6c757d'; ?>
            <tr>
                <td><span class="estado-cuadro" style="background: <?= $color ?>"></span><?= $t['estado_tarea'] ?></td>
                <td><?= $t['codigo'] ?></td>
                <td style="padding-left: <?= 8 + $nivel * 20 ?>px;"><?php if ($nivel > 0): ?>↳ <?php endif; ?><?= $t['nombre'] ?></td>
                <td><?= $t['responsable_nombre'] ?></td>
                <td><?= $t['fecha_inicio'] ?></td>
                <td><?= $t['fecha_fin'] ?></td>
                <td><?= $t['duracion_estimada'] ?> d</td>
                <td><?= $t['duracion_real'] ?> d</td>
                <td><?= $t['prioridad'] ?></td>
                <td><?= $t['progreso'] ?>%</td>
                <td>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&mover_arriba=<?= $t['id'] ?>" class="btn btn-light btn-sm" title="Mover arriba">&#9650;</a>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&mover_abajo=<?= $t['id'] ?>" class="btn btn-light btn-sm" title="Mover abajo">&#9660;</a>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&indentar=<?= $t['id'] ?>" class="btn btn-light btn-sm" title="Indentar (subtarea)">&#8594;</a>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&desindentar=<?= $t['id'] ?>" class="btn btn-light btn-sm" title="Desindentar">&#8592;</a>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarTarea<?= $t['id'] ?>">Editar</button>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalPR<?= $t['id'] ?>">Pruebas y Resultados</button>
                    <a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&delete_tarea=<?= $t['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta tarea y sus subtareas?')">Eliminar</a>
                </td>
            </tr>

            <!-- ===== Modal EDITAR TAREA ===== -->
            <div class="modal fade" id="modalEditarTarea<?= $t['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form action="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" method="POST">
                    <input type="hidden" name="action_tarea" value="edit">
                    <input type="hidden" name="id_tarea" value="<?= $t['id'] ?>">
                    <div class="modal-header"><h5 class="modal-title">Editar Tarea</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                      <div class="form-group"><label>Código</label><input type="text" class="form-control" name="codigo" value="<?= $t['codigo'] ?>"></div>
                      <div class="form-group"><label>Nombre *</label><input type="text" class="form-control" name="nombre" value="<?= $t['nombre'] ?>" required></div>
                      <div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion"><?= $t['descripcion'] ?></textarea></div>
                      <div class="form-group"><label>Responsable *</label>
                          <select class="form-control" name="id_responsable" required>
                              <?php foreach ($usuarios as $u): ?>
                                  <option value="<?= $u['id'] ?>" <?= ($u['id'] == $t['id_responsable']) ? 'selected' : '' ?>><?= $u['nombre'] ?></option>
                              <?php endforeach; ?>
                          </select></div>
                      <div class="form-group"><label>Fecha Inicio *</label><input type="date" class="form-control" name="fecha_inicio" value="<?= $t['fecha_inicio'] ?>" required></div>
                      <div class="form-group"><label>Fecha Fin *</label><input type="date" class="form-control" name="fecha_fin" value="<?= $t['fecha_fin'] ?>" required></div>
                      <div class="form-group"><label>Duración estimada (días) *</label><input type="number" class="form-control" name="duracion_estimada" value="<?= $t['duracion_estimada'] ?>" required></div>
                      <div class="form-group"><label>Duración real (días)</label><input type="number" class="form-control" name="duracion_real" value="<?= $t['duracion_real'] ?>"></div>
                      <div class="form-group"><label>Prioridad *</label>
                          <select class="form-control" name="prioridad" required>
                              <option value="Alta" <?= ($t['prioridad'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                              <option value="Media" <?= ($t['prioridad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                              <option value="Baja" <?= ($t['prioridad'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
                          </select></div>
                      <div class="form-group"><label>Costo estimado</label><input type="number" step="0.01" class="form-control" name="costo_estimado" value="<?= $t['costo_estimado'] ?>"></div>
                      <div class="form-group"><label>% Progreso</label><input type="number" class="form-control" name="progreso" value="<?= $t['progreso'] ?>"></div>
                      <div class="form-group"><label>Estado *</label>
                          <select class="form-control" name="estado_tarea" required>
                              <?php foreach (array_keys($colores) as $est): ?>
                                  <option value="<?= $est ?>" <?= ($t['estado_tarea'] == $est) ? 'selected' : '' ?>><?= $est ?></option>
                              <?php endforeach; ?>
                          </select></div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Modificar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- ===== Modal PRUEBAS Y RESULTADOS ===== -->
            <div class="modal fade" id="modalPR<?= $t['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Pruebas y Resultados — <?= $t['nombre'] ?></h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">

                      <h6>Pruebas de la tarea</h6>
                      <table class="table table-sm table-bordered">
                        <thead><tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Estado</th><th>Acción</th></tr></thead>
                        <tbody>
                          <?php foreach ($t['pruebas'] as $pr): ?>
                          <tr>
                            <td><?= $pr['codigo'] ?></td>
                            <td><?= $pr['nombre'] ?></td>
                            <td><?= $pr['tipo'] ?></td>
                            <td><?= $pr['estado_prueba'] ?></td>
                            <td><a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&delete_prueba=<?= $pr['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta prueba?')">Eliminar</a></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>

                      <form action="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" method="POST" class="border p-2 mb-4">
                        <input type="hidden" name="action_prueba" value="create">
                        <input type="hidden" name="id_tarea" value="<?= $t['id'] ?>">
                        <div class="form-row">
                          <div class="form-group col-md-6"><label>Nombre *</label><input type="text" class="form-control" name="nombre" required></div>
                          <div class="form-group col-md-3"><label>Código</label><input type="text" class="form-control" name="codigo"></div>
                          <div class="form-group col-md-3"><label>Tipo *</label>
                            <select class="form-control" name="tipo" required>
                              <option value="Funcional">Funcional</option>
                              <option value="Técnica">Técnica</option>
                              <option value="Incursión">Incursión</option>
                            </select></div>
                        </div>
                        <div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion"></textarea></div>
                        <div class="form-row">
                          <div class="form-group col-md-4"><label>Categoría</label><input type="text" class="form-control" name="categoria"></div>
                          <div class="form-group col-md-4"><label>Subcategoría</label><input type="text" class="form-control" name="subcategoria"></div>
                          <div class="form-group col-md-4"><label>Estado</label>
                            <select class="form-control" name="estado_prueba">
                              <option value="Incompleta">Incompleta</option>
                              <option value="En desarrollo">En desarrollo</option>
                              <option value="Finalizada">Finalizada</option>
                            </select></div>
                        </div>
                        <input type="hidden" name="id_creador" value="<?= $t['id_responsable'] ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Agregar prueba</button>
                      </form>

                      <h6>Resultados registrados (a partir de las pruebas)</h6>
                      <table class="table table-sm table-bordered">
                        <thead><tr><th>Prueba aplicada</th><th>Tipo</th><th>Descripción</th><th>Observaciones</th><th>Fecha</th><th>Por</th><th>Acción</th></tr></thead>
                        <tbody>
                          <?php foreach ($t['resultados'] as $res): ?>
                          <tr>
                            <td><?= $res['prueba_codigo'] ?> <?= $res['prueba_nombre'] ?></td>
                            <td><?= $res['tipo'] ?></td>
                            <td><?= $res['descripcion'] ?></td>
                            <td><?= $res['observaciones'] ?></td>
                            <td><?= $res['fecha_observacion'] ?></td>
                            <td><?= $res['usuario_nombre'] ?></td>
                            <td><a href="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>&delete_resultado=<?= $res['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este resultado?')">Eliminar</a></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>

                      <form action="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" method="POST" class="border p-2">
                        <input type="hidden" name="action_resultado" value="create">
                        <div class="form-row">
                          <div class="form-group col-md-6"><label>Prueba aplicada *</label>
                            <select class="form-control" name="id_prueba" required>
                              <?php foreach ($t['pruebas'] as $pr): ?>
                                  <option value="<?= $pr['id'] ?>"><?= $pr['codigo'] ?> - <?= $pr['nombre'] ?></option>
                              <?php endforeach; ?>
                            </select></div>
                          <div class="form-group col-md-3"><label>Tipo</label>
                            <select class="form-control" name="tipo">
                              <option value="Resultado">Resultado</option>
                              <option value="Observación">Observación</option>
                              <option value="Hallazgo">Hallazgo</option>
                              <option value="Seguimiento">Seguimiento</option>
                            </select></div>
                          <div class="form-group col-md-3"><label>Fecha</label><input type="date" class="form-control" name="fecha_observacion"></div>
                        </div>
                        <div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion"></textarea></div>
                        <div class="form-group"><label>Observaciones</label><textarea class="form-control" name="observaciones"></textarea></div>
                        <div class="form-group"><label>Realizado por</label>
                          <select class="form-control" name="id_usuario">
                              <?php foreach ($usuarios as $u): ?>
                                  <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
                              <?php endforeach; ?>
                          </select></div>
                        <button type="submit" class="btn btn-success btn-sm">Registrar resultado</button>
                      </form>

                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ===== Modal CREAR TAREA ===== -->
    <div class="modal fade" id="modalCrearTarea" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="index.php?entity=auditoria&action=tareas&id=<?= $plan['id'] ?>" method="POST">
            <input type="hidden" name="action_tarea" value="create">
            <div class="modal-header"><h5 class="modal-title">Crear Tarea</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
              <div class="form-group"><label>Código</label><input type="text" class="form-control" name="codigo"></div>
              <div class="form-group"><label>Nombre *</label><input type="text" class="form-control" name="nombre" required></div>
              <div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion"></textarea></div>
              <div class="form-group"><label>Subtarea de (opcional)</label>
                  <select class="form-control" name="id_padre">
                      <option value="">— Tarea principal —</option>
                      <?php foreach ($tareas as $tp): ?>
                          <option value="<?= $tp['id'] ?>"><?= $tp['codigo'] ?> - <?= $tp['nombre'] ?></option>
                      <?php endforeach; ?>
                  </select></div>
              <div class="form-group"><label>Responsable *</label>
                  <select class="form-control" name="id_responsable" required>
                      <?php foreach ($usuarios as $u): ?>
                          <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
                      <?php endforeach; ?>
                  </select></div>
              <div class="form-group"><label>Fecha Inicio *</label><input type="date" class="form-control" name="fecha_inicio" required></div>
              <div class="form-group"><label>Fecha Fin *</label><input type="date" class="form-control" name="fecha_fin" required></div>
              <div class="form-group"><label>Duración estimada (días) *</label><input type="number" class="form-control" name="duracion_estimada" required></div>
              <div class="form-group"><label>Duración real (días)</label><input type="number" class="form-control" name="duracion_real" value="0"></div>
              <div class="form-group"><label>Prioridad *</label>
                  <select class="form-control" name="prioridad" required>
                      <option value="Alta">Alta</option>
                      <option value="Media">Media</option>
                      <option value="Baja">Baja</option>
                  </select></div>
              <div class="form-group"><label>Costo estimado</label><input type="number" step="0.01" class="form-control" name="costo_estimado"></div>
              <div class="form-group"><label>% Progreso</label><input type="number" class="form-control" name="progreso" value="0"></div>
              <div class="form-group"><label>Estado *</label>
                  <select class="form-control" name="estado_tarea" required>
                      <?php foreach (array_keys($colores) as $est): ?>
                          <option value="<?= $est ?>"><?= $est ?></option>
                      <?php endforeach; ?>
                  </select></div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="index.php?entity=auditoria&action=index" class="btn btn-secondary">Volver a Auditorías</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
