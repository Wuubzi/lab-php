<?php
require_once __DIR__ . "/../../core/Database.php";
class Historial
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function registrarCambio($datos)
    {
        // Generar ID único para el registro
        $idRegistro = $this->generarIdRegistro();

        $sql = "INSERT INTO historial_cambios 
                (id_registro, usuario, email, accion, tabla_recurso, descripcion, ip_address, valores_anteriores, valores_nuevos) 
                VALUES (:id_registro, :usuario, :email, :accion, :tabla_recurso, :descripcion, :ip_address, :valores_anteriores, :valores_nuevos)";

        $query = $this->conn->prepare($sql);

        return $query->execute([
            ':id_registro' => $idRegistro,
            ':usuario' => $datos['usuario'],
            ':email' => $datos['email'],
            ':accion' => $datos['accion'],
            ':tabla_recurso' => $datos['tabla_recurso'],
            ':descripcion' => $datos['descripcion'],
            ':ip_address' => $datos['ip_address'] ?? $this->obtenerIP(),
            ':valores_anteriores' => isset($datos['valores_anteriores']) ? json_encode($datos['valores_anteriores']) : null,
            ':valores_nuevos' => isset($datos['valores_nuevos']) ? json_encode($datos['valores_nuevos']) : null
        ]);
    }

    public function obtenerHistorial($limite = 50)
    {
        $sql = "SELECT * FROM historial_cambios ORDER BY fecha_hora DESC LIMIT :limite";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':limite', $limite, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerHistorialPorTabla($tabla, $limite = 50)
    {
        $sql = "SELECT * FROM historial_cambios WHERE tabla_recurso = :tabla ORDER BY fecha_hora DESC LIMIT :limite";
        $query = $this->conn->prepare($sql);
        $query->execute([':tabla' => $tabla]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generarIdRegistro()
    {
        $sql = "SELECT COUNT(*) + 1 as siguiente_id FROM historial_cambios";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return '#' . str_pad($result['siguiente_id'], 5, '0', STR_PAD_LEFT);
    }

    private function obtenerIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
    }
}

?>