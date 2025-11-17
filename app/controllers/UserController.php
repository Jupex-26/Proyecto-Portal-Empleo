<?php
namespace app\controllers;
use League\Plates\Engine;
use app\repositories\RepoAlumno;
use app\helpers\Login;
use app\helpers\Session;
class UserController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index(){
        if ((Login::isLogin() && !empty($this->user) && $this->user->getRol()==1)){
            $repo=new RepoAlumno();
            $users=$repo->findAll();
            print $this->templates->render('Admin/AdminAlumnos',['page'=>$_GET['page'], 'user'=>$this->user]); 
        }else{
            header('location:?page=home');
        }
        

    } 
}
?>