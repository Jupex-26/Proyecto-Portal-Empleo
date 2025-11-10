<?php
namespace app\helpers;
use app\helpers\Session;

class Login{

    public static function login($user){
        Session::writeSession('user',$user);
    
    }

    public static function logout(){
        unset($_SESSION);
        Session::closeSession();
    }

    public static function isLogin(){
        return (isset($_SESSION['user']));
    }
}

?>