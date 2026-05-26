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
        echo "<p>Utiliza la ruta <a href='" . BASE_URL . "/setup/seed_fase4'>/setup/seed_fase4</a> para inyectar la data de prueba de Fase 4.</p>";
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

    public function seed_admin(): void {
        $db = Database::getInstance()->getConnection();
        
        // Create DGTyV admin user if not exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = 'admin@itch.edu.mx'");
        $stmt->execute();
        if ((int)$stmt->fetchColumn() === 0) {
            $db->prepare(
                "INSERT INTO usuarios (correo, password_hash, rol) VALUES ('admin@itch.edu.mx', :pass, 'DGTyV')"
            )->execute(['pass' => password_hash('admin123', PASSWORD_DEFAULT)]);
        }
        
        // Create test dependencias if none exist
        $stmt = $db->query("SELECT COUNT(*) FROM dependencias");
        if ((int)$stmt->fetchColumn() === 0) {
            // First create dependency users
            $depUsers = [
                ['correo' => 'gobierno@test.mx', 'pass' => password_hash('test123', PASSWORD_DEFAULT)],
                ['correo' => 'inegi@test.mx', 'pass' => password_hash('test123', PASSWORD_DEFAULT)],
                ['correo' => 'manosalaobra@test.mx', 'pass' => password_hash('test123', PASSWORD_DEFAULT)],
            ];
            $depUserIds = [];
            foreach ($depUsers as $u) {
                $db->prepare("INSERT INTO usuarios (correo, password_hash, rol) VALUES (:correo, :pass, 'Dependencia')")->execute(['correo' => $u['correo'], 'pass' => $u['pass']]);
                $depUserIds[] = $db->lastInsertId();
            }
            
            $deps = [
                ['id_usuario' => $depUserIds[0], 'nombre' => 'Gobierno del Estado', 'direccion' => 'Av. Héroes #123, Chetumal, Q.Roo'],
                ['id_usuario' => $depUserIds[1], 'nombre' => 'INEGI', 'direccion' => 'Blvd. Bahía #456, Chetumal, Q.Roo'],
                ['id_usuario' => $depUserIds[2], 'nombre' => 'Asociación Civil "Manos a la Obra"', 'direccion' => 'Calle 22 #789, Chetumal, Q.Roo'],
            ];
            foreach ($deps as $d) {
                $db->prepare(
                    "INSERT INTO dependencias (id_usuario, nombre_organizacion, direccion) VALUES (:id_usuario, :nombre, :dir)"
                )->execute(['id_usuario' => $d['id_usuario'], 'nombre' => $d['nombre'], 'dir' => $d['direccion']]);
            }
        }
        
        // Create test programas in 'En Revisión por DGTyV' state
        $stmt = $db->query("SELECT COUNT(*) FROM programas WHERE estado_aprobacion = 'En Revisión por DGTyV'");
        if ((int)$stmt->fetchColumn() === 0) {
            $depIds = $db->query("SELECT id_dependencia FROM dependencias ORDER BY id_dependencia")->fetchAll(PDO::FETCH_COLUMN);
            if (count($depIds) >= 3) {
                $programas = [
                    [
                        'id_dep' => $depIds[0],
                        'folio' => 'PRG-2023-089',
                        'nombre' => 'Desarrollo Tecnológico Rural',
                        'modalidad' => 'Presencial',
                        'descripcion' => 'El programa busca integrar estudiantes de las carreras de Ingeniería en Sistemas e Informática para el desarrollo de una plataforma web que permita a productores rurales registrar y gestionar sus inventarios agrícolas.',
                        'perfiles' => 'Ingeniería en Sistemas Computacionales (3 cupos), Ingeniería Informática (2 cupos), Conocimientos básicos en bases de datos SQL y frameworks web.',
                        'cupos' => 5,
                        'horario' => 'Lunes a Viernes 8:00 - 14:00',
                        'ubicacion' => 'Oficinas Gobierno del Estado, Chetumal',
                        'responsable' => 'Arq. Roberto Almeida',
                        'contacto' => 'ralmeida@gobestado.mx | Ext. 4021',
                        'fecha' => '2023-10-12'
                    ],
                    [
                        'id_dep' => $depIds[1],
                        'folio' => 'PRG-2023-090',
                        'nombre' => 'Actualización Cartográfica Local',
                        'modalidad' => 'Híbrida',
                        'descripcion' => 'Apoyo en la actualización de la cartografía digital del municipio de Othón P. Blanco mediante el uso de herramientas GIS.',
                        'perfiles' => 'Ingeniería Civil (5 cupos), Ingeniería en Sistemas (3 cupos), Arquitectura (4 cupos).',
                        'cupos' => 12,
                        'horario' => 'Lunes a Viernes 9:00 - 15:00',
                        'ubicacion' => 'INEGI Delegación Chetumal',
                        'responsable' => 'Ing. María López',
                        'contacto' => 'mlopez@inegi.org.mx | Ext. 301',
                        'fecha' => '2023-10-10'
                    ],
                    [
                        'id_dep' => $depIds[2],
                        'folio' => 'PRG-2023-091',
                        'nombre' => 'Apoyo Educativo Comunitario',
                        'modalidad' => 'Presencial',
                        'descripcion' => 'Programa de tutorías y apoyo educativo para niños y jóvenes de comunidades vulnerables en la zona sur de Quintana Roo.',
                        'perfiles' => 'Todas las carreras. Se requiere disposición para trabajo comunitario.',
                        'cupos' => 3,
                        'horario' => 'Sábados 9:00 - 13:00',
                        'ubicacion' => 'Comunidades rurales, Othón P. Blanco',
                        'responsable' => 'Lic. Fernando Castro',
                        'contacto' => 'fcastro@manosalaobra.org | Tel. 983-111-2233',
                        'fecha' => '2023-10-08'
                    ],
                ];
                
                foreach ($programas as $p) {
                    $db->prepare(
                        "INSERT INTO programas (id_dependencia, folio_programa, nombre_programa, modalidad, descripcion, perfiles_requeridos, cupos_totales, cupos_ocupados, horario, ubicacion, responsable_nombre, responsable_contacto, estado_aprobacion, fecha_envio) 
                         VALUES (:id_dep, :folio, :nombre, :modalidad, :desc, :perfiles, :cupos, 0, :horario, :ubicacion, :resp, :contacto, 'En Revisión por DGTyV', :fecha)"
                    )->execute([
                        'id_dep' => $p['id_dep'], 'folio' => $p['folio'], 'nombre' => $p['nombre'],
                        'modalidad' => $p['modalidad'], 'desc' => $p['descripcion'], 'perfiles' => $p['perfiles'],
                        'cupos' => $p['cupos'], 'horario' => $p['horario'], 'ubicacion' => $p['ubicacion'],
                        'resp' => $p['responsable'], 'contacto' => $p['contacto'], 'fecha' => $p['fecha']
                    ]);
                }
            }
        }
        
        // Update existing test alumno to have 500 hours for liberation testing
        $db->exec("UPDATE alumnos SET horas_completadas = 500, estado_servicio = 'En curso' WHERE id_alumno = 1");
        
        echo '<h2>Datos de prueba para admin insertados correctamente.</h2>';
        echo '<p><a href="' . BASE_URL . '/auth/login">Ir al Login</a> (admin@itch.edu.mx / admin123)</p>';
    }

    public function seed_fase4() {
        try {
            $this->db->beginTransaction();

            $correoDIF = 'contacto@difmunicipal.gob.mx';
            $stmtCheck = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
            $stmtCheck->execute([$correoDIF]);
            if ($stmtCheck->rowCount() == 0) {
                $stmtUser = $this->db->prepare("INSERT INTO usuarios (correo, password_hash, rol) VALUES (?, ?, 'Dependencia')");
                $stmtUser->execute([$correoDIF, password_hash('dif123', PASSWORD_DEFAULT)]);
                $idUserDIF = $this->db->lastInsertId();

                $stmtDep = $this->db->prepare("INSERT INTO dependencias (id_usuario, nombre_organizacion, direccion) VALUES (?, ?, ?)");
                $stmtDep->execute([$idUserDIF, 'DIF Municipal', 'Calle 5 Sur, Centro']);
                $idDependencia = $this->db->lastInsertId();
            } else {
                $idUserDIF = $stmtCheck->fetchColumn();
                $stmtDep = $this->db->prepare("SELECT id_dependencia FROM dependencias WHERE id_usuario = ?");
                $stmtDep->execute([$idUserDIF]);
                $idDependencia = $stmtDep->fetchColumn();
            }

            $stmtProg = $this->db->prepare("INSERT INTO programas (id_dependencia, folio_programa, nombre_programa, modalidad, cupos_totales, cupos_ocupados, estado_aprobacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtProg->execute([$idDependencia, 'DIF-001', 'Desarrollo Web Frontend', 'Presencial', 5, 2, 'Aprobado']);
            $idProg1 = $this->db->lastInsertId();
            $stmtProg->execute([$idDependencia, 'DIF-002', 'Prácticas de Ingeniería Civil', 'Híbrida', 10, 10, 'Aprobado']);
            $stmtProg->execute([$idDependencia, 'DIF-003', 'Análisis de Datos Económicos', 'Virtual', 3, 0, 'En Revisión por DGTyV']);

            // Crear alumno de prueba asignado
            $stmtUserAl = $this->db->prepare("INSERT INTO usuarios (correo, password_hash, rol) VALUES (?, ?, 'Alumno')");
            $stmtUserAl->execute(['agarcia@itch.edu.mx', password_hash('alumno123', PASSWORD_DEFAULT)]);
            $idUserAl1 = $this->db->lastInsertId();

            $stmtAl = $this->db->prepare("INSERT INTO alumnos (id_usuario, no_control, nombre_completo, carrera) VALUES (?, ?, ?, ?)");
            $stmtAl->execute([$idUserAl1, '19120155', 'García López, Ana', 'Ingeniería en Sistemas Computacionales']);
            $idAl1 = $this->db->lastInsertId();

            $stmtAsig = $this->db->prepare("INSERT INTO asignaciones (id_alumno, id_programa, estado_asignacion) VALUES (?, ?, 'Activo')");
            $stmtAsig->execute([$idAl1, $idProg1]);

            $this->db->commit();
            echo "✅ Seeder Fase 4 ejecutado correctamente.";

        } catch (Exception $e) {
            $this->db->rollBack();
            echo "❌ Error: " . $e->getMessage();
        }
    }
}
