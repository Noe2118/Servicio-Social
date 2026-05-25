<?php
class SetupController extends Controller {
    private $db;

    public function __construct() {
        // Obtenemos directamente la instancia de PDO para inserciones crudas del seeder
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        echo "<h1>Controlador Técnico</h1>";
        echo "<p>Utiliza la ruta <a href='/setup/seed_programas'>/setup/seed_programas</a> para inyectar la data de prueba.</p>";
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
                    'descripcion' => 'Apoyo en actividades de reforestación en zonas de selva baja en Othón P. Blanco.',
                    'perfiles_requeridos' => 'Biología, Arquitectura, Ingeniería Civil',
                    'cupos_totales' => 10,
                    'responsable_nombre' => 'Ing. Carlos Mendoza',
                    'responsable_contacto' => 'cmendoza@conafor.gob.mx'
                ],
                [
                    'folio_programa' => 'SS-2026-002',
                    'nombre_programa' => 'Desarrollo de Sistema de Inventario',
                    'modalidad' => 'Virtual',
                    'descripcion' => 'Creación de software web para control de inventario de flora rescatada.',
                    'perfiles_requeridos' => 'Ingeniería en Sistemas Computacionales, Informática',
                    'cupos_totales' => 3,
                    'responsable_nombre' => 'Lic. Ana Torres',
                    'responsable_contacto' => 'atorres@conafor.gob.mx'
                ],
                [
                    'folio_programa' => 'SS-2026-003',
                    'nombre_programa' => 'Campaña de Concientización Digital',
                    'modalidad' => 'Híbrida',
                    'descripcion' => 'Diseño multimedia para redes sociales fomentando la prevención de incendios.',
                    'perfiles_requeridos' => 'Lic. en Administración, Arquitectura',
                    'cupos_totales' => 2,
                    'responsable_nombre' => 'Mtro. Roberto Ruiz',
                    'responsable_contacto' => 'rruiz@conafor.gob.mx'
                ]
            ];

            // Inserción de programas verificando folios
            $stmtProg = $this->db->prepare("INSERT INTO programas (id_dependencia, folio_programa, nombre_programa, modalidad, descripcion, perfiles_requeridos, cupos_totales, responsable_nombre, responsable_contacto, estado_aprobacion, fecha_envio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Aprobado', CURRENT_DATE)");

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
            echo "<a href='/auth/login' style='padding: 10px 15px; background: #0b5ed7; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir al Login</a>";
            echo "</div>";

        } catch (Exception $e) {
            $this->db->rollBack();
            echo "<h2 style='color: red;'>❌ Error durante la inserción (Seeder)</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
}
