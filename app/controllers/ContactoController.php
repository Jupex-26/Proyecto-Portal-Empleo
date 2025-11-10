<?php
namespace app\controllers;
use League\Plates\Engine;

class ContactoController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index(){
        
        echo $this->templates->render('contacto');
        
    }
}
?>