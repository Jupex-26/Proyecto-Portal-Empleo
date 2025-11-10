<?php
namespace app\helpers;
/* Métodos static */
class Session{
    public static function openSession(){
        session_start();
    }
    public static function closeSession(){
        session_destroy();
    }
    public static function readSession($clave){
        if (self::exists($clave)){
            return $_SESSION[$clave];
        }else{
            return false;
        }
    }
    public static function exists($clave){
        return isset($_SESSION[$clave]);
    }
    public static function writeSession($clave,$valor){
        $_SESSION[$clave]=$valor;
    }
}

 ?>