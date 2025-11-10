<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Empresa;
use PDO;
/**
 * RepoEmpresa
 *
 * Métodos estáticos para manejar las empresas en la base de datos.
 */
class RepoEmpresa implements RepoMethods {
    private $conn;
    public function __construct() {
        $this->conn = DB::getConnection();
    }
    /**
 * Devuelve todas las empresas como array de objetos Empresa.
 * @return Empresa[]
 */
public function findAll(): array {
    $empresas = [];

    $sql = "
        SELECT 
            u.id,
            u.nombre,
            u.correo,
            u.rol_id,
            u.direccion,
            u.foto,
            u.token_id,
            e.correoContacto,
            e.telefonoContacto,
            e.activo,
            e.descripcion
        FROM user u
        INNER JOIN empresa e ON u.id = e.id
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $empresas[] = new Empresa(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            email: $row['correo'],
            rol: (int)$row['rol_id'],
            direccion: $row['direccion'],
            foto: $row['foto'] ?? '',
            token: $row['token_id'] !== null ? (int)$row['token_id'] : null,
            correoContacto: $row['correoContacto'] ?? '',
            telefonoContacto: (int)($row['telefonoContacto'] ?? 0),
            activo: (bool)($row['activo'] ?? false),
            descripcion: $row['descripcion'] ?? '',
            ofertas: [] // puedes rellenar esto luego con otra consulta si lo necesitas
        );
    }

    return $empresas;
}

/**
 * Encuentra una empresa por su ID.
 * @param int $id
 * @return Empresa|null
 */
public function findById(int $id): ?Empresa {
    $sql = "
        SELECT 
            u.id,
            u.nombre,
            u.correo,
            u.rol_id,
            u.direccion,
            u.foto,
            u.token_id,
            e.correoContacto,
            e.telefonoContacto,
            e.activo,
            e.descripcion
        FROM user u
        INNER JOIN empresa e ON u.id = e.id
        WHERE u.id = :id
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return new Empresa(
        id: (int)$row['id'],
        nombre: $row['nombre'],
        email: $row['correo'],
        rol: (int)$row['rol_id'],
        direccion: $row['direccion'],
        foto: $row['foto'] ?? '',
        token: $row['token_id'] !== null ? (int)$row['token_id'] : null,
        correoContacto: $row['correoContacto'] ?? '',
        telefonoContacto: (int)($row['telefonoContacto'] ?? 0),
        activo: (bool)($row['activo'] ?? false),
        descripcion: $row['descripcion'] ?? '',
        ofertas: [] // si luego haces otra consulta para las ofertas
    );
}

    /**
     * Guarda una nueva empresa en la base de datos.
     * @param Empresa $entity
     * @return bool
     */
    public function save(object $entity): ?int {

        try {
            $this->conn->beginTransaction();

            // Inserta primero en "user"
            $sqlUser = "
                INSERT INTO user (nombre, correo, passwd, rol_id, direccion, foto, token_id)
                VALUES (:nombre, :correo, :passwd, :rol, :direccion, :foto, :token)
            ";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([
                'nombre' => $entity->getNombre(),
                'correo' => $entity->getEmail(),
                'passwd' => $entity->getPassword(),
                'rol' => $entity->getRol(),
                'direccion' => $entity->getDireccion(),
                'foto' => $entity->getFoto(),
                'token' => $entity->getToken()
            ]);

            $userId = (int)$this->conn->lastInsertId();

            // Inserta en "empresa"
            $sqlEmpresa = "
                INSERT INTO empresa (id, correoContacto, telefonoContacto, activo, descripcion)
                VALUES (:id, :correoContacto, :telefonoContacto, :activo, :descripcion)
            ";
            $stmtEmp = $this->conn->prepare($sqlEmpresa);
            $stmtEmp->execute([
                'id' => $userId,
                'correoContacto' => $entity->getCorreoContacto(),
                'telefonoContacto' => $entity->getTelefonoContacto(),
                'activo' => $entity->isActivo(),
                'descripcion' => $entity->getDescripcion(),
            ]);

            $this->conn->commit();
            return $userId;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al guardar empresa: " . $e->getMessage());
            return null;
        }
    }

        
    /**
     * Actualiza una empresa existente.
     *
     * @param  Empresa $entity
     * @return bool
     */
    public function update(object $entity): bool {

        try {
            $this->conn->beginTransaction();

            // Update tabla user
            $sqlUser = "
                UPDATE user SET
                    nombre = :nombre,
                    correo = :correo,
                    passwd = :passwd,
                    rol_id = :rol,
                    direccion = :direccion,
                    foto = :foto,
                    token_id = :token
                WHERE id = :id
            ";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([
                'id' => $entity->getId(),
                'nombre' => $entity->getNombre(),
                'correo' => $entity->getEmail(),
                'passwd' => $entity->getPassword(),
                'rol' => $entity->getRol(),
                'direccion' => $entity->getDireccion(),
                'foto' => $entity->getFoto(),
                'token' => $entity->getToken()
            ]);

            // Update tabla empresa
            $sqlEmp = "
                UPDATE empresa SET
                    correoContacto = :correoContacto,
                    telefonoContacto = :telefonoContacto,
                    activo = :activo,
                    descripcion = :descripcion
                WHERE id = :id
            ";
            $stmtEmp = $this->conn->prepare($sqlEmp);
            $stmtEmp->execute([
                'id' => $entity->getId(),
                'correoContacto' => $entity->getCorreoContacto(),
                'telefonoContacto' => $entity->getTelefonoContacto(),
                'activo' => $entity->isActivo(),
                'descripcion' => $entity->getDescripcion(),
            ]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar empresa: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una empresa por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        try {
            $this->conn->beginTransaction();
            // Elimina ofertas asociadas primero (por FK)
            $stmtEmp = $this->conn->prepare("DELETE FROM oferta WHERE empresa_id = :id");
            $stmtEmp->execute(['id' => $id]);
            // Elimina primero de empresa (por FK)
            $stmtEmp = $this->conn->prepare("DELETE FROM empresa WHERE id = :id");
            $stmtEmp->execute(['id' => $id]);

            // Luego elimina de user
            $stmtUser = $this->conn->prepare("DELETE FROM user WHERE id = :id");
            $stmtUser->execute(['id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar empresa: " . $e->getMessage());
            return false;
        }
    }

    
    /**
     * findAllLimit
     *
     * @param  mixed $index
     * @param  mixed $size
     * @return array
     */
    public function findAllLimitWActive(int $index, int $size, bool $bool): array {
    $empresas = [];

    $sql = "
        SELECT 
            u.id,
            u.nombre,
            u.correo,
            u.rol_id,
            u.direccion,
            u.foto,
            u.token_id,
            e.correoContacto,
            e.telefonoContacto,
            e.activo,
            e.descripcion
        FROM user u
        INNER JOIN empresa e ON u.id = e.id
        where e.activo = :activo
        LIMIT $size offset $index
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':activo', $bool, PDO::PARAM_BOOL);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $empresas[] = new Empresa(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            email: $row['correo'],
            rol: (int)$row['rol_id'],
            direccion: $row['direccion'],
            foto: $row['foto'] ?? '',
            token: $row['token_id'] !== null ? (int)$row['token_id'] : null,
            correoContacto: $row['correoContacto'] ?? '',
            telefonoContacto: (int)($row['telefonoContacto'] ?? 0),
            activo: (bool)($row['activo'] ?? false),
            descripcion: $row['descripcion'] ?? '',
            ofertas: [] // puedes rellenar esto luego con otra consulta si lo necesitas
        );
    }

    return $empresas;
    }
    
    /**
     * getCount
     * Obtener el total de empresas que existen en la base de datos
     *
     * @return void
     */
    public function getCount(){
        $sql = "SELECT COUNT(*) as total FROM empresa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Obtener el valor del count
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }
}
?>