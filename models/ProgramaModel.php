<?php

class ProgramaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los programas de una dependencia específica.
     */
    public function getProgramasPorDependencia(int $id_dependencia): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM programas WHERE id_dependencia = :id ORDER BY nombre_programa ASC"
        );
        $stmt->execute(['id' => $id_dependencia]);
        return $stmt->fetchAll();
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

    public function contarPendientes(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM programas WHERE estado_aprobacion = 'En Revisión por DGTyV'");
        return (int) $stmt->fetchColumn();
    }

    public function obtenerPendientesConDependencia(): array {
        $stmt = $this->db->query(
            "SELECT p.*, d.nombre_organizacion 
             FROM programas p 
             JOIN dependencias d ON p.id_dependencia = d.id_dependencia 
             WHERE p.estado_aprobacion = 'En Revisión por DGTyV' 
             ORDER BY p.fecha_envio DESC"
        );
        return $stmt->fetchAll();
    }

    public function obtenerProgramaPorId(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, d.nombre_organizacion 
             FROM programas p 
             JOIN dependencias d ON p.id_dependencia = d.id_dependencia 
             WHERE p.id_programa = :id"
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function actualizarEstado(int $id, string $estado): bool {
        $stmt = $this->db->prepare(
            "UPDATE programas SET estado_aprobacion = :estado WHERE id_programa = :id"
        );
        $stmt->execute(['estado' => $estado, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerTodasLasDependencias(): array {
        $stmt = $this->db->query("SELECT id_dependencia, nombre_organizacion FROM dependencias ORDER BY nombre_organizacion ASC");
        return $stmt->fetchAll();
    }

    public function crearPrograma(array $datos, array $horarios = [], array $ciclos = []): int|bool {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO programas (
                        id_dependencia, folio_programa, nombre_programa, modalidad, 
                        descripcion, perfiles_requeridos, cupos_totales, cupos_ocupados, 
                        ubicacion, responsable_nombre, responsable_contacto, 
                        estado_aprobacion, fecha_envio
                    ) VALUES (
                        :id_dependencia, :folio, :nombre, :modalidad, 
                        :descripcion, :perfiles, :cupos, 0, 
                        :ubicacion, :responsable_nombre, :responsable_contacto, 
                        'Aprobado', CURRENT_DATE
                    )";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_dependencia' => $datos['id_dependencia'],
                'folio' => $datos['folio_programa'],
                'nombre' => $datos['nombre_programa'],
                'modalidad' => $datos['modalidad'],
                'descripcion' => $datos['descripcion'],
                'perfiles' => $datos['perfiles_requeridos'],
                'cupos' => $datos['cupos_totales'],
                'ubicacion' => $datos['ubicacion'],
                'responsable_nombre' => $datos['responsable_nombre'],
                'responsable_contacto' => $datos['responsable_contacto']
            ]);

            $id_programa = $this->db->lastInsertId();

            if (!empty($horarios)) {
                $sqlHorario = "INSERT INTO horarios_programas (id_programa, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
                $stmtHorario = $this->db->prepare($sqlHorario);
                foreach ($horarios as $h) {
                    $stmtHorario->execute([$id_programa, $h['dia'], $h['inicio'], $h['fin']]);
                }
            }

            if (!empty($ciclos)) {
                $sqlCiclo = "INSERT INTO ciclos_programas (id_programa, id_ciclo) VALUES (?, ?)";
                $stmtCiclo = $this->db->prepare($sqlCiclo);
                foreach ($ciclos as $id_ciclo) {
                    $stmtCiclo->execute([$id_programa, $id_ciclo]);
                }
            }

            $this->db->commit();
            return (int) $id_programa;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function obtenerProgramasRecientes(int $limit = 5): array {
        $stmt = $this->db->prepare(
            "SELECT p.id_programa, p.nombre_programa, p.estado_aprobacion, p.fecha_envio, d.nombre_organizacion 
             FROM programas p 
             JOIN dependencias d ON p.id_dependencia = d.id_dependencia 
             ORDER BY p.fecha_envio DESC 
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerAlumnosPorPrograma(int $id_programa): array {
        $stmt = $this->db->prepare("SELECT id_alumno FROM asignaciones WHERE id_programa = ? AND estado_asignacion = 'Activo'");
        $stmt->execute([$id_programa]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function notificarYDesvincularAlumnos(array $ids_alumnos, string $mensaje): bool {
        if (empty($ids_alumnos)) return true;
        $in = str_repeat('?,', count($ids_alumnos) - 1) . '?';
        $sql = "UPDATE alumnos SET estado_servicio = 'Sin Iniciar', notificacion = ? WHERE id_alumno IN ($in)";
        $params = array_merge([$mensaje], $ids_alumnos);
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function eliminarPrograma(int $id_programa): bool {
        $stmt = $this->db->prepare("DELETE FROM programas WHERE id_programa = ?");
        return $stmt->execute([$id_programa]);
    }
    
    public function actualizarPrograma(int $id_programa, array $datos, array $horarios = [], array $ciclos = []): bool {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE programas SET 
                    nombre_programa = :nombre, modalidad = :modalidad, 
                    descripcion = :descripcion, perfiles_requeridos = :perfiles, 
                    cupos_totales = :cupos, 
                    ubicacion = :ubicacion, responsable_nombre = :responsable_nombre, 
                    responsable_contacto = :responsable_contacto 
                    WHERE id_programa = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id' => $id_programa,
                'nombre' => $datos['nombre_programa'],
                'modalidad' => $datos['modalidad'],
                'descripcion' => $datos['descripcion'],
                'perfiles' => $datos['perfiles_requeridos'],
                'cupos' => $datos['cupos_totales'],
                'ubicacion' => $datos['ubicacion'],
                'responsable_nombre' => $datos['responsable_nombre'],
                'responsable_contacto' => $datos['responsable_contacto']
            ]);

            // Limpiar relaciones anteriores
            $this->db->prepare("DELETE FROM horarios_programas WHERE id_programa = ?")->execute([$id_programa]);
            $this->db->prepare("DELETE FROM ciclos_programas WHERE id_programa = ?")->execute([$id_programa]);

            // Insertar nuevas
            if (!empty($horarios)) {
                $sqlHorario = "INSERT INTO horarios_programas (id_programa, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
                $stmtHorario = $this->db->prepare($sqlHorario);
                foreach ($horarios as $h) {
                    $stmtHorario->execute([$id_programa, $h['dia'], $h['inicio'], $h['fin']]);
                }
            }

            if (!empty($ciclos)) {
                $sqlCiclo = "INSERT INTO ciclos_programas (id_programa, id_ciclo) VALUES (?, ?)";
                $stmtCiclo = $this->db->prepare($sqlCiclo);
                foreach ($ciclos as $id_ciclo) {
                    $stmtCiclo->execute([$id_programa, $id_ciclo]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function obtenerCiclosPorPrograma(int $id_programa): array {
        $stmt = $this->db->prepare("SELECT id_ciclo FROM ciclos_programas WHERE id_programa = ?");
        $stmt->execute([$id_programa]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obtenerHorariosPorPrograma(int $id_programa): array {
        $stmt = $this->db->prepare("SELECT dia_semana, hora_inicio, hora_fin FROM horarios_programas WHERE id_programa = ?");
        $stmt->execute([$id_programa]);
        return $stmt->fetchAll();
    }
}
