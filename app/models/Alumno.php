<?php

namespace app\models;
use app\helpers\Converter;
class Alumno extends User {
    // --- PROPIEDADES ---
    private array $ciclos = [];
    private string $ap1;
    private string $ap2;
    private string $cv;
    private array $solicitudes = [];
    private ?\DateTime $fechaNacimiento = null;
    private string $descripcion;

    // --- CONSTRUCTOR ---
    /* Se necesita indicar la propiedad a la hora de instanciar el objeto, Ejemplo:
        $alumno=new Alumno(nombre:$nombre,ap1:$ap1)
    */
    public function __construct(
        ?int $id = null,
        string $nombre = '',
        string $correo = '',
        int $rol = 3,
        string $direccion = '',
        string $passwd = '',
        string $foto = '',
        ?int $token = null,
        string $ap1 = '',
        string $ap2 = '',
        array $ciclos = [],
        string $cv = '',
        array $solicitudes = [],
        ?\DateTime $fechaNacimiento = null,
        string $descripcion=''
    ) {
        parent::__construct(
            $id,
            $nombre,
            $correo,
            $rol,
            $direccion,
            $passwd,
            $foto,
            $token
        );

        $this->ap1 = $ap1;
        $this->ap2 = $ap2;
        $this->ciclos = $ciclos;
        $this->cv = $cv;
        $this->solicitudes = $solicitudes;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->descripcion=$descripcion;
    }

    // --- GETTERS ---
    public function getAp1(): string {
        return $this->ap1;
    }

    public function getAp2(): string {
        return $this->ap2;
    }

    public function getCiclos(): array {
        return $this->ciclos;
    }

    public function getCv(): ?string {
        return $this->cv;
    }

    public function getSolicitudes(): array {
        return $this->solicitudes;
    }

    public function getFechaNac(): ?\DateTime {
        return $this->fechaNacimiento;
    }
    public function getDescripcion(): string {
        return $this->descripcion;
    }

    // --- SETTERS ---
    public function setAp1(string $ap1): void {
        $this->ap1 = $ap1;
    }

    public function setAp2(string $ap2): void {
        $this->ap2 = $ap2;
    }

    public function setCiclos(array $ciclos): void {
        $this->ciclos = $ciclos;
    }

    public function setCv(?string $cv): void {
        $this->cv = $cv;
    }

    public function setSolicitudes(array $solicitudes): void {
        $this->solicitudes = $solicitudes;
    }

    public function setFechaNac(?\DateTime $fechaNacimiento): void {
        $this->fechaNacimiento = $fechaNacimiento;
    }
    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    // --- MÉTODOS AUXILIARES ---
    public function addCiclo(object $ciclo): void {
        $this->ciclos[] = $ciclo;
    }

    public function addSolicitud(object $solicitud): void {
        $this->solicitudes[] = $solicitud;
    }
    /**
     * Obtiene la solicitud del alumno para una oferta específica.
     *
     * @param int $oferta_id ID de la oferta.
     * @return Solicitud|null Objeto Solicitud si existe, null si no.
     */
    public function getSolicitudByOfertaId(int $oferta_id): ?Solicitud {
        $solicitudes = array_filter(
            $this->solicitudes, 
            fn($solicitud) => $solicitud->getOfertaId() === $oferta_id
        );
        
        return reset($solicitudes) ?: null;
    }
    /**
     * Elimina una solicitud del array de solicitudes del alumno.
     *
     * @param Solicitud $solicitudAEliminar Objeto Solicitud a eliminar.
     * @return void
     */
    public function deleteSolicitud(Solicitud $solicitudAEliminar): void {
        $indice = array_search($solicitudAEliminar, $this->solicitudes, true);
        if ($indice !== false) {
            unset($this->solicitudes[$indice]);
        }
    }

    // --- SERIALIZACIÓN A JSON ---
    public function toJson(): array {
        $data = [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'correo' => $this->getcorreo(),
            'rol' => $this->getRol(),
            'direccion' => $this->getDireccion(),
            'descripcion' => $this->getDescripcion(),
            'foto' => $this->getFoto(),
            'ap1' => $this->getAp1(),
            'ap2' => $this->getAp2(),
            'ciclos' => Converter::arrayToJson($this->getCiclos()),
            'cv' => $this->getCv(),
            'solicitudes' => Converter::arrayToJson($this->getSolicitudes()),
            'fechaNacimiento' => $this->getFechaNac()?->format('d-m-Y')
        ];

        return $data; // mantiene acentos y caracteres especiales
    }
}
?>
