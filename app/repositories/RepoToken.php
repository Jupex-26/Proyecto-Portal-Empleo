<?php
namespace app\repositories;
use app\repositories\DB;
use app\models\Token;
use PDO;
use DateTime;
class RepoToken implements RepoMethods {
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
     * save
     *
     * @param  Token $token
     * @return int|null
     */
    public function save(object $token): ?int {
        $stmt = $this->conn->prepare("INSERT INTO token (codigo, expires_at) VALUES (:codigo, :expires_at)");
        $stmt->bindValue(':codigo', $token->getCodigo());
        $stmt->bindValue(':expires_at', $token->getExpiresAt()->format('Y-m-d H:i:s'));
        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }
        return null;
    }
    
    /**
     * findById
     *
     * @param  int $id
     * @return Token
     */
    public function findById(int $id): ?Token {
        $stmt = $this->conn->prepare("SELECT * FROM token WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Token(
                (int)$row['id'],
                $row['codigo'],
                new DateTime($row['expires_at'])
            );
        }
        return null;
    }    
    /**
     * findAll
     *
     * @return array
     */
    public function findAll(): array {
        $tokens = [];
        $stmt = $this->conn->prepare("SELECT * FROM token");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $tokens[] = new Token(
                (int)$row['id'],
                $row['codigo'],
                new DateTime($row['expires_at'])
            );
        }
        return $tokens;
    }    
    /**
     * update
     *
     * @param  Token $token
     * @return bool
     */
    public function update(object $token): bool {
        $stmt = $this->conn->prepare("UPDATE token SET codigo = :codigo, expires_at = :expires_at WHERE id = :id");
        $stmt->bindValue(':codigo', $token->getCodigo());
        $stmt->bindValue(':expires_at', $token->getExpiresAt()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $token->getId(), PDO::PARAM_INT);
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
            // Elimina referencias en otras tablas si es necesario
            $stmtEmp = $this->conn->prepare("UPDATE oferta SET token_id = NULL WHERE token_id = :id");
            $stmtEmp->execute(['id' => $id]);
            // Elimina el token
            $stmtEmp = $this->conn->prepare("DELETE FROM token WHERE id = :id");
            $stmtEmp->execute(['id' => $id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar token: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca un token por su código
     *
     * @param  string $codigo
     * @return Token|null
     */
    public function findByCodigo(string $codigo): ?Token {
        $stmt = $this->conn->prepare("SELECT * FROM token WHERE codigo = :codigo");
        $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Token(
                (int)$row['id'],
                $row['codigo'],
                new DateTime($row['expires_at'])
            );
        }
        return null;
    }

}