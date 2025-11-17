<?php
namespace app\repositories;
use app\repositories\DB;
use app\repositories\RepoCicloOferta;
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
    $sql = "SELECT o.id, o.nombre, o.descripcion, o.empresa_id, o.fecha_inicio, o.fecha_fin, u.foto 
            FROM oferta o 
            JOIN user u ON o.empresa_id = u.id 
            WHERE o.id = :id";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Obtener los ciclos asociados a la oferta
        $repoCicloOferta = new RepoCicloOferta();
        $ciclos = $repoCicloOferta->findCiclosByOferta($id);
        
        return new Oferta(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            descripcion: $row['descripcion'],
            empresaId: (int)$row['empresa_id'],
            fechaInicio: new DateTime($row['fecha_inicio']),
            fechaFin: new DateTime($row['fecha_fin']),
            ciclos: $ciclos,
            foto: $row['foto']
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
        $ofertas = [];

        $sql = "SELECT o.id, o.nombre, o.descripcion, o.empresa_id, o.fecha_inicio, o.fecha_fin ,u.foto
                FROM oferta o join user u on o.empresa_id=u.id
                WHERE empresa_id = :empresa_id 
                order by id desc";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repo=new RepoCicloOferta();
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                id:(int)$row['id'],                    
                nombre:$row['nombre'],                   
                descripcion:$row['descripcion'],                
                empresaId:(int)$row['empresa_id'],            
                fechaInicio:new DateTime($row['fecha_inicio']), 
                fechaFin: new DateTime($row['fecha_fin']),
                solicitudes:[],
                foto:$row['foto'],
                ciclos:$repo->findCiclosByOferta((int)$row['id'])
            );
        }

        // Retornar el array de ofertas (vacío si la empresa no tiene ninguna)
        return $ofertas;
    }


    /**
     * Obtiene todas las ofertas de una empresa según su estado.
     *
     * @param int $empresaId ID de la empresa.
     * @param string $estado Estado de la oferta: 'activos' o 'finalizados'.
     * @return array Array de objetos Oferta que cumplen con el estado especificado.
     */
    public function findByEstado(int $empresaId, string $estado): array {
        $ofertas = [];

        // Determinar la condición de estado
        $condicionEstado = match($estado) {
            'activos' => 'AND CURDATE() BETWEEN fecha_inicio AND fecha_fin',
            'finalizados' => 'AND fecha_fin < CURDATE()',
            default => '' // Sin filtro si no es válido
        };

        $sql = "SELECT id, nombre, descripcion, empresa_id, fecha_inicio, fecha_fin 
                FROM oferta 
                WHERE empresa_id = :empresa_id
                {$condicionEstado}
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repo = new RepoCicloOferta();
        
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                descripcion: $row['descripcion'],
                empresaId: (int)$row['empresa_id'],
                fechaInicio: new DateTime($row['fecha_inicio']),
                fechaFin: new DateTime($row['fecha_fin']),
                solicitudes: [],
                ciclos: $repo->findCiclosByOferta((int)$row['id'])
            );
        }

        return $ofertas;
    }


    /**
     * Obtiene todas las ofertas de una empresa que tienen ciclos de una familia específica y están en un estado determinado.
     *
     * @param int $empresaId ID de la empresa.
     * @param int $familiaId ID de la familia de ciclos.
     * @param string $estado Estado de la oferta: 'activos' o 'finalizados'.
     * @return array Array de objetos Oferta que cumplen ambos criterios.
     */
    public function findByFamiliaAndEstado(int $empresaId, int $familiaId, string $estado): array {
        $ofertas = [];

        // Determinar la condición de estado
        $condicionEstado = match($estado) {
            'activos' => 'AND CURDATE() BETWEEN o.fecha_inicio AND o.fecha_fin',
            'finalizados' => 'AND o.fecha_fin < CURDATE()',
            default => '' // Sin filtro de estado si no es válido
        };

        $sql = "SELECT DISTINCT o.id, o.nombre, o.descripcion, o.empresa_id, o.fecha_inicio, o.fecha_fin 
                FROM oferta o
                INNER JOIN ciclo_tiene_oferta cto ON o.id = cto.oferta_id
                INNER JOIN ciclo c ON cto.ciclo_id = c.id
                WHERE o.empresa_id = :empresa_id
                AND c.familia_id = :familia_id
                {$condicionEstado}
                ORDER BY o.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'empresa_id' => $empresaId,
            'familia_id' => $familiaId
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repo = new RepoCicloOferta();
        
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                descripcion: $row['descripcion'],
                empresaId: (int)$row['empresa_id'],
                fechaInicio: new DateTime($row['fecha_inicio']),
                fechaFin: new DateTime($row['fecha_fin']),
                solicitudes: [],
                ciclos: $repo->findCiclosByOferta((int)$row['id'])
            );
        }

        return $ofertas;
    }



    /**
     * Obtiene todas las ofertas activas que tienen ciclos de una familia específica.
     *
     * @param int $familiaId ID de la familia de ciclos.
     * @return array Array de objetos Oferta que tienen ciclos de esa familia.
     */
    public function findByFamilia(int $familiaId): array {
        $ofertas = [];

        $sql = "SELECT DISTINCT o.id, o.nombre, o.descripcion, o.empresa_id, o.fecha_inicio, o.fecha_fin, e.foto
                FROM oferta o
                INNER JOIN ciclo_tiene_oferta cto ON o.id = cto.oferta_id
                INNER JOIN ciclo c ON cto.ciclo_id = c.id
                INNER JOIN user e ON o.empresa_id = e.id
                WHERE c.familia_id = :familia_id
                AND CURDATE() BETWEEN o.fecha_inicio AND o.fecha_fin
                ORDER BY o.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['familia_id' => $familiaId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repo = new RepoCicloOferta();
        
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                descripcion: $row['descripcion'],
                empresaId: (int)$row['empresa_id'],
                fechaInicio: new DateTime($row['fecha_inicio']),
                fechaFin: new DateTime($row['fecha_fin']),
                solicitudes: [],
                ciclos: $repo->findCiclosByOferta((int)$row['id']),
                foto: $row['foto'] ?? ''
            );
        }

        return $ofertas;
    }


    /**
     * Obtiene todas las ofertas que tienen alguno de los ciclos especificados.
     *
     * @param array $ciclos Array de objetos Ciclo del alumno.
     * @return array Array de objetos Oferta que tienen al menos uno de esos ciclos.
     */
    public function findByCiclos(array $ciclos): array {
        $ofertas = [];
        
        // Si no hay ciclos, retornar vacío
        if (empty($ciclos)) {
            return $ofertas;
        }
        
        // Extraer los IDs de los ciclos
        $cicloIds = array_map(fn($ciclo) => $ciclo->getId(), $ciclos);
        
        // Crear placeholders para la consulta preparada
        $placeholders = implode(',', array_fill(0, count($cicloIds), '?'));
        
        $sql = "SELECT DISTINCT o.id, o.nombre, o.descripcion, o.empresa_id, o.fecha_inicio, o.fecha_fin, e.foto
                FROM oferta o
                INNER JOIN ciclo_tiene_oferta cto ON o.id = cto.oferta_id
                INNER JOIN user e ON o.empresa_id = e.id
                WHERE cto.ciclo_id IN ({$placeholders})
                AND CURDATE() BETWEEN o.fecha_inicio AND o.fecha_fin
                ORDER BY o.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($cicloIds);
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repo = new RepoCicloOferta();
        
        foreach ($rows as $row) {
            $ofertas[] = new Oferta(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                descripcion: $row['descripcion'],
                empresaId: (int)$row['empresa_id'],
                fechaInicio: new DateTime($row['fecha_inicio']),
                fechaFin: new DateTime($row['fecha_fin']),
                solicitudes: [],
                ciclos: $repo->findCiclosByOferta((int)$row['id']),
                foto: $row['foto'] ?? ''
            );
        }
        
        return $ofertas;
    }


}