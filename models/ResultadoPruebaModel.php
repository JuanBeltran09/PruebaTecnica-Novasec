<?php
// models/ResultadoPruebaModel.php
require_once 'config.php';

class ResultadoPruebaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByPrueba($id_prueba) {
        $stmt = $this->pdo->prepare("
            SELECT res.*, u.nombre as usuario_nombre
            FROM ResultadoPrueba res
            LEFT JOIN Usuario u ON res.id_usuario = u.id
            WHERE res.id_prueba = ?
        ");
        $stmt->execute([$id_prueba]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Todos los resultados de las pruebas de una tarea (incluye nombre de la prueba aplicada)
    public function getByTarea($id_tarea) {
        $stmt = $this->pdo->prepare("
            SELECT res.*, u.nombre as usuario_nombre, pr.nombre as prueba_nombre, pr.codigo as prueba_codigo
            FROM ResultadoPrueba res
            INNER JOIN PruebaTarea pr ON res.id_prueba = pr.id
            LEFT JOIN Usuario u ON res.id_usuario = u.id
            WHERE pr.id_tarea = ?
            ORDER BY res.fecha_observacion DESC
        ");
        $stmt->execute([$id_tarea]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT res.*, u.nombre as usuario_nombre
            FROM ResultadoPrueba res
            LEFT JOIN Usuario u ON res.id_usuario = u.id
            WHERE res.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($id_prueba, $data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO ResultadoPrueba
                (id_prueba, descripcion, observaciones, fecha_observacion, tipo, id_usuario)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $id_prueba,
            $data['descripcion'],
            $data['observaciones'],
            $data['fecha_observacion'],
            $data['tipo'],
            $data['id_usuario']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE ResultadoPrueba SET
                descripcion = ?, observaciones = ?, fecha_observacion = ?, tipo = ?, id_usuario = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['descripcion'],
            $data['observaciones'],
            $data['fecha_observacion'],
            $data['tipo'],
            $data['id_usuario'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM ResultadoPrueba WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
