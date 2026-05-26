<?php
class DependenciaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getIdDependenciaPorUsuario($id_usuario) {
        $stmt = $this->db->prepare("SELECT id_dependencia FROM dependencias WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn();
    }

    public function getAlumnosActivosCount($id_dependencia) {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT a.id_alumno) 
            FROM asignaciones a 
            JOIN programas p ON a.id_programa = p.id_programa 
            WHERE p.id_dependencia = ? AND a.estado_asignacion = 'Activo'
        ");
        $stmt->execute([$id_dependencia]);
        return $stmt->fetchColumn();
    }

    public function getProgramasAprobadosCount($id_dependencia) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM programas 
            WHERE id_dependencia = ? AND estado_aprobacion = 'Aprobado'
        ");
        $stmt->execute([$id_dependencia]);
        return $stmt->fetchColumn();
    }

    public function getEvaluacionesPendientesCount($id_dependencia) {
        $stmt = $this->db->prepare("
            SELECT COUNT(a.id_asignacion) 
            FROM asignaciones a 
            JOIN programas p ON a.id_programa = p.id_programa 
            LEFT JOIN evaluaciones e ON a.id_alumno = e.id_alumno AND a.id_programa = e.id_programa 
            WHERE p.id_dependencia = ? AND a.estado_asignacion = 'Activo' AND e.id_evaluacion IS NULL
        ");
        $stmt->execute([$id_dependencia]);
        return $stmt->fetchColumn();
    }
}
