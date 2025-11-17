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

    /**
     * Obtiene los niveles distintos de ciclos formativos para una familia profesional específica.
     *
     * @param int $familia_id Identificador de la familia profesional
     * @return array Array de objetos NivelEnum con los niveles disponibles ordenados
     */
    public function findNivelByFamily(int $familia_id) {
        $niveles = [];
        $sql = "SELECT DISTINCT nivel FROM ciclo WHERE familia_id = :familia_id ORDER BY nivel";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':familia_id', $familia_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $nivelEnum = NivelEnum::from($row['nivel']);
    
            $niveles[] = new Ciclo(
                null,                 
                $nivelEnum,           
                $nivelEnum->name,     
                $familia_id                     
            );
        }
        return $niveles;
    }

    /**
     * Busca ciclos formativos filtrados por familia profesional y nivel educativo.
     *
     * @param int $familia_id Identificador de la familia profesional
     * @param string $nivel Nivel del ciclo formativo (BASICO, MEDIO, SUPERIOR, ESPECIALIZACION)
     * @return array Array de objetos Ciclo que cumplen los criterios de búsqueda, ordenados por nombre
     */
    public function findByNivelFamily(int $familia_id, string $nivel) {
        $ciclos = [];
        $sql = "SELECT id, nivel, nombre, familia_id FROM ciclo WHERE familia_id = :familia_id AND nivel = :nivel ORDER BY nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':familia_id', $familia_id);
        $stmt->bindValue(':nivel', $nivel);
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