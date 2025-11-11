<?php
namespace app\repositories;

use app\repositories\DB;
use app\models\User;
use PDO;
use PDOException;

class RepoUser implements RepoMethods {
    private $conn;

    public function __construct() {
        $this->conn = DB::getConnection();
    }

    /**
     * Devuelve todos los usuarios como array de objetos User.
     * @return User[]
     */
    public function findAll(): array {
        $usuarios = [];

        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.correo,
                u.passwd,
                u.rol_id,
                u.direccion,
                u.foto,
                u.token_id
            FROM user u
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $usuarios[] = new User(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                email: $row['correo'],
                rol: (int)$row['rol_id'],
                direccion: $row['direccion'],
                passwd: $row['passwd'],
                foto: $row['foto'] ?? '',
                token: (int)($row['token_id'] ?? null)
            );
        }

        return $usuarios;
    }

    /**
     * Encuentra un usuario por su ID.
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User {
        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.correo,
                u.passwd,
                u.rol_id,
                u.direccion,
                u.foto,
                u.token_id
            FROM user u
            WHERE u.id = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new User(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            email: $row['correo'],
            rol: (int)$row['rol_id'],
            direccion: $row['direccion'],
            passwd: $row['passwd'],
            foto: $row['foto'] ?? '',
            token: (int)($row['token_id'] ?? null)
        );
    }

    /**
     * Inserta un nuevo usuario en la tabla user.
     * @param User $user
     * @return int|null Devuelve el ID insertado o null si falla.
     */
    public function save(object $user): ?int {
        try {
            $sql = "
                INSERT INTO user 
                    (nombre, correo, passwd, rol_id, direccion, foto, token_id)
                VALUES 
                    (:nombre, :correo, :passwd, :rol, :direccion, :foto, :token)
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nombre'     => $user->getNombre(),
                ':correo'     => $user->getEmail(),
                ':passwd'     => $user->getPassword(),
                ':rol'        => $user->getRol(),
                ':direccion'  => $user->getDireccion(),
                ':foto'       => $user->getFoto(),
                ':token'      => $user->getToken()
            ]);

            return (int)$this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Error al guardar usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un usuario.
     */
    public function update(object $user): bool {
        try {
            $sql = "
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

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id'         => $user->getId(),
                ':nombre'     => $user->getNombre(),
                ':correo'     => $user->getEmail(),
                ':passwd'     => $user->getPassword(),
                ':rol'        => $user->getRol(),
                ':direccion'  => $user->getDireccion(),
                ':foto'       => $user->getFoto(),
                ':token'      => $user->getToken()
            ]);

            return true;

        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un usuario por su ID.
     */
    public function delete(int $id): bool {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;

        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca un usuario por email y contraseña (verificación segura).
     */
    public function findUser(string $email, string $pass): ?User {
        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.correo,
                u.passwd,
                u.rol_id,
                u.direccion,
                u.foto,
                u.token_id
            FROM user u
            WHERE u.correo = :email and u.passwd = :passwd
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':passwd', $pass, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new User(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            email: $row['correo'],
            rol: (int)$row['rol_id'],
            direccion: $row['direccion'],
            passwd: $row['passwd'],
            foto: $row['foto'] ?? '',
            token: (int)($row['token_id'] ?? null)
        );
    }
    
    /**
     * correoExiste
     * 
     * Comprueba si el correo ya existe en la base de datos
     *
     * @param  mixed $correo
     * @return bool
     */
    public function correoExiste(string $correo): bool
{
    $sql = "SELECT COUNT(*) FROM user WHERE correo = :correo";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['correo' => $correo]);
    $count = $stmt->fetchColumn();

    return $count > 0;
}
}
?>
