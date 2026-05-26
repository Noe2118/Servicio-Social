<?php
class ProgramaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getProgramasPorDependencia($id_dependencia) {
        $stmt = $this->db->prepare("SELECT * FROM programas WHERE id_dependencia = ?");
        $stmt->execute([$id_dependencia]);
        return $stmt->fetchAll();
    }
}
