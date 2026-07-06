<?php
// controllers/AuditoriaController.php
require_once 'models/PlanAuditoriaModel.php';
require_once 'models/TareaAuditoriaModel.php';
require_once 'models/PruebaTareaModel.php';
require_once 'models/ResultadoPruebaModel.php';
require_once 'models/EstadoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/HallazgoModel.php'; // Agregado para escalamiento

class AuditoriaController {
    private $model;
    private $tareaModel;
    private $pruebaModel;
    private $resultadoModel;
    private $estadoModel;
    private $usuarioModel;
    private $hallazgoModel; // Modelo para escalamiento

    public function __construct($pdo) {
        $this->model = new PlanAuditoriaModel($pdo);
        $this->tareaModel = new TareaAuditoriaModel($pdo);
        $this->pruebaModel = new PruebaTareaModel($pdo);
        $this->resultadoModel = new ResultadoPruebaModel($pdo);
        $this->estadoModel = new EstadoModel($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
        $this->hallazgoModel = new HallazgoModel($pdo);
    }

    // ===================== PLANES =====================
    public function index() {
        $planes = $this->model->getAll();
        require 'views/auditoria/list.php';
    }

    public function show($id) {
        $plan = $this->model->getById($id);
        require 'views/auditoria/show.php';
    }

    public function create() {
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        require 'views/auditoria/create.php';
    }

    public function insert($data) {
        $data['id_usuario_creador'] = $data['id_responsable'];
        $this->model->insert($data);
        header('Location: index.php?entity=auditoria&action=index');
    }

    public function edit($id) {
        $plan = $this->model->getById($id);
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        require 'views/auditoria/edit.php';
    }

    public function update($id, $data) {
        $this->model->update($id, $data);
        header('Location: index.php?entity=auditoria&action=index');
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?entity=auditoria&action=index');
    }

    // ===================== TAREAS DEL PLAN (listado jerarquico / Gantt) =====================
    public function tareas($id_plan) {
        $plan = $this->model->getById($id_plan);
        $tareas = $this->tareaModel->getByPlan($id_plan);
        // Adjunta a cada tarea sus pruebas y los resultados (para los modales)
        foreach ($tareas as &$t) {
            $t['pruebas'] = $this->pruebaModel->getByTarea($t['id']);
            $t['resultados'] = $this->resultadoModel->getByTarea($t['id']);
        }
        unset($t);
        $usuarios = $this->usuarioModel->getAll();
        require 'views/auditoria/tareas.php';
    }

    public function insertTarea($id_plan, $data) {
        $this->tareaModel->insert($id_plan, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function updateTarea($id_plan, $id_tarea, $data) {
        $this->tareaModel->update($id_tarea, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function deleteTarea($id_plan, $id_tarea) {
        $this->tareaModel->delete($id_tarea);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // Convierte la tarea en subtarea de la que esta justo encima
    public function indentarTarea($id_plan, $id_tarea) {
        $tarea = $this->tareaModel->getById($id_tarea);
        $anterior = $this->tareaModel->getAnterior($id_plan, $tarea['orden']);
        if ($anterior) {
            $this->tareaModel->setPadre($id_tarea, $anterior['id']);
        }
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // Promueve la tarea un nivel (la saca de su padre)
    public function desindentarTarea($id_plan, $id_tarea) {
        $tarea = $this->tareaModel->getById($id_tarea);
        $nuevoPadre = null;
        if (!empty($tarea['id_padre'])) {
            $padre = $this->tareaModel->getById($tarea['id_padre']);
            $nuevoPadre = $padre ? $padre['id_padre'] : null;
        }
        $this->tareaModel->setPadre($id_tarea, $nuevoPadre);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // Intercambia el orden con la tarea adyacente
    public function moverTarea($id_plan, $id_tarea, $direccion) {
        $tarea = $this->tareaModel->getById($id_tarea);
        if ($direccion === 'arriba') {
            $vecina = $this->tareaModel->getAnterior($id_plan, $tarea['orden']);
        } else {
            $vecina = $this->tareaModel->getSiguiente($id_plan, $tarea['orden']);
        }
        if ($vecina) {
            $this->tareaModel->setOrden($id_tarea, $vecina['orden']);
            $this->tareaModel->setOrden($vecina['id'], $tarea['orden']);
        }
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // ===================== PRUEBAS DE LA TAREA (desde el modal) =====================
    public function insertPrueba($id_plan, $id_tarea, $data) {
        $this->pruebaModel->insert($id_tarea, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function updatePrueba($id_plan, $id_prueba, $data) {
        $this->pruebaModel->update($id_prueba, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function deletePrueba($id_plan, $id_prueba) {
        $this->pruebaModel->delete($id_prueba);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // ===================== RESULTADOS (a partir de las pruebas, desde el modal) =====================
    public function insertResultado($id_plan, $id_prueba, $data) {
        $this->resultadoModel->insert($id_prueba, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function updateResultado($id_plan, $id_resultado, $data) {
        $this->resultadoModel->update($id_resultado, $data);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    public function deleteResultado($id_plan, $id_resultado) {
        $this->resultadoModel->delete($id_resultado);
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
    }

    // Escalamiento Automatizado de Hallazgos
    public function escalarAHallazgo($id_plan, $id_resultado) {
        $resultado = $this->resultadoModel->getById($id_resultado);
        
        if ($resultado && $resultado['tipo'] === 'Hallazgo') {
            // Se usa el título de la prueba y la descripción del resultado
            $titulo = "Escalado desde Auditoría: Resultado " . $resultado['id'];
            $descripcion = $resultado['descripcion'] . "\n\nObservaciones: " . $resultado['observaciones'];
            $id_estado = 1; // Por defecto estado inicial (ej. 'Abierto' o el que corresponda al ID 1)
            $id_usuario = $resultado['id_usuario']; // Mismo usuario que reportó el hallazgo

            $nuevo_hallazgo_id = $this->hallazgoModel->insertFromAuditoria($titulo, $descripcion, $id_estado, $id_usuario);

            if ($nuevo_hallazgo_id) {
                // Redirigir al nuevo hallazgo creado para que puedan verlo y completarlo
                header('Location: index.php?entity=hallazgo&action=show&id=' . $nuevo_hallazgo_id);
                exit;
            }
        }
        // Si falla o no es hallazgo, vuelve a la tarea
        header('Location: index.php?entity=auditoria&action=tareas&id=' . $id_plan);
        exit;
    }
}
?>
