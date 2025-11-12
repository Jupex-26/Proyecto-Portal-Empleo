<?php
namespace app\repositories;

use app\repositories\DB;
use PDO;
use PDOException;
class RepoAlumCiclo implements RepoMethods {
    private $conn;

    public function __construct() {
        $this->conn = DB::getConnection();
    }

    /**
     * findAll
     *
     * @return array
     */
    public function findAll(): array {
        $resultados = [];
        $sql = "SELECT id, alumno_id, ciclo_id, fecha_inicio, fecha_fin 
                FROM alum_cursado_ciclo";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $resultados[] = new AlumCursadoCiclo(
                $row['id'],
                $row['alumno_id'],
                $row['ciclo_id'],
                new DateTime($row['fecha_inicio']),
                isset($row['fecha_fin']) ? new DateTime($row['fecha_fin']) : null
            );
        }
        return $resultados;
    }

    /**
     * findById
     *
     * @param  int $id
     * @return AlumCursadoCiclo|null
     */
    public function findById(int $id): ?object {
        $sql = "SELECT id, alumno_id, ciclo_id, fecha_inicio, fecha_fin 
                FROM alum_cursado_ciclo 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new AlumCursadoCiclo(
                $row['id'],
                $row['alumno_id'],
                $row['ciclo_id'],
                new DateTime($row['fecha_inicio']),
                isset($row['fecha_fin']) ? new DateTime($row['fecha_fin']) : null
            );
        }
        return null;
    }

    /**
     * save
     *
     * @param  AlumCursadoCiclo $obj
     * @return int|null
     */
    public function save(object $obj): ?int {
        $sql = "INSERT INTO alum_cursado_ciclo 
                    (alumno_id, ciclo_id, fecha_inicio, fecha_fin) 
                VALUES 
                    (:alumno_id, :ciclo_id, :fecha_inicio, :fecha_fin)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':alumno_id', $obj->getAlumnoId(), PDO::PARAM_INT);
        $stmt->bindValue(':ciclo_id', $obj->getCicloId(), PDO::PARAM_INT);
        $stmt->bindValue(':fecha_inicio', $obj->getFechaInicio()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':fecha_fin', $obj->getFechaFin()?->format('Y-m-d H:i:s'));

        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }

    /**
     * update
     *
     * @param  AlumCursadoCiclo $obj
     * @return bool
     */
    public function update(object $obj): bool {
        $sql = "UPDATE alum_cursado_ciclo 
                SET alumno_id = :alumno_id, 
                    ciclo_id = :ciclo_id, 
                    fecha_inicio = :fecha_inicio, 
                    fecha_fin = :fecha_fin 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':alumno_id', $obj->getAlumnoId(), PDO::PARAM_INT);
        $stmt->bindValue(':ciclo_id', $obj->getCicloId(), PDO::PARAM_INT);
        $stmt->bindValue(':fecha_inicio', $obj->getFechaInicio()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':fecha_fin', $obj->getFechaFin()?->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("DELETE FROM alum_cursado_ciclo WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar registro de alum_cursado_ciclo: " . $e->getMessage());
            return false;
        }
    }
}
