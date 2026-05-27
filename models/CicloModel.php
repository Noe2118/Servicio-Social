<?php

class CicloModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodosLosCiclos(): array {
        $stmt = $this->db->query("SELECT * FROM ciclos ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll();
    }

    public function obtenerCiclosActuales(): array {
        $stmt = $this->db->query("SELECT * FROM ciclos WHERE CURRENT_DATE <= fecha_fin ORDER BY fecha_inicio ASC");
        return $stmt->fetchAll();
    }

    public function crearCiclo(string $nombre, string $inicio, string $fin): bool {
        $stmt = $this->db->prepare("INSERT INTO ciclos (nombre_ciclo, fecha_inicio, fecha_fin) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $inicio, $fin]);
    }

    public function eliminarCiclo(int $id_ciclo): bool {
        $stmt = $this->db->prepare("DELETE FROM ciclos WHERE id_ciclo = ?");
        return $stmt->execute([$id_ciclo]);
    }

    public function hayCicloActivo(): bool {
        $stmt = $this->db->query("SELECT COUNT(*) FROM ciclos WHERE CURRENT_DATE BETWEEN fecha_inicio AND fecha_fin");
        return (int) $stmt->fetchColumn() > 0;
    }
}
