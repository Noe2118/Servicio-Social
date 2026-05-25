<?php
class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUsuarioPorCorreo($correo) {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE correo = :correo');
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch();
    }

    public function crearUsuario($correo, $password_hash, $rol) {
        $stmt = $this->db->prepare('INSERT INTO usuarios (correo, password_hash, rol) VALUES (:correo, :password_hash, :rol)');
        $stmt->execute([
            'correo' => $correo,
            'password_hash' => $password_hash,
            'rol' => $rol
        ]);
        return $this->db->lastInsertId();
    }

    public function contarUsuariosPorRol($rol) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM usuarios WHERE rol = :rol');
        $stmt->execute(['rol' => $rol]);
        return $stmt->fetchColumn();
    }
}
