<?php
namespace app\models;
use DateTime;
class Oferta{
    private ?int $id;
    private string $nombre;
    private string $descripcion;
    private int $empresaId;
    private ?DateTime $fechaInicio;
    private ?DateTime $fechaFin;
    private array $solicitudes = [];

    public function __construct(?int $id=null, string $nombre='', string $descripcion='', int $empresaId=0, ?DateTime $fechaInicio=null, ?DateTime $fechaFin=null, array $solicitudes=[]){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->empresaId = $empresaId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getEmpresaId(): int {
        return $this->empresaId;
    }

    public function getFechaInicio(): DateTime {
        return $this->fechaInicio;
    }

    public function getFechaFin(): DateTime {
        return $this->fechaFin;
    }
    public function getSolicitudes(): array {
        return $this->solicitudes;
    }
    public function setSolicitudes(array $solicitudes): void {
        $this->solicitudes = $solicitudes;
    }
    public function addSolicitud(Solicitud $solicitud): void {
        $this->solicitudes[] = $solicitud;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }  
    public function setEmpresaId(int $empresaId): void {
        $this->empresaId = $empresaId;
    }
    public function setFechaInicio(DateTime $fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }
    public function setFechaFin(DateTime $fechaFin): void {
        $this->fechaFin = $fechaFin;
    }
}
?>