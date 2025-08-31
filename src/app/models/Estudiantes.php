<?php
require_once __DIR__ . "/../../core/Database.php";

class Estudiantes
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getEstudiantes()
    {
        $sql = "SELECT * FROM usuarios";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEstudiantesForExport()
    {
        $sql = "SELECT nombre,apellido,email,programa,calificacion FROM usuarios";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEstudiantesById($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $query = $this->conn->prepare($sql);
        $query->execute([":id" => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEstudiantesByPrograma($programa)
    {
        $sql = "SELECT * FROM usuarios WHERE programa = :programa";
        $query = $this->conn->prepare($sql);
        $query->execute([":programa" => $programa]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEstudiantesByBusqueda($busqueda)
    {
        $sql = "SELECT * FROM usuarios
        WHERE nombre LIKE :busqueda
           OR apellido LIKE :busqueda
           OR email LIKE :busqueda
           OR usuario LIKE :busqueda";

        $query = $this->conn->prepare($sql);
        $query->execute([':busqueda' => "%$busqueda%"]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEstudiantesByCalificaciones($data)
    {
        $sql = "SELECT * FROM usuarios WHERE calificacion BETWEEN :calificacion_inicio AND :calificacion_final";
        $query = $this->conn->prepare($sql);
        $query->execute([
            ":calificacion_inicio" => $data['calificacion_inicio'],
            ":calificacion_final" => $data['calificacion_final'],
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function createEstudiante($data)
    {
        $sql = "INSERT INTO usuarios (nombre,apellido, usuario, email, programa, calificacion)
               VALUES (:nombre,:apellido,:usuario,:email,:programa,:calificacion)";
        $query = $this->conn->prepare($sql);
        return $query->execute([
            ":nombre" => $data["nombre"],
            ":apellido" => $data["apellido"],
            ":usuario" => $data["usuario"],
            ":email" => $data["email"],
            ":programa" => $data["programa"],
            ":calificacion" => $data["calificacion"],
        ]);
    }

    public function updateEstudiante($data)
    {
        $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, email = :email, usuario = :usuario, programa = :programa, calificacion = :calificacion, profile_url = :profile_url WHERE id =:id";
        $query = $this->conn->prepare($sql);
        $query->execute([
            ":id" => $data["id"],
            ":nombre" => $data["nombre"],
            ":apellido" => $data["apellido"],
            ":email" => $data["email"],
            ":usuario" => $data["usuario"],
            ":programa" => $data["programa"],
            ":calificacion" => $data["calificacion"],
            ":profile_url" => $data['profile_url'] ?? null,
        ]);
        return $query->rowCount() > 0;
    }

    public function deleteEstudiante($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $query = $this->conn->prepare($sql);
        $query->execute([":id" => $id]);
        return $query->rowCount() > 0;
    }

    public function getDatosGenerales()
    {
        $sql = "SELECT count(*) as total_estudiantes, AVG(calificacion) as promedio_general, MAX(calificacion) as calificacion_maxima, MIN(calificacion) as calificacion_minima FROM usuarios";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPromedioPorPrograma()
    {
        $sql = "SELECT programa, AVG(calificacion) as promedio_programa, COUNT(programa)as total_estudiante_programa FROM usuarios GROUP BY programa";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTop5Estudiantes()
    {
        $sql = "SELECT nombre, apellido, programa, calificacion FROM usuarios ORDER BY calificacion DESC LIMIT 5";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRankingAllEstudiantes()
    {
        $sql = "SELECT
    id,
    nombre,
    programa,
    calificacion,
    ROW_NUMBER() OVER (PARTITION BY programa ORDER BY calificacion DESC) AS ranking
FROM usuarios
ORDER BY
    CASE programa
        WHEN 'ADSO' THEN 1
        WHEN 'Entrenamiento' THEN 2
        WHEN 'Sst' THEN 3
        ELSE 99
    END,
    ranking;
";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }



}

?>