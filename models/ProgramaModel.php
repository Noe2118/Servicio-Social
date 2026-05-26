<?php

class ProgramaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los programas aprobados con cupos disponibles.
     * JOIN con dependencias para mostrar el nombre de la organización.
     * Filtros opcionales por modalidad y dependencia.
     */
    public function obtenerProgramasDisponibles(?string $modalidad = null, ?int $idDependencia = null): array {
        $sql = "SELECT p.*, d.nombre_organizacion, d.direccion AS direccion_dependencia,
                       (p.cupos_totales - p.cupos_ocupados) AS cupos_disponibles
                FROM programas p
                INNER JOIN dependencias d ON p.id_dependencia = d.id_dependencia
                WHERE p.estado_aprobacion = 'Aprobado'
                  AND (p.cupos_totales - p.cupos_ocupados) > 0";
        
        $params = [];

        if ($modalidad !== null && $modalidad !== '') {
            $sql .= " AND p.modalidad = :modalidad";
            $params['modalidad'] = $modalidad;
        }

        if ($idDependencia !== null && $idDependencia > 0) {
            $sql .= " AND p.id_dependencia = :id_dependencia";
            $params['id_dependencia'] = $idDependencia;
        }

        $sql .= " ORDER BY p.nombre_programa ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un programa específico por su ID con datos de la dependencia.
     */
    public function obtenerPorId(int $idPrograma): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, d.nombre_organizacion, d.direccion AS direccion_dependencia,
                    (p.cupos_totales - p.cupos_ocupados) AS cupos_disponibles
             FROM programas p
             INNER JOIN dependencias d ON p.id_dependencia = d.id_dependencia
             WHERE p.id_programa = :id_programa"
        );
        $stmt->execute(['id_programa' => $idPrograma]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Incrementar cupos_ocupados en 1 al inscribirse un alumno.
     */
    public function incrementarCuposOcupados(int $idPrograma): bool {
        $stmt = $this->db->prepare(
            "UPDATE programas 
             SET cupos_ocupados = cupos_ocupados + 1 
             WHERE id_programa = :id_programa 
               AND cupos_ocupados < cupos_totales"
        );
        $stmt->execute(['id_programa' => $idPrograma]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtener la lista de dependencias que tienen al menos un programa aprobado.
     */
    public function obtenerDependenciasConProgramas(): array {
        $stmt = $this->db->query(
            "SELECT DISTINCT d.id_dependencia, d.nombre_organizacion
             FROM dependencias d
             INNER JOIN programas p ON d.id_dependencia = p.id_dependencia
             WHERE p.estado_aprobacion = 'Aprobado'
             ORDER BY d.nombre_organizacion ASC"
        );
        return $stmt->fetchAll();
    }
}
