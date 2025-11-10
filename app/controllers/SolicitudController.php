<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Login;
use app\helpers\Session;

class SolicitudController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index(){
        if (Login::isLogin()){
            echo "Esto es un index";
        }else{
            header("location:?page=login");
        }
    }
}

?>