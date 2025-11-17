<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Solicitud;
use app\models\EstadoSolicitud;
use PDO;
/**
 * RepoSolicitud
 */
class RepoSolicitud implements RepoMethods {
    private $conn;
    public function __construct() {
        $this->conn = DB::getConnection();
    }    
    /**
     * findAll
     *
     * @return array
     */
    public function findAll():array{
        $solicitudes = [];
        $sql = "SELECT id, alumno_id, oferta_id, estado FROM solicitud";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $solicitudes[] = new Solicitud(
                (int)$row['id'],
                (int)$row['alumno_id'],
                (int)$row['oferta_id'],
                EstadoSolicitud::from($row['estado'])
            );
        }
        return $solicitudes;
    }    
    /**
     * findById
     *
     * @param  mixed $id
     * @return Solicitud
     */
    public function findById(int $id): ?Solicitud {
        $sql = "SELECT id, alumno_id, oferta_id, estado FROM solicitud WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Solicitud(
                (int)$row['id'],
                (int)$row['alumno_id'],
                (int)$row['oferta_id'],
                EstadoSolicitud::from($row['estado'])
            );
        }
        return null;
    }    
    /**
     * save
     *
     * @param  mixed $solicitud
     * @return int
     */
    public function save(object $solicitud): ?int {
        $sql = "INSERT INTO solicitud (alumno_id, oferta_id, estado) VALUES (:alumno_id, :oferta_id, :estado)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':alumno_id', $solicitud->getAlumnoId());
        $stmt->bindValue(':oferta_id', $solicitud->getOfertaId());
        $stmt->bindValue(':estado', $solicitud->getEstado()->value);
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }    
    /**
     * update
     *
     * @param  mixed $solicitud
     * @return bool
     */
    public function update(object $solicitud): bool {
        $sql = "UPDATE solicitud SET alumno_id = :alumno_id, oferta_id = :oferta_id, estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':alumno_id', $solicitud->getAlumnoId());
        $stmt->bindValue(':oferta_id', $solicitud->getOfertaId());
        $stmt->bindValue(':estado', $solicitud->getEstado()->value);
        $stmt->bindValue(':id', $solicitud->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }    
    /**
     * delete
     *
     * @param  mixed $id
     * @return bool
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM solicitud WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Obtiene todas las solicitudes de un alumno específico.
     *
     * @param int $alumnoId ID del alumno.
     * @return array Array de objetos Solicitud.
     */
    public function findByAlumno(int $alumnoId): array {
        $solicitudes = [];
        
        $sql = "SELECT id, alumno_id, oferta_id, estado 
                FROM solicitud 
                WHERE alumno_id = :alumno_id
                ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['alumno_id' => $alumnoId]);
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $solicitudes[] = new Solicitud(
                id: (int)$row['id'],
                alumnoId: (int)$row['alumno_id'],
                ofertaId: (int)$row['oferta_id'],
                estado: EstadoSolicitud::from($row['estado'])
            );
        }
        
        return $solicitudes;
    }

    /**
     * Obtiene la solicitud de un alumno para una oferta específica.
     *
     * @param int $alumnoId ID del alumno.
     * @param int $ofertaId ID de la oferta.
     * @return array Array con un objeto Solicitud si existe, array vacío si no.
     */
    public function findByAlumnoAndOferta(int $alumnoId, int $ofertaId): array {
        $sql = "SELECT id, alumno_id, oferta_id, estado 
                FROM solicitud 
                WHERE alumno_id = :alumno_id AND oferta_id = :oferta_id
                LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'alumno_id' => $alumnoId,
            'oferta_id' => $ofertaId
        ]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return [new Solicitud(
                id: (int)$row['id'],
                alumnoId: (int)$row['alumno_id'],
                ofertaId: (int)$row['oferta_id'],
                estado: EstadoSolicitud::from($row['estado'])
            )];
        }
        
        return [];
    }

    

}
?>