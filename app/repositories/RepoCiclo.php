<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Ciclo;
use app\models\NivelEnum;
use PDO;
/**
 * RepoCiclo
 */
class RepoCiclo implements RepoMethods {
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
        $ciclos = [];
        $sql = "SELECT id, nivel, nombre, familia_id FROM ciclo";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $ciclos[] = new Ciclo(
                $row['id'],
                NivelEnum::from($row['nivel']),
                $row['nombre'],
                $row['familia_id']
            );
        }
        return $ciclos;
    }    
    /**
     * findById
     *
     * @param  int $id
     * @return Ciclo
     */
    public function findById(int $id): ?Ciclo {
        $sql = "SELECT id, nivel, nombre, familia_id FROM ciclo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Ciclo(
                $row['id'],
                NivelEnum::from($row['nivel']),
                $row['nombre'],
                $row['familia_id']
            );
        }
        return null;
    }    
    /**
     * save
     *
     * @param  Ciclo $ciclo
     * @return int
     */
    public function save(object $ciclo): ?int {
        $sql = "INSERT INTO ciclo (nivel, nombre, familia_id) VALUES (:nivel, :nombre, :familia_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nivel', $ciclo->getNivel()->value);
        $stmt->bindValue(':nombre', $ciclo->getNombre());
        $stmt->bindValue(':familia_id', $ciclo->getFamiliaId(), PDO::PARAM_INT);
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }    
    /**
     * update
     *
     * @param  Ciclo $ciclo
     * @return bool
     */
    public function update(object $ciclo): bool {
        $sql = "UPDATE ciclo SET nivel = :nivel, nombre = :nombre, familia_id = :familia_id WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nivel', $ciclo->getNivel()->value);
        $stmt->bindValue(':nombre', $ciclo->getNombre());
        $stmt->bindValue(':familia_id', $ciclo->getFamiliaId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $ciclo->getId(), PDO::PARAM_INT);
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
            // Elimina ofertas asociadas primero (por FK)
            $stmtEmp = $this->conn->prepare("DELETE FROM alum_cursado_ciclo WHERE ciclo_id = :id");
            $stmtEmp->execute(['id' => $id]);

            // Elimina relaciones ciclo-oferta primero (por FK)
            $stmtEmp = $this->conn->prepare("DELETE FROM ciclo_tiene_oferta WHERE ciclo_id = :id");
            $stmtEmp->execute(['id' => $id]);

            // Elimina el ciclo
            $stmtEmp = $this->conn->prepare("DELETE FROM ciclo WHERE id = :id");
            $stmtEmp->execute(['id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar empresa: " . $e->getMessage());
            return false;
        }
    }
    public function findByFamily(int $familia_id){
        $ciclos = [];
        $sql = "SELECT id, nivel, nombre, familia_id FROM ciclo where familia_id = :familia_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':familia_id', $familia_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $ciclos[] = new Ciclo(
                $row['id'],
                NivelEnum::from($row['nivel']),
                $row['nombre'],
                $row['familia_id']
            );
        }
        return $ciclos;
    }
}
?>