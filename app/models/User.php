<?php
namespace app\models;
class User{
    private ?int $id;
    private string $nombre;
    private string $email;
    private int $rol;
    private string $direccion;
    private string $passwd;
    private string $foto;
    private ?int $token;

    /**
        * Se necesita indicar la propiedad a la hora de instanciar el objeto, Ejemplo:
        * $user=new User(nombre:$nombre,email:$email)
    */
    public function __construct(
        ?int $id=null,
        string $nombre='',
        string $email='',
        int $rol=1,
        string $direccion='',
        string $passwd='',
        string $foto = '',
        ?int $token = null,
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rol = $rol;
        $this->direccion = $direccion;
        $this->passwd = $passwd;
        $this->foto = $foto;
        $this->token = $token;
    }

    // --- GETTERS ---
    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRol(): int {
        return $this->rol;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function getPassword(): string {
        return $this->passwd;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function getToken(): ?int {
        return $this->token;
    }



    // --- SETTERS ---
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setRol(int $rol): void {
        $this->rol = $rol;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function setPassword(string $passwd): void {
        $this->passwd = $passwd;
    }

    public function setFoto(string $foto): void {
        $this->foto = $foto;
    }

    public function setToken(int $token): void {
        $this->token = $token;
    }

}
?>