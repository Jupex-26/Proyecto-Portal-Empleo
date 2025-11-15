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
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index() {
         
         
        if (Login::isLogin()){
            $rol=$this->user->getRol();
            switch($rol){
                case 1:
                    echo $this->templates->render('Admin/AdminHome',['page'=>$_GET['page']??'home']);
                    break;
                case 2:
                    echo $this->templates->render('EmpresaHome');
                    break;
                case 3:
                    echo $this->templates->render('AlumnoHome');
                    break;
            }
        }else{
            echo $this->templates->render('home');
        }  
    }
    /* Hacer top 5 de empresas y enseñarlas,
        Para alumno enseñarle top 5 empresas según su familia
        Para empresas enseñarle top 5 empresas según la familia en sus ofertas  */
    
}

?>