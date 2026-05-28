<?php
class BimestreModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene la configuración de los 3 bimestres para un programa.
     * Si no existen, devuelve un array vacío.
     */
    public function obtenerBimestresPorPrograma(int $idPrograma): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM bimestres_programa WHERE id_programa = :id_programa ORDER BY numero_bimestre ASC"
        );
        $stmt->execute(['id_programa' => $idPrograma]);
        $resultados = $stmt->fetchAll();
        
        // Formatear para que el índice sea el número de bimestre (1, 2, 3)
        $bimestres = [];
        foreach ($resultados as $row) {
            $bimestres[$row['numero_bimestre']] = $row;
        }
        return $bimestres;
    }

    /**
     * Guarda o actualiza la configuración de un bimestre específico para un programa.
     */
    public function guardarBimestre(int $idPrograma, int $numeroBimestre, ?string $fechaInicio, ?string $fechaFin, bool $habilitado): bool {
        // Verificar si ya existe el registro
        $stmt = $this->db->prepare(
            "SELECT id_bimestre_prog FROM bimestres_programa WHERE id_programa = :id_programa AND numero_bimestre = :numero_bimestre"
        );
        $stmt->execute(['id_programa' => $idPrograma, 'numero_bimestre' => $numeroBimestre]);
        $existe = $stmt->fetchColumn();

        if ($existe) {
            // Actualizar
            $stmtUpdate = $this->db->prepare(
                "UPDATE bimestres_programa 
                 SET fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, habilitado = :habilitado 
                 WHERE id_programa = :id_programa AND numero_bimestre = :numero_bimestre"
            );
            return $stmtUpdate->execute([
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'habilitado' => $habilitado ? 1 : 0,
                'id_programa' => $idPrograma,
                'numero_bimestre' => $numeroBimestre
            ]);
        } else {
            // Insertar
            $stmtInsert = $this->db->prepare(
                "INSERT INTO bimestres_programa (id_programa, numero_bimestre, fecha_inicio, fecha_fin, habilitado) 
                 VALUES (:id_programa, :numero_bimestre, :fecha_inicio, :fecha_fin, :habilitado)"
            );
            return $stmtInsert->execute([
                'id_programa' => $idPrograma,
                'numero_bimestre' => $numeroBimestre,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'habilitado' => $habilitado ? 1 : 0
            ]);
        }
    }
}
