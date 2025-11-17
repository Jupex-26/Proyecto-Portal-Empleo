<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Session;
class ContactoController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->user=Session::readSession('user');
    }
    public function index(){
        
        echo $this->templates->render('contacto',['user'=>$this->user]);
        
    }
}
?>