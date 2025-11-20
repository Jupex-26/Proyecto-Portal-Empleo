<?php
namespace app\controllers;
use app\views\formulario;
use app\repositories\RepoEmpresa;
use League\Plates\Engine;
use app\helpers\Generator;
use app\helpers\Login;
use app\helpers\Session;
class HomeController {
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->user=Session::readSession('user'); 
    }
    public function index() {
        $repo=new RepoEmpresa();
        $empresas=$repo->findAllLimitWActive(0,5,true);
        if (Login::isLogin()&&$this->user->getRol()==1){
            echo $this->templates->render('Admin/AdminHome',['user'=>$this->user,'page'=>$_GET['page']]);
        }else{
            echo $this->templates->render('home', ['user'=>$this->user,'empresas'=>$empresas]);
        }  
    }
    /* Hacer top 5 de empresas y enseñarlas,
        Para alumno enseñarle top 5 empresas según su familia
        Para empresas enseñarle top 5 empresas según la familia en sus ofertas  */
    
}

?>