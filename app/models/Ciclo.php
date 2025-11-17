<?php
namespace app\models;
use app\models\NivelEnum;
/**
 * Ciclo
 */
class Ciclo{
    private ?int $id;
    private ?NivelEnum $nivel;
    private string $nombre;
    private int $familiaId;    
    /**
     * __construct
     *
     * @param  mixed $id
     * @param  mixed $nivel
     * @param  mixed $nombre
     * @param  mixed $familiaId
     * @return void
     */
    public function __construct(?int $id=null,NivelEnum $nivel=null, string $nombre='', int $familiaId=0){
        $this->id = $id;
        $this->nivel = $nivel;
        $this->nombre = $nombre;
        $this->familiaId = $familiaId;
    }
    public function getId(): ?int {
        return $this->id;
    }
    public function getNivel(): ?NivelEnum {
        return $this->nivel;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getFamiliaId(): int {
        return $this->familiaId;
    }
    public function setNivel(NivelEnum $nivel): void {
        $this->nivel = $nivel;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setFamiliaId(int $familiaId): void {
        $this->familiaId = $familiaId;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function toJson(): array {
        $data = [
            'id' => $this->getId(),
            'nombre' => $this->getNombre()
        ];

        return $data; 
    }
}
?>