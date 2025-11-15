<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Validator;
use app\helpers\OfertaValidator;
use app\helpers\Converter;
use app\models\Empresa;
use app\models\Oferta;
use app\repositories\RepoOferta;
use app\repositories\RepoCicloOferta;
use DateTime;
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
            $postAccion=$_POST['accion']??'';
            if ($accion=='newOffer'){
                $this->manejarOffer();
            }else if($postAccion=='editar'){
                var_dump($_POST);
                var_dump($this->user);
            }else if($postAccion=='eliminar'){
                var_dump($_POST);
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
        $validator=new Validator();
        $postData=filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $oferta=new Oferta();
        if (isset($postData['accion']) && $postData['accion']=='crear'){
            OfertaValidator::validarOferta($validator,$postData);
            $this->actualizarOferta($postData,$oferta);
            if($validator->validacionPasada()){
                $oferta->setEmpresaId($this->user->getId());
                $repo=new RepoOferta();
                $id=$repo->save($oferta);
                $oferta->setId($id);
                $repo=new RepoCicloOferta();
                $repo->saveMasivo($id,$oferta->getCiclos());
                $this->user->addOferta($oferta);
                header('location: ?page=oferta');
                exit;
            }
            
        }
        echo $this->templates->render('Ofertas/FormOferta',['empresa'=>$this->user, 'validator'=>$validator, 'oferta'=>$oferta]);
    }


        
    /**
     * actualizarOferta
     *
     * @param  mixed $postData
     * @param  mixed $oferta
     * @return void
     */
    private function actualizarOferta($postData,$oferta){
        $oferta->setNombre($postData['nombre']??$oferta->getNombre());
        $oferta->setDescripcion($postData['descripcion']??$oferta->getDescripcion());
        $oferta->setFechaInicio(new DateTime($postData['fecha_inicio'])??$oferta->getFechaInicio());
        $oferta->setFechaFin(new DateTime($postData['fecha_fin'])??$oferta->getFechaFin());
        $oferta->setCiclos(Converter::postToCiclos($postData)??$oferta->getCiclos());
    }

}
?>