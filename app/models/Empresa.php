<?php
namespace app\models;
class Empresa extends User{
    private string $correoContacto;
    private int $telefonoContacto;
    private bool $activo;
    private string $descripcion;
    private array $ofertas = [];


    /**
        * Se necesita indicar la propiedad a la hora de instanciar el objeto, Ejemplo:
        * $empresa=new Empresa(nombre:$nombre,correo:$correo)
    */
    public function __construct(
        ?int $id=null,
        string $nombre='',
        string $correo='',
        int $rol=2,
        string $direccion='',
        string $passwd='',
        string $foto = '',
        ?int $token = null,
        string $localidad = '',
        string $provincia = '',
        string $correoContacto='',
        int $telefonoContacto=0,
        bool $activo = false,
        string $descripcion = '',
        array $ofertas = []
    ) {
        parent::__construct(
            $id,
            $nombre,
            $correo,
            $rol,
            $direccion,
            $passwd,
            $foto,
            $token,
            $localidad,
            $provincia
        );

        $this->correoContacto = $correoContacto;
        $this->telefonoContacto = $telefonoContacto;
        $this->activo = $activo;
        $this->descripcion = $descripcion;
        $this->ofertas = $ofertas;
    }

    // --- GETTERS ---
    public function getCorreoContacto(): string {
        return $this->correoContacto;
    }

    public function getTelefonoContacto(): ?int {
        return $this->telefonoContacto;
    }

    public function isActivo(): bool {
        return $this->activo;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    /** @return array */
    public function getOfertas(): array {
        return $this->ofertas;
    }

    // --- SETTERS ---
    public function setCorreoContacto(string $correoContacto): void {
        $this->correoContacto = $correoContacto;
    }

    public function setTelefonoContacto(int $telefonoContacto): void {
        $this->telefonoContacto = $telefonoContacto;
    }

    public function setActivo(bool $activo): void {
        $this->activo = $activo;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setOfertas(array $ofertas): void {
        $this->ofertas = $ofertas;
    }

    // --- MÉTODOS AUXILIARES ---
    public function addOferta(Oferta $oferta): void {
        $this->ofertas[] = $oferta;
    }
}
?>