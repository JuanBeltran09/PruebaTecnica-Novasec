<?php
// models/TareaAuditoriaModel.php
require_once 'config.php';

class TareaAuditoriaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByPlan($id_plan) {
        $stmt = $this->pdo->prepare("
            SELECT t.*, r.nombre as responsable_nombre
            FROM TareaAuditoria t
            LEFT JOIN Usuario r ON t.id_responsable = r.id
            WHERE t.id_plan = ?
            ORDER BY t.orden ASC, t.id ASC
        ");
        $stmt->execute([$id_plan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT t.*, r.nombre as responsable_nombre
            FROM TareaAuditoria t
            LEFT JOIN Usuario r ON t.id_responsable = r.id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMaxOrden($id_plan) {
        $stmt = $this->pdo->prepare("SELECT MAX(orden) as m FROM TareaAuditoria WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['m'] ?? 0;
    }

    public function insert($id_plan, $data) {
        $orden = $this->getMaxOrden($id_plan) + 1;
        $id_padre = !empty($data['id_padre']) ? $data['id_padre'] : null;
        $stmt = $this->pdo->prepare("
            INSERT INTO TareaAuditoria
                (id_plan, id_padre, orden, codigo, nombre, descripcion, id_responsable, fecha_inicio, fecha_fin, duracion_estimada, duracion_real, prioridad, costo_estimado, progreso, estado_tarea)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $id_plan,
            $id_padre,
            $orden,
            $data['codigo'],
            $data['nombre'],
            $data['descripcion'],
            $data['id_responsable'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['duracion_estimada'],
            $data['duracion_real'],
            $data['prioridad'],
            $data['costo_estimado'],
            $data['progreso'],
            $data['estado_tarea']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE TareaAuditoria SET
                codigo = ?, nombre = ?, descripcion = ?, id_responsable = ?, fecha_inicio = ?, fecha_fin = ?,
                duracion_estimada = ?, duracion_real = ?, prioridad = ?, costo_estimado = ?, progreso = ?, estado_tarea = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['descripcion'],
            $data['id_responsable'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['duracion_estimada'],
            $data['duracion_real'],
            $data['prioridad'],
            $data['costo_estimado'],
            $data['progreso'],
            $data['estado_tarea'],
            $id
        ]);
    }

    public function delete($id) {
        // Primero las subtareas que cuelgan de esta tarea
        $stmt = $this->pdo->prepare("DELETE FROM TareaAuditoria WHERE id_padre = ?");
        $stmt->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM TareaAuditoria WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setPadre($id, $id_padre) {
        $stmt = $this->pdo->prepare("UPDATE TareaAuditoria SET id_padre = ? WHERE id = ?");
        return $stmt->execute([$id_padre, $id]);
    }

    public function setOrden($id, $orden) {
        $stmt = $this->pdo->prepare("UPDATE TareaAuditoria SET orden = ? WHERE id = ?");
        return $stmt->execute([$orden, $id]);
    }

    // Tarea inmediatamente anterior dentro del mismo plan (segun orden)
    public function getAnterior($id_plan, $orden) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM TareaAuditoria
            WHERE id_plan = ? AND orden < ?
            ORDER BY orden DESC LIMIT 1
        ");
        $stmt->execute([$id_plan, $orden]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tarea inmediatamente posterior dentro del mismo plan (segun orden)
    public function getSiguiente($id_plan, $orden) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM TareaAuditoria
            WHERE id_plan = ? AND orden > ?
            ORDER BY orden ASC LIMIT 1
        ");
        $stmt->execute([$id_plan, $orden]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
