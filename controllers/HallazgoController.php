<?php
// controllers/HallazgoController.php
require_once 'models/HallazgoModel.php';
require_once 'models/ProcesoModel.php';
require_once 'models/EstadoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/PlanAccionModel.php'; // H-6778: Modelo de planes de acción

class HallazgoController {
    private $model;
    private $procesoModel;
    private $estadoModel;
    private $usuarioModel;
    private $planAccionModel; // H-6778: Modelo de planes de acción

    public function __construct($pdo) {
        $this->model = new HallazgoModel($pdo);
        $this->procesoModel = new ProcesoModel($pdo);
        $this->estadoModel = new EstadoModel($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
        $this->planAccionModel = new PlanAccionModel($pdo); // H-6778
    }

    public function index() {
        $hallazgos = $this->model->getAll();
        $estados = $this->estadoModel->getAll(); // H-5995: Se pasan los estados a la vista para los selectores
        require 'views/hallazgo/list.php';
    }

    public function show($id) {
        $hallazgo = $this->model->getById($id);
        require 'views/hallazgo/show.php';
    }

    public function create() {
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        require 'views/hallazgo/create.php';
    }

    public function insert($data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];

        $this->model->insert($titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    public function edit($id) {
        $hallazgo = $this->model->getById($id);
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $selectedProcesos = $this->model->getProcesos($hallazgo['id']);
        $selectedProcesoIds = array_column($selectedProcesos, 'id');
        require 'views/hallazgo/edit.php';
    }

    public function update($id, $data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];

        $this->model->update($id, $titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?action=index');
    }

    // H-6778: Método para mostrar los planes de acción de un hallazgo
    public function planesAccion($id_hallazgo) {
        $hallazgo = $this->model->getById($id_hallazgo);
        $planesAccion = $this->planAccionModel->getByRegistro($id_hallazgo, 'HALLAZGO');
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $entity = 'hallazgo'; // Variable para los componentes compartidos
        require 'views/hallazgo/planes_accion.php';
    }

    // H-6778: Método para insertar un plan de acción vinculado a un hallazgo
    public function insertPlanAccion($id_hallazgo, $data) {
        $id_plan_accion = $this->planAccionModel->insert($data);
        if ($id_plan_accion) {
            $this->planAccionModel->linkToRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        }
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // H-6778: Método para actualizar un plan de acción de un hallazgo
    public function updatePlanAccion($id_hallazgo, $id_plan_accion, $data) {
        $this->planAccionModel->update($id_plan_accion, $data);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // H-6778: Método para eliminar un plan de acción de un hallazgo
    public function deletePlanAccion($id_hallazgo, $id_plan_accion) {
        $this->planAccionModel->unlinkFromRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        $this->planAccionModel->delete($id_plan_accion);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // H-5995: Endpoint AJAX para actualizar el estado de un hallazgo sin recargar la página
    public function updateEstadoAjax($id) {
        header('Content-Type: application/json');

        // Leer el cuerpo de la petición JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $id_estado = $input['id_estado'] ?? null;

        if (!$id_estado) {
            echo json_encode(['success' => false, 'message' => 'El id_estado es requerido.']);
            return;
        }

        $result = $this->model->updateEstado($id, $id_estado);

        if ($result) {
            // Obtener el nombre del nuevo estado para mostrarlo en la alerta
            $estado = $this->estadoModel->getById($id_estado);
            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente.',
                'estado_nombre' => $estado['nombre']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado.']);
        }
    }
}
?>