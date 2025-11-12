<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\User;
use app\models\Alumno;
use PDO;
class RepoAlumno implements RepoMethods {
    private $conn;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->conn = DB::getConnection();
    }
    /**
     * Devuelve todos los alumnos como array de objetos Alumno.
     * @return Alumno[]
     */
    public function findAll(): array {
        $alumnos = [];

        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.correo,
                u.rol_id,
                u.direccion,
                u.foto,
                u.token_id,
                a.ap1,
                a.ap2,
                a.cv,
                a.fecha_nacimiento,
                a.descripcion
            FROM user u
            INNER JOIN alumno a ON u.id = a.id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $alumnos[] = new Alumno(
                id: (int)$row['id'],
                nombre: $row['nombre'],
                correo: $row['correo'],
                rol: (int)$row['rol_id'],
                direccion: $row['direccion'],
                foto: $row['foto'] ?? '',
                token: (int)($row['token_id'] ?? null),
                ap1: $row['ap1'] ?? '',
                ap2: $row['ap2'] ?? '',
                ciclos: [],          
                cv: $row['cv'] ?? '',
                solicitudes: [],     
                fechaNacimiento: !empty($row['fecha_nacimiento'])
                                ? new \DateTime($row['fecha_nacimiento'])
                                : null,
                descripcion:$row['descripcion']      
            );
        }

        return $alumnos;
    }
     /**
     * Encuentra un alumno por su ID.
     * @param int $id
     * @return Alumno|null
     */
    public function findById(int $id): ?Alumno {
        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.correo,
                u.rol_id,
                u.direccion,
                u.foto,
                u.token_id,
                a.ap1,
                a.ap2,
                a.cv,
                a.fecha_nacimiento,
                a.descripcion
            FROM user u
            INNER JOIN alumno a ON u.id = a.id
            WHERE u.id = :id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Alumno(
            id: (int)$row['id'],
            nombre: $row['nombre'],
            correo: $row['correo'],
            rol: (int)$row['rol_id'],
            direccion: $row['direccion'],
            foto: $row['foto'] ?? '',
            token: (int)($row['token_id'] ?? null),
            ap1: $row['ap1'] ?? '',
            ap2: $row['ap2'] ?? '',
            ciclos: [],        
            cv: $row['cv'] ?? '',
            solicitudes: [],    
            fechaNacimiento: !empty($row['fecha_nacimiento'])
                                ? new \DateTime($row['fecha_nacimiento'])
                                : null,
            descripcion:$row['descripcion']     
        );
    }
    /**
     * Inserta un nuevo alumno en user + alumno.
     */
    public function save(object $alumno): ?int {
        try {
            $this->conn->beginTransaction();

            // Insertar en tabla user
            $sqlUser = "
                INSERT INTO user 
                    (nombre, correo, passwd, rol_id, direccion, foto, token_id)
                VALUES 
                    (:nombre, :correo, :passwd, :rol, :direccion, :foto, :token)
            ";

            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([
                ':nombre'     => $alumno->getNombre(),
                ':correo'     => $alumno->getCorreo(),
                ':passwd'     => $alumno->getPassword(),
                ':rol'        => $alumno->getRol(),
                ':direccion'  => $alumno->getDireccion(),
                ':foto'       => $alumno->getFoto(),
                ':token'      => $alumno->getToken()
            ]);

            $id = (int)$this->conn->lastInsertId();

            // Insertar en tabla alumno
            $sqlAlumno = "INSERT INTO alumno (id, ap1, ap2, cv, fecha_nacimiento, descripcion) VALUES (:id, :ap1, :ap2, :cv, :fecha, :descripcion)";
            $stmtAlumno = $this->conn->prepare($sqlAlumno);
            $stmtAlumno->execute([
                ':id' => $id,
                ':ap1' => $alumno->getAp1(),
                ':ap2' => $alumno->getAp2(),
                ':cv' => $alumno->getCv(),
                ':fecha' => $alumno->getFechaNac()->format('Y-m-d'),
                ':descripcion'=>$alumno->getDescripcion()
            ]);

            $this->conn->commit();
            return $id;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al guardar alumno: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un alumno (en ambas tablas).
     */
    public function update(object $alumno): bool {
        try {
            $this->conn->beginTransaction();

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
                ':id'         => $alumno->getId(),
                ':nombre'     => $alumno->getNombre(),
                ':correo'     => $alumno->getCorreo(),
                ':passwd'   => $alumno->getPassword(),
                ':rol'        => $alumno->getRol(),
                ':direccion'  => $alumno->getDireccion(),
                ':foto'       => $alumno->getFoto(),
                ':token'      => $alumno->getToken()
            ]);

            $sqlAlumno = "UPDATE alumno SET ap1 = :ap1, ap2 = :ap2, cv = :cv, fecha_nacimiento = :fecha, descripcion = :descripcion WHERE id = :id";
            $stmtAlumno = $this->conn->prepare($sqlAlumno);
            $stmtAlumno->execute([
                ':id' => $alumno->getId(),
                ':ap1' => $alumno->getAp1(),
                ':ap2' => $alumno->getAp2(),
                ':cv' => $alumno->getCv(),
                ':fecha' => $alumno->getFechaNac()->format('Y-m-d'),
                ':descripcion'=>$alumno->getDescripcion()
            ]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar alumno: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un alumno (de alumno + user).
     */
    public function delete(int $id): bool {
        try {
            $this->conn->beginTransaction();
            // Elimina primero las solicitudes y ciclos asociados (por FK)
            $stmtUser = $this->conn->prepare("DELETE FROM solicitud WHERE alumno_id = :id");
            $stmtUser->execute([':id' => $id]);
            // Elimina los ciclos asociados
            $stmtUser = $this->conn->prepare("DELETE FROM alum_cursado_ciclo WHERE alumno_id = :id");
            $stmtUser->execute([':id' => $id]);
            // Elimina primero de alumno (por FK)
            $stmtUser = $this->conn->prepare("DELETE FROM alumno WHERE id = :id");
            $stmtUser->execute([':id' => $id]);
            // Finalmente elimina de user
            $stmtUser = $this->conn->prepare("DELETE FROM user WHERE id = :id");
            $stmtUser->execute([':id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar alumno: " . $e->getMessage());
            return false;
        }
    }

    public function findUser(string $correo,string $pass){
        $sql = "
        SELECT 
            u.id,
            u.nombre,
            u.correo,
            u.passwd,
            u.rol_id,
            u.direccion,
            u.foto,
            u.token_id,
            a.ap1,
            a.ap2,
            a.cv,
            a.fecha_nacimiento,
            a.descripcion
        FROM user u
        INNER JOIN alumno a ON u.id = a.id
        WHERE u.correo = :correo
          AND u.passwd = :pass
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }

    return new Alumno(
        id: (int)$row['id'],
        nombre: $row['nombre'],
        correo: $row['correo'],
        rol: (int)$row['rol_id'],
        direccion: $row['direccion'],
        passwd: $row['passwd'],
        foto: $row['foto'] ?? '',
        token: (int)($row['token_id'] ?? null),
        ap1: $row['ap1'] ?? '',
        ap2: $row['ap2'] ?? '',
        ciclos: [],        
        cv: $row['cv'] ?? '',
        solicitudes: [],    
        fechaNacimiento: !empty($row['fecha_nacimiento'])
                            ? new \DateTime($row['fecha_nacimiento'])
                            : null,
        descripcion: $row['descripcion']
    );
    }
}
?>