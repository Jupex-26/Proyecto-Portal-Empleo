<?php
namespace app\repositories;
use app\repositories\DB; 
use PDO;

class RepoRol implements RepoMethods {
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
        $roles = [];
        $sql = "SELECT id, nombre FROM rol";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $roles[] = [
                'id' => (int)$row['id'],
                'nombre' => $row['nombre']
            ];
        }
        return $roles;
    }    
    /**
     * findById
     *
     * @param  mixed $id
     * @return array
     */
    public function findById(int $id): ?array {
        $sql = "SELECT id, nombre FROM rol WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return [
                'id' => (int)$row['id'],
                'nombre' => $row['nombre']
            ];
        }
        return null;
    }    
    /**
     * save
     *
     * @param  mixed $rol
     * @return int
     */
    public function save(object $rol): ?int {
        $sql = "INSERT INTO rol (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $rol['nombre']);
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }    
    /**
     * update
     *
     * @param  mixed $rol
     * @return bool
     */
    public function update(object $rol): bool {
        $sql = "UPDATE rol SET nombre = :nombre WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $rol['nombre']);
        $stmt->bindValue(':id', $rol['id'], PDO::PARAM_INT);
        return $stmt->execute();
    }    
    /**
     * delete
     *
     * @param  mixed $id
     * @return bool
     */
    public function delete(int $id): bool {
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("UPDATE user SET rol_id = 0 WHERE rol_id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $this->conn->prepare("DELETE FROM rol WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>