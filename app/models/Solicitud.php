<?php
namespace app\models;
use app\models\EstadoSolicitud;
class Solicitud{
    private ?int $id;
    private int $alumnoId;
    private int $ofertaId;
    private EstadoSolicitud $estado;

    public function __construct(?int $id=null, int $alumnoId=0, int $ofertaId=0, EstadoSolicitud $estado=EstadoSolicitud::PROCESO){
        $this->id = $id;
        $this->alumnoId = $alumnoId;
        $this->ofertaId = $ofertaId;
        $this->estado = $estado;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getAlumnoId(): int {
        return $this->alumnoId;
    }

    public function getOfertaId(): int {
        return $this->ofertaId;
    }


    public function getEstado(): EstadoSolicitud {
        return $this->estado;
    }

    public function setEstado(EstadoSolicitud $estado): void {
        $this->estado = $estado;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setAlumnoId(int $alumnoId): void {
        $this->alumnoId = $alumnoId;
    }
    public function setOfertaId(int $ofertaId): void {
        $this->ofertaId = $ofertaId;
    }
}