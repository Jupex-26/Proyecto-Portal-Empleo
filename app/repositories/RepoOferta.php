<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Oferta;
use PDO;
use DateTime;
/**
 * RepoOferta
 */
class RepoOferta  implements RepoMethods {
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
        $ofertas = [];
        $sql = "SELECT id, nombre, descripcion, empresa_id, fecha_inicio, fecha_fin FROM oferta";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                (int)$row['id'],
                $row['nombre'],
                $row['descripcion'],
                (int)$row['empresa_id'],
                new DateTime($row['fecha_inicio']),
                new DateTime($row['fecha_fin'])
            );
        }
        return $ofertas;
    }    
    /**
     * findById
     *
     * @param  int $id
     * @return Oferta
     */
    public function findById(int $id): ?Oferta {
        $sql = "SELECT id, nombre, descripcion, empresa_id, fecha_inicio, fecha_fin FROM oferta WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Oferta(
                (int)$row['id'],
                $row['nombre'],
                $row['descripcion'],
                (int)$row['empresa_id'],
                new DateTime($row['fecha_inicio']),
                new DateTime($row['fecha_fin'])
            );
        }
        return null;
    }    
    /**
     * save
     *
     * @param  Oferta $oferta
     * @return int
     */
    public function save(object $oferta): ?int {
        $sql = "INSERT INTO oferta (nombre, descripcion, empresa_id, fecha_inicio, fecha_fin) 
                VALUES (:nombre, :descripcion, :empresa_id, :fecha_inicio, :fecha_fin)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $oferta->getNombre());
        $stmt->bindValue(':descripcion', $oferta->getDescripcion());
        $stmt->bindValue(':empresa_id', $oferta->getEmpresaId(), PDO::PARAM_INT);
        $stmt->bindValue(':fecha_inicio', $oferta->getFechaInicio()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':fecha_fin', $oferta->getFechaFin()->format('Y-m-d H:i:s'));
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }    
    /**
     * update
     *
     * @param  Oferta $oferta
     * @return bool
     */
    public function update(object $oferta): bool {
        $sql = "UPDATE oferta 
                SET nombre = :nombre, descripcion = :descripcion, empresa_id = :empresa_id, 
                    fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $oferta->getNombre());
        $stmt->bindValue(':descripcion', $oferta->getDescripcion());
        $stmt->bindValue(':empresa_id', $oferta->getEmpresaId(), PDO::PARAM_INT);
        $stmt->bindValue(':fecha_inicio', $oferta->getFechaInicio()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':fecha_fin', $oferta->getFechaFin()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $oferta->getId(), PDO::PARAM_INT);
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
            // Elimina las solicitudes asociadas primero (por FK)
            $stmtUser = $this->conn->prepare("DELETE FROM solicitud WHERE oferta_id = :id");
            $stmtUser->execute([':id' => $id]);
            // Elimina las asociaciones con ciclos primero (por FK)
            $stmtUser = $this->conn->prepare("DELETE FROM ciclo_tiene_oferta WHERE oferta_id = :id");
            $stmtUser->execute([':id' => $id]);
            // Elimina la oferta
            $stmtUser = $this->conn->prepare("DELETE FROM oferta WHERE id = :id");
            $stmtUser->execute([':id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar alumno: " . $e->getMessage());
            return false;
        }
    }

    /**
 * Obtiene todas las ofertas asociadas a una empresa específica.
 *
 * @param int $empresaId ID de la empresa de la cual se quieren obtener las ofertas.
 * @return array Array de objetos Oferta pertenecientes a la empresa indicada.
 */
public function findAllByEmpresa(int $empresaId): array {
    // Array donde se almacenarán los objetos Oferta creados
    $ofertas = [];

    // Consulta SQL: selecciona solo las ofertas que pertenecen a la empresa indicada
    $sql = "SELECT id, nombre, descripcion, empresa_id, fecha_inicio, fecha_fin 
            FROM oferta 
            WHERE empresa_id = :empresa_id";

    // Preparar la consulta para evitar inyección SQL
    $stmt = $this->conn->prepare($sql);

    // Ejecutar la consulta, pasando el parámetro empresa_id de forma segura
    $stmt->execute(['empresa_id' => $empresaId]);

    // Obtener todas las filas como un array asociativo
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recorrer cada fila del resultado y crear un objeto Oferta
    foreach ($rows as $row) {
        $ofertas[] = new Oferta(
            (int)$row['id'],                    // ID de la oferta
            $row['nombre'],                     // Nombre de la oferta
            $row['descripcion'],                // Descripción
            (int)$row['empresa_id'],            // ID de la empresa propietaria
            new DateTime($row['fecha_inicio']), // Fecha de inicio convertida a objeto DateTime
            new DateTime($row['fecha_fin'])     // Fecha de fin convertida a objeto DateTime
        );
    }

    // Retornar el array de ofertas (vacío si la empresa no tiene ninguna)
    return $ofertas;
}


}