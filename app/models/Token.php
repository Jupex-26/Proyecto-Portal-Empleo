<?php
namespace app\models;
use DateTime;
class Token {
    private ?int $id;
    private string $codigo;
    private DateTime $expiresAt;

    public function __construct(?int $id, string $codigo, DateTime $expiresAt) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->expiresAt = $expiresAt;
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function getCodigo(): string {
        return $this->codigo;
    }

    public function getExpiresAt(): DateTime {
        return $this->expiresAt;
    }  
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setCodigo(string $codigo): void {
        $this->codigo = $codigo;
    }
    public function setExpiresAt(DateTime $expiresAt): void {
        $this->expiresAt = $expiresAt;
    }
}
?>
