<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Session;
use app\helpers\Login;
use app\repositories\RepoAlumno;
class PerfilController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->user=Session::readSession('user');
    }
    public function index(){
        if (Login::isLogin()){
            $rol=$this->user->getRol();
            switch($rol){
                case 1:
                    break;
                case 2:
                    echo $this->templates->render('Empresa/FichaEmpresa',['user'=>$this->user]);
                    break;
                case 3:
                    /* Parche Temporal */
                    $repo=new RepoAlumno();
                    $this->user=$repo->findById($this->user->getId());
                    Session::writeSession('user',$this->user);
                    echo $this->templates->render('Alumno/FichaAlumno',['user'=>$this->user]);
                    break;
            }
        }else{
            header('location: ?page=login');
        }
        
        
    }
}
?>