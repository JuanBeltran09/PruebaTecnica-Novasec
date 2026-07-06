<?php
// models/PruebaTareaModel.php
require_once 'config.php';

class PruebaTareaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByTarea($id_tarea) {
        $stmt = $this->pdo->prepare("
            SELECT pr.*, c.nombre as creador_nombre
            FROM PruebaTarea pr
            LEFT JOIN Usuario c ON pr.id_creador = c.id
            WHERE pr.id_tarea = ?
        ");
        $stmt->execute([$id_tarea]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT pr.*, c.nombre as creador_nombre
            FROM PruebaTarea pr
            LEFT JOIN Usuario c ON pr.id_creador = c.id
            WHERE pr.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($id_tarea, $data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO PruebaTarea
                (id_tarea, codigo, nombre, tipo, descripcion, categoria, subcategoria, estado_prueba, id_creador)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $id_tarea,
            $data['codigo'],
            $data['nombre'],
            $data['tipo'],
            $data['descripcion'],
            $data['categoria'],
            $data['subcategoria'],
            $data['estado_prueba'],
            $data['id_creador']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE PruebaTarea SET
                codigo = ?, nombre = ?, tipo = ?, descripcion = ?, categoria = ?, subcategoria = ?, estado_prueba = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['tipo'],
            $data['descripcion'],
            $data['categoria'],
            $data['subcategoria'],
            $data['estado_prueba'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM PruebaTarea WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
