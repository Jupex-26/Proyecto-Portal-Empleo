<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Validator;
use app\models\Empresa;
class OfertaController{
    private $templates;
    private $user;
    private $page;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->page=$_GET['page'];
    }    
    /**
     * index
     *
     * @return void
     */
    public function index(){
        if (Login::isLogin() && $this->user->getRol()=='2'){
            $accion=$_GET['accion']??'';
            if ($accion=='newOffer'){
                $this->manejarOffer();
            }else{
                echo $this->templates->render('Ofertas/Ofertas', ['user'=>$this->user]);
            }
            
        }else{
            header("location:?page=login");
        }
    }
    
    /**
     * manejarOffer
     *
     * @return void
     */
    private function manejarOffer(){
        $postData=filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $validator=new Validator();
        if ($postData['accion']){
            
        }
        echo $this->templates->render('Ofertas/NewOferta',['empresa'=>$this->user]);
    }

}
?>