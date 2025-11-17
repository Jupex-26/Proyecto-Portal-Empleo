<?php
namespace app\helpers;
use app\repositories\RepoUser;
use app\repositories\RepoAlumno;
use app\repositories\RepoEmpresa;
use app\models\User;
use app\models\Alumno;
use app\models\Empresa;
class Security{
    /**
     * Verifica si una contraseña en texto plano coincide con un hash almacenado.
     *
     * Esta función utiliza password_verify() de PHP para comprobar si la
     * contraseña proporcionada coincide con el hash seguro generado previamente.
     * Es útil para autenticación de usuarios.
     *
     * @param string $passwd La contraseña en texto plano que se desea verificar.
     * @param string $passwd_hash El hash seguro de la contraseña almacenado en la base de datos.
     * @return bool Retorna true si la contraseña coincide con el hash, false en caso contrario.
     */
    public static function validatePasswd($passwd,$passwd_hash){
        return password_verify($passwd,$passwd_hash);
    }

    /**
     * Convierte una contraseña en texto plano a un hash seguro.
     *
     * Esta función utiliza password_hash() de PHP para generar un hash
     * seguro que se puede almacenar en la base de datos. Se recomienda
     * usar este hash para verificar contraseñas en lugar de almacenar
     * la contraseña en texto plano.
     *
     * @param string $pass La contraseña en texto plano que se desea hashear.
     * @return string Retorna el hash seguro de la contraseña.
     */
    public static function passwdToHash($pass){
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
    * Obtiene un objeto de usuario completo según su rol.
    * 
    * Si el usuario es de tipo empresa o alumno, busca sus datos detallados
    * en el repositorio correspondiente. Si es un administrador, se devuelve tal cual.
    * 
    * @param object $user Instancia básica del usuario (con al menos ID y rol)
    * @return object|null Devuelve el usuario completo según su tipo, o null si el rol no es válido.
    */
    public static function getUser($user) {
        // Determina el tipo de usuario según su rol numérico.
        switch($user->getRol()) {
            case 1:
                return $user;
                break;
            case 2:
                $repo = new RepoEmpresa();
                break;
            case 3:
                $repo = new RepoAlumno();
                break;
            default: 
                return null;
        }
        $user = $repo->findById($user->getId());
        if ($user instanceof Empresa && !$user->isActivo()){
            return null;
        }
        return $user;
    }

    
    /**
     * Genera un token aleatorio seguro.
     *
     * @param int $length Longitud en bytes antes de convertir a hexadecimal (por defecto 20)
     * @return string Token generado
     */
    public static function generateToken($length = 20)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Valida un token.
     *
     * Dependiendo de tu implementación, esto puede ser:
     *  - Comparar con un token almacenado en base de datos
     *  - Decodificar JWT
     * 
     * @param string $token Token a validar
     * @param string|null $storedToken Token que se tiene guardado (opcional)
     * @return bool True si el token es válido, false si no
     */
    public static function validateToken($token, $storedToken = null)
    {
        if ($storedToken !== null) {
            return hash_equals($storedToken, $token);
        }

        return !empty($token); // validación mínima si no hay token guardado
    }
 }
 ?>