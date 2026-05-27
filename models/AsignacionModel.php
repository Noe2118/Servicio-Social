<?php

class AsignacionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener la asignación activa del alumno con datos del programa y dependencia.
     * Un alumno solo debería tener una asignación activa o pendiente a la vez.
     */
    public function obtenerAsignacionActiva(int $idAlumno): ?array {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.nombre_programa, p.descripcion, p.modalidad,
                    p.ubicacion, p.responsable_nombre, p.responsable_contacto,
                    d.nombre_organizacion, d.direccion AS direccion_dependencia
             FROM asignaciones a
             INNER JOIN programas p ON a.id_programa = p.id_programa
             INNER JOIN dependencias d ON p.id_dependencia = d.id_dependencia
             WHERE a.id_alumno = :id_alumno
               AND a.estado_asignacion IN ('Activo', 'Pendiente')
             ORDER BY a.fecha_asignacion DESC
             LIMIT 1"
        );
        $stmt->execute(['id_alumno' => $idAlumno]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Crear una nueva solicitud de asignación (estado = 'Pendiente').
     */
    public function crearSolicitud(int $idAlumno, int $idPrograma): int {
        $stmt = $this->db->prepare(
            "INSERT INTO asignaciones (id_alumno, id_programa, fecha_asignacion, estado_asignacion)
             VALUES (:id_alumno, :id_programa, CURRENT_DATE, 'Pendiente')"
        );
        $stmt->execute([
            'id_alumno' => $idAlumno,
            'id_programa' => $idPrograma
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Verificar si el alumno ya tiene una solicitud pendiente o activa
     * para evitar duplicados.
     */
    public function tieneAsignacionActiva(int $idAlumno): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM asignaciones
             WHERE id_alumno = :id_alumno
               AND estado_asignacion IN ('Activo', 'Pendiente')"
        );
        $stmt->execute(['id_alumno' => $idAlumno]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verificar si el alumno ya solicitó un programa específico.
     */
    public function yaHaSolicitadoPrograma(int $idAlumno, int $idPrograma): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM asignaciones
             WHERE id_alumno = :id_alumno
               AND id_programa = :id_programa
               AND estado_asignacion IN ('Activo', 'Pendiente')"
        );
        $stmt->execute([
            'id_alumno' => $idAlumno,
            'id_programa' => $idPrograma
        ]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Actualiza el estado de la asignación y guarda el motivo en caso de baja o rechazo.
     */
    public function actualizarEstadoAsignacion(int $idAlumno, int $idPrograma, string $estado, ?string $motivo = null): bool {
        $stmt = $this->db->prepare(
            "UPDATE asignaciones 
             SET estado_asignacion = :estado, motivo_baja = :motivo 
             WHERE id_alumno = :id_alumno AND id_programa = :id_programa"
        );
        $stmt->execute([
            'estado' => $estado,
            'motivo' => $motivo,
            'id_alumno' => $idAlumno,
            'id_programa' => $idPrograma
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtiene los alumnos con asignación activa en los programas de una dependencia
     */
    public function obtenerAlumnosActivosPorDependencia(int $idDependencia): array {
        $stmt = $this->db->prepare(
            "SELECT a.id_alumno, a.no_control, a.nombre_completo, a.carrera, a.estado_servicio,
                    asig.fecha_asignacion, asig.estado_asignacion, asig.motivo_baja,
                    p.id_programa, p.nombre_programa, p.folio_programa
             FROM asignaciones asig
             INNER JOIN alumnos a ON asig.id_alumno = a.id_alumno
             INNER JOIN programas p ON asig.id_programa = p.id_programa
             WHERE p.id_dependencia = :id_dependencia
               AND asig.estado_asignacion = 'Activo'
             ORDER BY p.nombre_programa ASC, a.nombre_completo ASC"
        );
        $stmt->execute(['id_dependencia' => $idDependencia]);
        return $stmt->fetchAll();
    }
}
