<?php

namespace app\models;

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
        string $email = '',
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
            $email,
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

    // --- SERIALIZACIÓN A JSON ---
    public function toJson(): string {
        $data = [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'email' => $this->getEmail(),
            'rol' => $this->getRol(),
            'direccion' => $this->getDireccion(),
            'foto' => $this->getFoto(),
            'ap1' => $this->getAp1(),
            'ap2' => $this->getAp2(),
            'ciclos' => $this->getCiclos(),
            'cv' => $this->getCv(),
            'solicitudes' => $this->getSolicitudes(),
            'fecha_nacimiento' => $this->getFechaNac()?->format('d-m-Y')
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE); // mantiene acentos y caracteres especiales
    }
}
?>
