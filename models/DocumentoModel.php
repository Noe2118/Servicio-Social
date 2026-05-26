<?php

class DocumentoModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los documentos de un alumno.
     */
    public function obtenerPorAlumno(int $idAlumno): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM documentos
             WHERE id_alumno = :id_alumno
             ORDER BY fecha_subida DESC"
        );
        $stmt->execute(['id_alumno' => $idAlumno]);
        return $stmt->fetchAll();
    }

    /**
     * Insertar un nuevo documento en la base de datos.
     */
    public function subirDocumento(int $idAlumno, string $tipoDocumento, string $nombreOriginal, string $rutaServidor): int {
        $stmt = $this->db->prepare(
            "INSERT INTO documentos (id_alumno, tipo_documento, nombre_archivo_original, ruta_servidor, estado_validacion)
             VALUES (:id_alumno, :tipo_documento, :nombre_original, :ruta_servidor, 'Nuevo')"
        );
        $stmt->execute([
            'id_alumno' => $idAlumno,
            'tipo_documento' => $tipoDocumento,
            'nombre_original' => $nombreOriginal,
            'ruta_servidor' => $rutaServidor
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Obtener un documento específico por ID (para descargar o eliminar).
     */
    public function obtenerPorId(int $idDocumento, int $idAlumno): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM documentos
             WHERE id_documento = :id_documento AND id_alumno = :id_alumno"
        );
        $stmt->execute([
            'id_documento' => $idDocumento,
            'id_alumno' => $idAlumno
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Eliminar un documento de la base de datos (solo si está en estado Nuevo o Rechazado).
     */
    public function eliminarDocumento(int $idDocumento, int $idAlumno): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM documentos
             WHERE id_documento = :id_documento
               AND id_alumno = :id_alumno
               AND estado_validacion IN ('Nuevo', 'Rechazado')"
        );
        $stmt->execute([
            'id_documento' => $idDocumento,
            'id_alumno' => $idAlumno
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtener el estado de los documentos iniciales requeridos.
     */
    public function obtenerEstadoDocumentosIniciales(int $idAlumno): array {
        $documentosRequeridos = [
            'Carta de Aceptación',
            'Plan de Trabajo Inicial',
            'Constancia de Créditos'
        ];

        // Obtener los últimos documentos subidos por cada tipo (para manejar re-subidas)
        $stmt = $this->db->prepare(
            "SELECT tipo_documento, estado_validacion
             FROM documentos
             WHERE id_alumno = :id_alumno
             ORDER BY fecha_subida DESC"
        );
        $stmt->execute(['id_alumno' => $idAlumno]);
        $documentosSubidos = $stmt->fetchAll();

        $estadoMap = [];
        // Solo tomamos el más reciente de cada tipo
        foreach ($documentosSubidos as $doc) {
            if (!isset($estadoMap[$doc['tipo_documento']])) {
                $estadoMap[$doc['tipo_documento']] = $doc['estado_validacion'];
            }
        }

        $resultado = [];
        foreach ($documentosRequeridos as $doc) {
            $resultado[$doc] = $estadoMap[$doc] ?? 'Falta Subir';
        }

        return $resultado;
    }

    public function contarNuevos(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM documentos WHERE estado_validacion = 'Nuevo'");
        return (int) $stmt->fetchColumn();
    }

    public function obtenerDocumentosConAlumno(): array {
        $stmt = $this->db->query(
            "SELECT d.*, a.nombre_completo, a.no_control 
             FROM documentos d 
             JOIN alumnos a ON d.id_alumno = a.id_alumno 
             WHERE d.estado_validacion IN ('Nuevo', 'Corregido') 
             ORDER BY d.fecha_subida DESC"
        );
        return $stmt->fetchAll();
    }

    public function obtenerDocumentoPorIdAdmin(int $idDocumento): ?array {
        $stmt = $this->db->prepare(
            "SELECT d.*, a.nombre_completo, a.no_control 
             FROM documentos d 
             JOIN alumnos a ON d.id_alumno = a.id_alumno 
             WHERE d.id_documento = :id"
        );
        $stmt->execute(['id' => $idDocumento]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function actualizarEstadoConComentarios(int $id, string $estado, ?string $comentarios): bool {
        $stmt = $this->db->prepare(
            "UPDATE documentos SET estado_validacion = :estado, comentarios_dgtyv = :comentarios WHERE id_documento = :id"
        );
        $stmt->execute(['estado' => $estado, 'comentarios' => $comentarios, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerDocumentosRecientes(int $limit = 5): array {
        $stmt = $this->db->prepare(
            "SELECT d.id_documento, d.tipo_documento, d.estado_validacion, d.fecha_subida, a.nombre_completo 
             FROM documentos d 
             JOIN alumnos a ON d.id_alumno = a.id_alumno 
             ORDER BY d.fecha_subida DESC 
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
