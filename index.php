<?php
// index.php
require_once 'config.php';

$entity = $_GET['entity'] ?? 'hallazgo'; // Valor por defecto 'hallazgo'
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

if ($entity === 'incidente') {
    require_once 'controllers/IncidenteController.php';
    $controller = new IncidenteController($pdo);

    if ($action === 'planes_accion' && $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action_plan']) && $_POST['action_plan'] === 'create') {
                $controller->insertPlanAccion($id, $_POST);
            } elseif (isset($_POST['action_plan']) && $_POST['action_plan'] === 'edit') {
                $id_plan_accion = $_POST['id_plan_accion'];
                $controller->updatePlanAccion($id, $id_plan_accion, $_POST);
            }
        } elseif (isset($_GET['delete_plan']) && $_GET['delete_plan']) {
            $id_plan_accion = $_GET['delete_plan'];
            $controller->deletePlanAccion($id, $id_plan_accion);
        } else {
            $controller->planesAccion($id);
        }
    } else {
        // Acciones existentes para 'incidente'
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->insert($_POST);
        } elseif ($action === 'edit' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id, $_POST);
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } elseif ($action === 'show' && $id) {
            $controller->show($id);
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } else {
            $controller->index();
        }
    }
} elseif ($entity === 'auditoria') {
    require_once 'controllers/AuditoriaController.php';
    $controller = new AuditoriaController($pdo);

    if ($action === 'tareas' && $id) {
        // $id aquí es el id del PLAN. Toda la gestión de tareas, pruebas y
        // resultados se realiza sobre el listado jerárquico (Gantt) del plan.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action_tarea']) && $_POST['action_tarea'] === 'create') {
                $controller->insertTarea($id, $_POST);
            } elseif (isset($_POST['action_tarea']) && $_POST['action_tarea'] === 'edit') {
                $controller->updateTarea($id, $_POST['id_tarea'], $_POST);
            } elseif (isset($_POST['action_prueba']) && $_POST['action_prueba'] === 'create') {
                $controller->insertPrueba($id, $_POST['id_tarea'], $_POST);
            } elseif (isset($_POST['action_prueba']) && $_POST['action_prueba'] === 'edit') {
                $controller->updatePrueba($id, $_POST['id_prueba'], $_POST);
            } elseif (isset($_POST['action_resultado']) && $_POST['action_resultado'] === 'create') {
                $controller->insertResultado($id, $_POST['id_prueba'], $_POST);
            } elseif (isset($_POST['action_resultado']) && $_POST['action_resultado'] === 'edit') {
                $controller->updateResultado($id, $_POST['id_resultado'], $_POST);
            }
        } elseif (isset($_GET['delete_tarea']) && $_GET['delete_tarea']) {
            $controller->deleteTarea($id, $_GET['delete_tarea']);
        } elseif (isset($_GET['delete_prueba']) && $_GET['delete_prueba']) {
            $controller->deletePrueba($id, $_GET['delete_prueba']);
        } elseif (isset($_GET['delete_resultado']) && $_GET['delete_resultado']) {
            $controller->deleteResultado($id, $_GET['delete_resultado']);
        } elseif (isset($_GET['indentar']) && $_GET['indentar']) {
            $controller->indentarTarea($id, $_GET['indentar']);
        } elseif (isset($_GET['desindentar']) && $_GET['desindentar']) {
            $controller->desindentarTarea($id, $_GET['desindentar']);
        } elseif (isset($_GET['mover_arriba']) && $_GET['mover_arriba']) {
            $controller->moverTarea($id, $_GET['mover_arriba'], 'arriba');
        } elseif (isset($_GET['mover_abajo']) && $_GET['mover_abajo']) {
            $controller->moverTarea($id, $_GET['mover_abajo'], 'abajo');
        } else {
            $controller->tareas($id);
        }
    } else {
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->insert($_POST);
        } elseif ($action === 'edit' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id, $_POST);
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } elseif ($action === 'show' && $id) {
            $controller->show($id);
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } else {
            $controller->index();
        }
    }
} else {
    require_once 'controllers/HallazgoController.php';
    $controller = new HallazgoController($pdo);

    // H-6778: Rutas para planes de acción de hallazgos
    if ($action === 'planes_accion' && $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action_plan']) && $_POST['action_plan'] === 'create') {
                $controller->insertPlanAccion($id, $_POST);
            } elseif (isset($_POST['action_plan']) && $_POST['action_plan'] === 'edit') {
                $id_plan_accion = $_POST['id_plan_accion'];
                $controller->updatePlanAccion($id, $id_plan_accion, $_POST);
            }
        } elseif (isset($_GET['delete_plan']) && $_GET['delete_plan']) {
            $id_plan_accion = $_GET['delete_plan'];
            $controller->deletePlanAccion($id, $id_plan_accion);
        } else {
            $controller->planesAccion($id);
        }
    }
    // Acciones existentes para 'hallazgo'
    // H-5995: Ruta para actualización asíncrona del estado
    elseif ($action === 'update_estado' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->updateEstadoAjax($id);
    } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->insert($_POST);
    } elseif ($action === 'edit' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->update($id, $_POST);
    } elseif ($action === 'delete' && $id) {
        $controller->delete($id);
    } elseif ($action === 'show' && $id) {
        $controller->show($id);
    } elseif ($action === 'create') {
        $controller->create();
    } elseif ($action === 'edit' && $id) {
        $controller->edit($id);
    } else {
        $controller->index();
    }
}
?>