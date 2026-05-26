<?php

class AlumnoModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener datos del alumno por su id_usuario (sesión)
     */
    public function obtenerPorUsuarioId(int $idUsuario): ?array {
        $stmt = $this->db->prepare(
            'SELECT * FROM alumnos WHERE id_usuario = :id_usuario'
        );
        $stmt->execute(['id_usuario' => $idUsuario]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener datos del alumno por su id_alumno
     */
    public function obtenerPorId(int $idAlumno): ?array {
        $stmt = $this->db->prepare(
            'SELECT * FROM alumnos WHERE id_alumno = :id_alumno'
        );
        $stmt->execute(['id_alumno' => $idAlumno]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Actualizar las horas completadas del alumno
     */
    public function actualizarHoras(int $idAlumno, int $horas): bool {
        $stmt = $this->db->prepare(
            'UPDATE alumnos SET horas_completadas = :horas WHERE id_alumno = :id_alumno'
        );
        return $stmt->execute([
            'horas' => $horas,
            'id_alumno' => $idAlumno
        ]);
    }
}
