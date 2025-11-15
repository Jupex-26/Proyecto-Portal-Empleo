<?php
namespace app\repositories;

use app\repositories\DB;
use app\models\NivelEnum;
use app\models\Ciclo;
use PDO;
use PDOException;

/**
 * RepoCicloTieneOferta
 * Repositorio para gestionar la relación muchos a muchos entre Ciclo y Oferta
 */
class RepoCicloOferta implements RepoMethods {
    private $conn;

    public function __construct() {
        $this->conn = DB::getConnection();
    }

    /**
     * Obtiene todas las relaciones ciclo-oferta
     *
     * @return array Array de arrays asociativos con ciclo_id y oferta_id
     */
    public function findAll(): array {
        $relaciones = [];
        $sql = "SELECT ciclo_id, oferta_id FROM ciclo_tiene_oferta";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $relaciones[] = [
                'ciclo_id' => (int)$row['ciclo_id'],
                'oferta_id' => (int)$row['oferta_id']
            ];
        }
        
        return $relaciones;
    }

    /**
     * Busca una relación específica por ciclo_id y oferta_id
     * Como no hay un ID único, se busca por la combinación de ambos
     *
     * @param int $id Puede ser ciclo_id u oferta_id dependiendo del contexto
     * @return array|null Array asociativo con la relación o null
     */
    public function findById(int $id): ?object {
        // Este método busca todas las relaciones para un ciclo_id específico
        $sql = "SELECT ciclo_id, oferta_id FROM ciclo_tiene_oferta WHERE ciclo_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows) {
            $relaciones = [];
            foreach ($rows as $row) {
                $relaciones[] = [
                    'ciclo_id' => (int)$row['ciclo_id'],
                    'oferta_id' => (int)$row['oferta_id']
                ];
            }
            return $relaciones;
        }
        
        return null;
    }

    /**
     * Guarda una nueva relación ciclo-oferta
     * El objeto debe tener métodos getCicloId() y getOfertaId()
     *
     * @param object $objeto Objeto con la relación
     * @return int|null Retorna 1 si se insertó correctamente, null si falló
     */
    public function save(object $objeto): ?int {
        try {
            $sql = "INSERT INTO ciclo_tiene_oferta (ciclo_id, oferta_id) 
                    VALUES (:ciclo_id, :oferta_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':ciclo_id', $objeto->getCicloId(), PDO::PARAM_INT);
            $stmt->bindValue(':oferta_id', $objeto->getOfertaId(), PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return 1; // No hay lastInsertId en tablas sin PK autoincremental
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error al guardar relación ciclo-oferta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza una relación ciclo-oferta
     * En este caso, no tiene mucho sentido ya que no hay campos adicionales
     * Este método está aquí por cumplir con la interfaz
     *
     * @param object $objeto
     * @return bool
     */
    public function update(object $objeto): bool {
        // No hay campos que actualizar en una tabla de relación
        // Si se necesita "actualizar", normalmente se elimina y se crea de nuevo
        return true;
    }

    /**
     * Elimina una relación específica por ciclo_id y oferta_id
     * 
     * @param int $id ID del ciclo (se eliminan todas sus relaciones)
     * @return bool
     */
    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM ciclo_tiene_oferta WHERE ciclo_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar relación: " . $e->getMessage());
            return false;
        }
    }
     /**
     * Guarda masivamente las asociaciones entre una oferta y múltiples ciclos
     *
     * @param int $ofertaId ID de la oferta
     * @param array $ciclos Array de objetos Ciclo
     * @return bool True si todas las inserciones fueron exitosas
     */
    public function saveMasivo(int $ofertaId, array $ciclos): bool {
        try {
            $this->conn->beginTransaction();
            
            // Insertar cada asociación
            foreach ($ciclos as $ciclo) {
                $sql = "INSERT INTO ciclo_tiene_oferta (ciclo_id, oferta_id) 
                        VALUES (:ciclo_id, :oferta_id)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':ciclo_id', $ciclo->getId(), PDO::PARAM_INT);
                $stmt->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error en save masivo: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Obtiene todos los objetos Ciclo asociados a una oferta
     *
     * @param int $ofertaId
     * @return array Array de objetos Ciclo
     */
    public function findCiclosByOferta(int $ofertaId): array {
        $ciclos = [];
        $sql = "SELECT c.id, c.nivel, c.nombre, c.familia_id 
                FROM ciclo c
                INNER JOIN ciclo_tiene_oferta cto ON c.id = cto.ciclo_id
                WHERE cto.oferta_id = :oferta_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':oferta_id', $ofertaId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $ciclos[] = new Ciclo(
                (int)$row['id'],
                NivelEnum::from($row['nivel']),
                $row['nombre'],
                (int)$row['familia_id']
            );
        }
        
        return $ciclos;
    }
}