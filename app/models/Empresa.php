<?php
namespace app\models;
use app\helpers\Validator;
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
        array_unshift($this->ofertas, $oferta);
    }
    public function deleteOferta(Oferta $oferta):void{
        $indice = array_search($oferta, $this->ofertas, true); /* Busca la referencia en memoria al poner el true, si uso falso elimina las que tengan las mismas propiedades, por lo que si se actualiza una oferta no pasará */
        if ($indice !== false) {
            unset($this->ofertas[$indice]);
        }
    }


    /**
     * Actualiza los datos de una empresa con los valores recibidos.
     * 
     * @param Empresa $empresa Objeto empresa a actualizar
     * @param array $data Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    public function actualizarEmpresa($data, $fileData) {
        $foto_url =$this->guardarFoto($fileData, $data['foto']);
        $this->setNombre($data['nombre'] ?? $this->getNombre());
        $this->setCorreo($data['correo'] ?? $this->getCorreo());
        $this->setCorreoContacto($data['correo_contacto'] ?? $this->getCorreoContacto());
        $this->setTelefonoContacto($data['telefono_contacto'] ?? $this->getTelefonoContacto());
        $this->setDireccion($data['direccion'] ?? $this->getDireccion());
        $this->setDescripcion($data['descripcion'] ?? $this->getDescripcion());
        $this->setFoto($foto_url);
        $this->setPassword($data['passwd']==''?$this->getPassword():$data['passwd']);
        
    }

    // =====================================================
    // MÉTODOS DE UTILIDAD
    // =====================================================

    /**
     * Guarda la imagen subida en la carpeta de assets.
     * 
     * @param Empresa $empresa Empresa relacionada con la imagen
     * @param array|null $fileData Datos del archivo subido
     * @return string Nombre final del archivo guardado
     */
    private function guardarFoto($fileData,$foto) {
        $directorio = "./assets/img/";
        $validator = new Validator();
        $nombreFinal = $foto;

        if (isset($fileData['error']) &&
        $fileData['error'] === UPLOAD_ERR_OK && $validator->isImagen($fileData['tmp_name']) ) {
            $extension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
            $nombreFinal = "empresa_" . $this->getId() . "." . $extension;
            move_uploaded_file($fileData['tmp_name'], $directorio . $nombreFinal);
        }

        return $nombreFinal;
    }

    // --- SERIALIZACIÓN A JSON ---
public function toJson(): string {
    $data = [
        'id' => $this->getId(),
        'nombre' => $this->getNombre(),
        'correo' => $this->getCorreo(),
        'rol' => $this->getRol(),
        'direccion' => $this->getDireccion(),
        'foto' => $this->getFoto(),
        'token' => $this->getToken(),
        'localidad' => $this->getLocalidad(),
        'provincia' => $this->getProvincia(),
        'correoContacto' => $this->getCorreoContacto(),
        'telefonoContacto' => $this->getTelefonoContacto(),
        'activo' => $this->isActivo(),
        'descripcion' => $this->getDescripcion(),
        'ofertas' => $this->getOfertas()
    ];

    return $data;
}

}
?>