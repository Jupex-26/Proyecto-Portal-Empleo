<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Login;
use app\helpers\Session;
class StatController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index(){
         if (Login::isLogin()){
            /* TO DO */
            echo $this->templates->render('Admin/AdminStat',['page'=>$_GET['page']]); 
        }else{
            header('location:?page=home');
        }
    }
}

?>