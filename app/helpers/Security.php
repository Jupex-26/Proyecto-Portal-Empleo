<?php
namespace app\helpers;
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
 }
/* generate y validate token */
 ?>