<?php
class SetupController extends Controller {
    private $db;

    public function __construct() {
        // Obtenemos directamente la instancia de PDO para inserciones crudas del seeder
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        echo "<h1>Controlador Técnico</h1>";
        echo "<p>Utiliza la ruta <a href='" . BASE_URL . "/setup/seed_programas'>/setup/seed_programas</a> para inyectar la data de prueba.</p>";
        echo "<p>Utiliza la ruta <a href='" . BASE_URL . "/setup/seed_alumno'>/setup/seed_alumno</a> para crear un alumno de prueba.</p>";
    }

    public function seed_programas() {
        try {
            $this->db->beginTransaction();

            // 1. Crear usuario para una Dependencia genérica
            $correoDep = 'contacto@conafor.gob.mx';
            // Verificar si ya existe para no duplicar en caso de múltiples ejecuciones
            $stmtCheck = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
            $stmtCheck->execute([$correoDep]);
            $usuarioExistente = $stmtCheck->fetchColumn();

            if ($usuarioExistente) {
                $idUsuarioDep = $usuarioExistente;
                $stmtCheckDep = $this->db->prepare("SELECT id_dependencia FROM dependencias WHERE id_usuario = ?");
                $stmtCheckDep->execute([$idUsuarioDep]);
                $idDependencia = $stmtCheckDep->fetchColumn();
            } else {
                // Insertar usuario
                $stmtUserDep = $this->db->prepare("INSERT INTO usuarios (correo, password_hash, rol) VALUES (?, ?, 'Dependencia')");
                $stmtUserDep->execute([$correoDep, password_hash('dependencia123', PASSWORD_DEFAULT)]);
                $idUsuarioDep = $this->db->lastInsertId();

                // Insertar Dependencia
                $stmtDep = $this->db->prepare("INSERT INTO dependencias (id_usuario, nombre_organizacion, direccion) VALUES (?, ?, ?)");
                $stmtDep->execute([$idUsuarioDep, 'CONAFOR - Comisión Nacional Forestal', 'Av. Insurgentes Sur 123, Chetumal, Q. Roo']);
                $idDependencia = $this->db->lastInsertId();
            }

            // 2. Definir 3 Programas de prueba (Dummy Data)
            $programas = [
                [
                    'folio_programa' => 'SS-2026-001',
                    'nombre_programa' => 'Reforestación y Cuidado Ambiental',
                    'modalidad' => 'Presencial',
                    'descripcion' => 'Apoyo en actividades de reforestación en zonas de selva baja en Othón P. Blanco. El alumno participará en la planificación, ejecución y seguimiento de proyectos de restauración ecológica, incluyendo la identificación de especies nativas, preparación de terrenos, siembra y monitoreo de plántulas.',
                    'perfiles_requeridos' => 'Biología, Arquitectura, Ingeniería Civil',
                    'cupos_totales' => 10,
                    'horario' => 'Matutino',
                    'ubicacion' => 'Zona Rural, Othón P. Blanco',
                    'responsable_nombre' => 'Ing. Carlos Mendoza',
                    'responsable_contacto' => 'cmendoza@conafor.gob.mx'
                ],
                [
                    'folio_programa' => 'SS-2026-002',
                    'nombre_programa' => 'Desarrollo de Sistema de Inventario',
                    'modalidad' => 'Virtual',
                    'descripcion' => 'Creación de software web para control de inventario de flora rescatada. El alumno diseñará e implementará una aplicación web con base de datos para registrar, catalogar y dar seguimiento a las especies de flora rescatada por la comisión.',
                    'perfiles_requeridos' => 'Ingeniería en Sistemas Computacionales, Informática',
                    'cupos_totales' => 3,
                    'horario' => 'Flexible',
                    'ubicacion' => 'Remoto',
                    'responsable_nombre' => 'Lic. Ana Torres',
                    'responsable_contacto' => 'atorres@conafor.gob.mx'
                ],
                [
                    'folio_programa' => 'SS-2026-003',
                    'nombre_programa' => 'Campaña de Concientización Digital',
                    'modalidad' => 'Híbrida',
                    'descripcion' => 'Diseño multimedia para redes sociales fomentando la prevención de incendios. El alumno creará contenido gráfico y audiovisual para campañas de concientización ambiental en plataformas digitales.',
                    'perfiles_requeridos' => 'Lic. en Administración, Arquitectura',
                    'cupos_totales' => 5,
                    'horario' => 'Vespertino',
                    'ubicacion' => 'Edificio CONAFOR, Chetumal',
                    'responsable_nombre' => 'Mtro. Roberto Ruiz',
                    'responsable_contacto' => 'rruiz@conafor.gob.mx'
                ]
            ];

            // Inserción de programas verificando folios
            $stmtProg = $this->db->prepare("INSERT INTO programas (id_dependencia, folio_programa, nombre_programa, modalidad, descripcion, perfiles_requeridos, cupos_totales, horario, ubicacion, responsable_nombre, responsable_contacto, estado_aprobacion, fecha_envio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Aprobado', CURRENT_DATE)");

            $insertados = 0;
            foreach ($programas as $p) {
                $checkProg = $this->db->prepare("SELECT COUNT(*) FROM programas WHERE folio_programa = ?");
                $checkProg->execute([$p['folio_programa']]);
                if ($checkProg->fetchColumn() == 0) {
                    $stmtProg->execute([
                        $idDependencia,
                        $p['folio_programa'],
                        $p['nombre_programa'],
                        $p['modalidad'],
                        $p['descripcion'],
                        $p['perfiles_requeridos'],
                        $p['cupos_totales'],
                        $p['horario'],
                        $p['ubicacion'],
                        $p['responsable_nombre'],
                        $p['responsable_contacto']
                    ]);
                    $insertados++;
                }
            }

            $this->db->commit();
            
            echo "<div style='font-family: Arial; padding: 20px;'>";
            echo "<h2 style='color: green;'>✅ Seeder ejecutado correctamente</h2>";
            echo "<p>Dependencia (CONAFOR) asegurada en base de datos.</p>";
            echo "<p>Programas nuevos insertados: <b>{$insertados}</b></p>";
            echo "<br><hr>";
            echo "<h3>Credenciales Generadas:</h3>";
            echo "<ul>";
            echo "<li><b>Administrador:</b> admin@itch.edu.mx / admin123 (Auto-generado en Login)</li>";
            echo "<li><b>Dependencia:</b> contacto@conafor.gob.mx / dependencia123</li>";
            echo "</ul>";
            echo "<a href='" . BASE_URL . "/setup/seed_alumno' style='padding: 10px 15px; background: #198754; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;'>Crear Alumno de Prueba</a>";
            echo "<a href='" . BASE_URL . "/auth/login' style='padding: 10px 15px; background: #0b5ed7; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir al Login</a>";
            echo "</div>";

        } catch (Exception $e) {
            $this->db->rollBack();
            echo "<h2 style='color: red;'>❌ Error durante la inserción (Seeder)</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }

    /**
     * Seeder para crear un alumno de prueba con asignación activa.
     * Endpoint: GET /setup/seed_alumno
     */
    public function seed_alumno() {
        try {
            $this->db->beginTransaction();

            $correoAlumno = 'alumno@itch.edu.mx';

            // Verificar si ya existe
            $stmtCheck = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
            $stmtCheck->execute([$correoAlumno]);
            $idUsuarioExistente = $stmtCheck->fetchColumn();

            if ($idUsuarioExistente) {
                $this->db->commit();
                echo "<div style='font-family: Arial; padding: 20px;'>";
                echo "<h2 style='color: #d97706;'>⚠️ El alumno de prueba ya existe</h2>";
                echo "<p><b>Correo:</b> alumno@itch.edu.mx</p>";
                echo "<p><b>Contraseña:</b> alumno123</p>";
                echo "<a href='" . BASE_URL . "/auth/login' style='padding: 10px 15px; background: #0b5ed7; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir al Login</a>";
                echo "</div>";
                return;
            }

            // 1. Crear usuario
            $stmtUser = $this->db->prepare(
                "INSERT INTO usuarios (correo, password_hash, rol) VALUES (?, ?, 'Alumno')"
            );
            $stmtUser->execute([$correoAlumno, password_hash('alumno123', PASSWORD_DEFAULT)]);
            $idUsuario = $this->db->lastInsertId();

            // 2. Crear perfil de alumno
            $stmtAlumno = $this->db->prepare(
                "INSERT INTO alumnos (id_usuario, no_control, nombre_completo, carrera, periodo_actual, porcentaje_creditos, horas_completadas, estado_servicio)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmtAlumno->execute([
                $idUsuario,
                '22560001',
                'Juan Carlos Pérez López',
                'Ing. en Sistemas Computacionales',
                'Ene-Jun 2026',
                75.50,
                250,
                'En curso'
            ]);
            $idAlumno = $this->db->lastInsertId();

            // 3. Crear asignación activa al primer programa (si existe)
            $stmtProg = $this->db->query("SELECT id_programa FROM programas LIMIT 1");
            $programa = $stmtProg->fetch();
            
            if ($programa) {
                $stmtAsig = $this->db->prepare(
                    "INSERT INTO asignaciones (id_alumno, id_programa, fecha_asignacion, estado_asignacion)
                     VALUES (?, ?, '2025-08-15', 'Activo')"
                );
                $stmtAsig->execute([$idAlumno, $programa['id_programa']]);
            }

            $this->db->commit();

            echo "<div style='font-family: Arial; padding: 20px;'>";
            echo "<h2 style='color: green;'>✅ Alumno de prueba creado exitosamente</h2>";
            echo "<table style='border-collapse: collapse; margin: 15px 0;'>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>Correo:</td><td style='padding: 5px 15px;'>alumno@itch.edu.mx</td></tr>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>Contraseña:</td><td style='padding: 5px 15px;'>alumno123</td></tr>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>No. Control:</td><td style='padding: 5px 15px;'>22560001</td></tr>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>Nombre:</td><td style='padding: 5px 15px;'>Juan Carlos Pérez López</td></tr>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>Horas Completadas:</td><td style='padding: 5px 15px;'>250 / 500</td></tr>";
            echo "<tr><td style='padding: 5px 15px; font-weight: bold;'>Estado:</td><td style='padding: 5px 15px;'>En curso</td></tr>";
            echo "</table>";
            if ($programa) {
                echo "<p style='color: green;'>✔ Asignación activa creada al primer programa disponible.</p>";
            }
            echo "<a href='" . BASE_URL . "/auth/login' style='padding: 10px 15px; background: #0b5ed7; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir al Login</a>";
            echo "</div>";

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            echo "<h2 style='color: red;'>❌ Error al crear alumno de prueba</h2>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
