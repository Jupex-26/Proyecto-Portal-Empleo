<?php
namespace app\models;

class Rol {
    private int $id;
    private string $nombre;

    public function __construct(int $id=0, string $nombre="") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function isAlumno(Alumno $alumno): bool {
        return $alumno->getRolId() == $this->id && $this->nombre == 'ALUMNO';
    }
    public function isEmpresa(Empresa $empresa): bool {
        return $empresa->getRolId() == $this->id && $this->nombre == 'EMPRESA';
    }
}