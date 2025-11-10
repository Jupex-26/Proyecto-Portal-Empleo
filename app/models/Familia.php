<?php
namespace app\models;
class Familia{
    private ?int $id;
    private string $nombre;
    private array $ciclos = [];
    public function __construct(?int $id=null, string $nombre='', array $ciclos=[]){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->ciclos = $ciclos;
    }
    public function getId(): ?int {
        return $this->id;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getCiclos(): array {
        return $this->ciclos;
    }
    public function setCiclos(array $ciclos): void {
        $this->ciclos = $ciclos;
    }
    public function addCiclo(Ciclo $ciclo): void {
        $this->ciclos[] = $ciclo;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function toJson(): string {
        $data = [
            'id' => $this->getId(),
            'nombre' => $this->getNombre()
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE); // mantiene acentos y caracteres especiales
    }
}
?>