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

    public function crearAlumno(int $id_usuario, string $no_control, string $nombre, string $carrera, float $porcentaje): bool {
        $stmt = $this->db->prepare(
            'INSERT INTO alumnos (id_usuario, no_control, nombre_completo, carrera, porcentaje_creditos, periodo_actual) 
             VALUES (:id_usuario, :no_control, :nombre, :carrera, :porcentaje, :periodo)'
        );
        return $stmt->execute([
            'id_usuario' => $id_usuario,
            'no_control' => $no_control,
            'nombre' => $nombre,
            'carrera' => $carrera,
            'porcentaje' => $porcentaje,
            'periodo' => 'Ene-Jun ' . date('Y')
        ]);
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

    public function contarParaLiberacion(): int {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM alumnos WHERE horas_completadas >= 480 AND estado_servicio = 'En curso'"
        );
        return (int) $stmt->fetchColumn();
    }

    public function obtenerAlumnosParaLiberacion(): array {
        $stmt = $this->db->query(
            "SELECT a.*, asig.id_programa, asig.estado_reportes, asig.estado_reporte_final, asig.motivo_rechazo_final, p.nombre_programa, d.nombre_organizacion 
             FROM alumnos a 
             LEFT JOIN asignaciones asig ON a.id_alumno = asig.id_alumno AND asig.estado_asignacion IN ('Activo', 'Concluido')
             LEFT JOIN programas p ON asig.id_programa = p.id_programa 
             LEFT JOIN dependencias dep ON p.id_dependencia = dep.id_dependencia
             LEFT JOIN dependencias d ON p.id_dependencia = d.id_dependencia
             WHERE asig.estado_reportes = 'Aprobado' AND a.estado_servicio = 'En curso'
             ORDER BY a.nombre_completo ASC"
        );
        return $stmt->fetchAll();
    }

    public function marcarLiberado(int $idAlumno): bool {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "UPDATE alumnos SET estado_servicio = 'Liberado' WHERE id_alumno = :id"
            );
            $stmt->execute(['id' => $idAlumno]);
            
            $stmt2 = $this->db->prepare(
                "UPDATE asignaciones SET estado_asignacion = 'Concluido' WHERE id_alumno = :id AND estado_asignacion = 'Activo'"
            );
            $stmt2->execute(['id' => $idAlumno]);
            
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
