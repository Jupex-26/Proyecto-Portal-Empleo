<?php
namespace app\models;
use DateTime;
use app\helpers\Converter;
class Oferta{
    private ?int $id;
    private string $nombre;
    private string $descripcion;
    private int $empresaId;
    private ?DateTime $fechaInicio;
    private ?DateTime $fechaFin;
    private array $solicitudes = [];
    private array $ciclos=[];
    private string $foto;

    public function __construct(?int $id=null, string $nombre='', string $descripcion='', int $empresaId=0, ?DateTime $fechaInicio=null, ?DateTime $fechaFin=null, array $solicitudes=[], array $ciclos=[], $foto=''){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->empresaId = $empresaId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->solicitudes=$solicitudes;
        $this->ciclos=$ciclos;
        $this->foto=$foto;
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

    public function getFechaInicio(): ?DateTime {
        return $this->fechaInicio;
    }

    public function getFechaFin(): ?DateTime {
        return $this->fechaFin;
    }
    public function getSolicitudes(): array {
        return $this->solicitudes;
    }
    public function getCiclos(): array {
        return $this->ciclos;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function setFoto(string $foto): void {
        $this->foto = $foto;
    }
    public function setCiclos(array $ciclos): void {
        $this->ciclos = $ciclos;
    }
    public function addCiclo(Ciclo $ciclo): void {
        $this->ciclos[] = $ciclo;
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
    /**
     * actualizarOferta
     *
     * @param  mixed $postData
     * @param  mixed $oferta
     * @return void
     */
    public function actualizarOferta($postData){
        $this->setNombre($postData['nombre']??$this->getNombre());
        $this->setDescripcion($postData['descripcion']??$this->getDescripcion());
        $this->setFechaInicio(new DateTime($postData['fecha_inicio'])??$this->getFechaInicio());
        $this->setFechaFin(new DateTime($postData['fecha_fin'])??$this->getFechaFin());
        $this->setCiclos(Converter::postToCiclos($postData));
    }    
    /**
     * toJson
     *  Convierte el objeto Oferta a un array asociativo para JSON  
     * @return array
     */
    public function toJson(): array {
    return ([
        'id' => $this->id,
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'empresaId' => $this->empresaId,
        'fechaInicio' => $this->fechaInicio ? $this->fechaInicio->format('Y-m-d') : null,
        'fechaFin' => $this->fechaFin ? $this->fechaFin->format('Y-m-d') : null,
        'solicitudes' => Converter::arrayToJson($this->solicitudes),
        'ciclos' => Converter::arrayToJson($this->ciclos),
        'foto' => $this->foto
    ]);
}
}
?>