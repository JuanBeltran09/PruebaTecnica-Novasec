<?php
// models/PlanAuditoriaModel.php
require_once 'config.php';

class PlanAuditoriaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT p.*, e.nombre as estado_nombre, r.nombre as responsable_nombre, c.nombre as creador_nombre
            FROM PlanAuditoria p
            LEFT JOIN Estado e ON p.id_estado = e.id
            LEFT JOIN Usuario r ON p.id_responsable = r.id
            LEFT JOIN Usuario c ON p.id_usuario_creador = c.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, e.nombre as estado_nombre, r.nombre as responsable_nombre, c.nombre as creador_nombre
            FROM PlanAuditoria p
            LEFT JOIN Estado e ON p.id_estado = e.id
            LEFT JOIN Usuario r ON p.id_responsable = r.id
            LEFT JOIN Usuario c ON p.id_usuario_creador = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO PlanAuditoria
                (codigo, nombre, alcance, justificacion, tipo_plan, costo_estimado, fecha_inicio, fecha_fin, id_estado, id_responsable, id_usuario_creador)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['alcance'],
            $data['justificacion'],
            $data['tipo_plan'],
            $data['costo_estimado'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['id_estado'],
            $data['id_responsable'],
            $data['id_usuario_creador']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE PlanAuditoria SET
                codigo = ?, nombre = ?, alcance = ?, justificacion = ?, tipo_plan = ?,
                costo_estimado = ?, fecha_inicio = ?, fecha_fin = ?, id_estado = ?, id_responsable = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['alcance'],
            $data['justificacion'],
            $data['tipo_plan'],
            $data['costo_estimado'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['id_estado'],
            $data['id_responsable'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM PlanAuditoria WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
