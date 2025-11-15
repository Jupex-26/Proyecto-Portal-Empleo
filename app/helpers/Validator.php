<?php
namespace app\helpers;
/**
 * Clase Validator
 *
 * Helper para validar datos de formularios y acumular errores.
 */
class Validator{
    /**
     * Array asociativo que contiene errores por campo.
     *
     * @var array<string,string>
     */
    private $errores;
    private $aprobado;

    /**
     * Constructor del Validator.
     *
     * Inicializa el array de errores.
     */
    public function __construct(){
        $this->errores = array();
        $this->aprobado='';
    }

    /**
     * Valida que un campo esté presente en el array que se le pasa como parámetro.
     *
     * Si el campo no está definido en el array añade un mensaje de error
     * al array de errores con la clave del campo.
     *
     * @param string $campo Nombre del campo a validar
     * @return void
     */
    public function required($campo, $array){
        if (!isset($array[$campo]) || empty($array[$campo])){
            $this->errores[$campo] = "El campo $campo no puede estar vacío";
        }
    }

    
    /**
     * Valida que el usuario ha enviado un fichero, sino da error
     * 
     * Si el campo no está definido en el array añade un mensaje de error
     * al array de errores con la clave del campo.
     * requiredFile
     *
     * @param  mixed $campo
     * @return void
     */
    public function requiredFile($campo){
    if (
        !isset($_FILES[$campo]) ||                     // no se envió el campo
        $_FILES[$campo]['error'] !== UPLOAD_ERR_OK ||  // ocurrió un error
        empty($_FILES[$campo]['name'])                 // no seleccionaron archivo
    ){
        $this->errores['foto'] = "Debe subir un archivo en el campo $campo";
    }
}
    /**
* Comprueba si el campo es un Correo válido (versión estricta con DNS)
* @param string $nombreCampo El nombre del campo (para la clave del error).
* @param string $valor El valor a validar (el Correo).
* @return boolean
*/
public function validarCorreo (string $nombreCampo, array $array): bool {
    // 1. Verificación de valor vacío
    if (empty($array[$nombreCampo])) {
        $this->errores[$nombreCampo] = "El Correo no debe estar vacío";
        return false;
    }

    $email = $array[$nombreCampo];

    // 2. Primero, verifica el formato estándar
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->errores[$nombreCampo] = "El Correo tiene un formato incorrecto.";
        return false;
    }

    // 3. Verificación de DNS (ESTRICTO)
    list(, $dominio) = explode('@', $email);
    if (!checkdnsrr($dominio, 'MX') && !checkdnsrr($dominio, 'A')) {
        $this->errores[$nombreCampo] = "El dominio del Correo no parece existir.";
        return false;
    }

    // 4. Email es válido y el dominio existe
    return true;
}
    
       
    /**
     * validarNombre
     * Comprueba si es un nombre/apellido valido pasandole además el array en el que estará 
     * (Por ejemplo si se envía por $_POST)
     * @param  mixed $campo
     * @param  mixed $array
     * @return bool
     */
    public function validarNombre(string $campo, array $array): bool {
        $valor = $array[$campo] ?? '';
        $nombre = trim((string)$valor);

        // Opcional: solo letras y espacios
        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u", $nombre)) {
            $this->errores[$campo] = "El nombre solo puede contener letras y espacios";
            return false;
        }

        return true;
    }
    
    /**
     * ImprimirError
     * Imprime el error del campo si existe
     *
     * @param  mixed $campo
     * @return void
     */
    public function imprimirError($campo){
    return isset ($this->errores[$campo])?'<span class="error">'.$this->errores [$campo]. '</span>':'';
    }

    /**
     * Imprimir
     * Imprime el aprobado si existe
     *
     * @param  mixed $campo
     * @return void
     */
    public function imprimir(){
    return isset ($this->aprobado)?'<span class="aprobado">'.$this->aprobado. '</span>':'';
    }

    
    /**
     * validarTelefono
     *
     * @param  mixed $campo
     * @param  mixed $array
     * @return bool
     */
    public function validarTelefono(string $campo, array $array): bool {
    $telefono = trim($array[$campo]);

    // Verifica que no esté vacío
    if (empty($telefono)) {
        $this->errores[$campo] = "El número de teléfono no puede estar vacío";
        return false;
    }

    // Validación: exactamente 9 dígitos, sin espacios ni símbolos
    if (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $this->errores[$campo] = "El número de teléfono debe contener exactamente 9 dígitos";
        return false;
    }

    return true;
}

    
    /**
     * ValidacionPasada
     *
     * @return void
     */
    public function validacionPasada(){
        if (count ($this->errores) !=0){
            return false;
        }
        return true;
    }    

    
    /**
     * mensajeExito
     *
     * @param  mixed $mensaje
     * @return void
     */
    public function mensajeExito($mensaje='Se ha guardado perfectamente'){
        $this->aprobado=$mensaje;
    }
    
    /**
     * isImagen
     * Esta función comprueba si se ha enviado una imagn
     *
     * @param  mixed $rutaTemporal
     * @return void
     */
    public function isImagen($rutaTemporal) {
        if (!str_starts_with(mime_content_type($rutaTemporal), 'image/')) {
            $this->errores['imagen'] = "El archivo debe ser una imagen válida";
            return false;
        }

        return true;
    }
    
    /**
     * insertarError 
     *
     * @param  mixed $campo
     * @param  mixed $mensaje
     * @return void
     */
    public function insertarError($campo,$mensaje) {
        $this->errores[$campo] = $mensaje;
        return true;
    }

    /**
     * remove
     *
     * Elimina un error del array de errores por su clave.
     *
     * @param string $campo La clave del error a eliminar.
     * @return void
     */
    public function remove($campo){
        unset($this->errores[$campo]);
    }

}

?>