<?php
class EvaluacionModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAlumnosParaEvaluacion($id_dependencia) {
        $stmt = $this->db->prepare("
            SELECT al.*, p.nombre_programa, a.id_programa 
            FROM alumnos al 
            JOIN asignaciones a ON al.id_alumno = a.id_alumno 
            JOIN programas p ON a.id_programa = p.id_programa 
            WHERE p.id_dependencia = ? AND a.estado_asignacion = 'Activo'
        ");
        $stmt->execute([$id_dependencia]);
        return $stmt->fetchAll();
    }

    public function guardarEvaluacion($id_alumno, $id_programa, $nivel_desempeno, $comentarios, $fecha) {
        $stmt = $this->db->prepare("
            INSERT INTO evaluaciones (id_alumno, id_programa, tipo_evaluacion, nivel_desempeno, comentarios_supervisor, fecha_evaluacion) 
            VALUES (?, ?, 'Cualitativa', ?, ?, ?)
        ");
        return $stmt->execute([$id_alumno, $id_programa, $nivel_desempeno, $comentarios, $fecha]);
    }
}
