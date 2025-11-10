<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Familia;
use PDO;
/**
 * RepoFamilia
 */
class RepoFamilia implements RepoMethods {
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
        $familias = [];
        $sql = "SELECT id, nombre FROM familia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $familias[] = new Familia(
                $row['id'],
                $row['nombre']
            );
        }
        return $familias;
    }        
    /**
     * findById
     *
     * @param  int $id
     * @return Familia
     */
    public function findById(int $id): ?Familia {
        $sql = "SELECT id, nombre FROM familia WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Familia(
                $row['id'],
                $row['nombre']
            );
        }
        return null;
    }    
    /**
     * save
     *
     * @param  Familia $familia
     * @return int
     */
    public function save(object $familia): ?int {
        $sql = "INSERT INTO familia (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $familia->getNombre());
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }    
    /**
     * update
     *
     * @param  Familia $familia
     * @return bool
     */
    public function update(object $familia): bool {
        $sql = "UPDATE familia SET nombre = :nombre WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $familia->getNombre());
        $stmt->bindValue(':id', $familia->getId(), PDO::PARAM_INT);
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
            // Elimina ciclos asociados primero (por FK)
            $stmtCiclo = $this->conn->prepare("DELETE FROM ciclo WHERE familia_id = :id");
            $stmtCiclo->execute(['id' => $id]);
            // Elimina la familia  
            $stmtFamilia = $this->conn->prepare("DELETE FROM familia WHERE id = :id");
            $stmtFamilia->execute(['id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar familia: " . $e->getMessage());
            return false;
        }
    }
}