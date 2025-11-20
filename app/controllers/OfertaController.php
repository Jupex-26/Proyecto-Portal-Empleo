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
use app\models\Solicitud;
use app\models\Ciclo;
use app\repositories\RepoOferta;
use app\repositories\RepoCicloOferta;
use app\repositories\RepoEmpresa;
use app\repositories\RepoSolicitud;
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
            $getData = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
            $accion=$getData['accion']??'';
            switch($accion){
                case 'newOffer':
                    $this->manejarOffer($accion);
                    break;
                case 'editar':
                    $this->editarOffer((int)$getData['id'],$accion);
                    break;
                case 'eliminar':
                    $this->eliminarOffer((int)$getData['id'],$accion);
                    break;
                case 'filtro':
                    $this->manejarFiltro($getData);
                    break;
                default: echo $this->templates->render('Ofertas/Ofertas', ['user'=>$this->user]);
            }
        }else if (Login::isLogin() && $this->user->getRol()==3){
            $repo=new RepoEmpresa();
            $repo=new RepoOferta();
            $getData = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
            $ofertas=$repo->findByCiclos($this->user->getCiclos());
            $accion=$getData['accion']??'';
            switch($accion){
                case 'renunciar':
                    $this->manejarRenunciar($getData);
                        break;
                case 'postular':
                    $this->manejarPostular($getData);
                        break;
                case 'filtro':
                    $this->filtroCiclos($getData,$ofertas);
                        break;
                default:
                 echo $this->templates->render('Ofertas/Ofertas', ['user'=>$this->user,'ofertas'=>$ofertas]);
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
    private function manejarOffer(string $accion){
        $validator=new Validator();
        $postData=filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $oferta=new Oferta();
        
        if (isset($postData['accion']) && $postData['accion']=='crear'){
            OfertaValidator::validarOferta($validator,$postData);
            $oferta->actualizarOferta($postData);
            if($validator->validacionPasada()){
                $oferta->setEmpresaId($this->user->getId());
                $repo=new RepoOferta();
                $id=$repo->save($oferta);
                $oferta->setId($id);
                $repo=new RepoCicloOferta();
                $repo->saveMasivo($id,$oferta->getCiclos());
                $this->user->addOferta($oferta);
                header('location: ?page=oferta');
                
            }
            
        }
        echo $this->templates->render('Ofertas/FormOferta',['empresa'=>$this->user, 'validator'=>$validator, 'oferta'=>$oferta, 'user'=>$this->user,'accion'=>$accion]);
    }

    /**
     * Edita una oferta existente de un usuario.
     *
     * Obtiene y sanitiza los datos del formulario ($_POST), valida los campos,
     * localiza la oferta por ID, actualiza sus datos y finalmente renderiza
     * la vista del formulario con la oferta actualizada y las validaciones.
     *
     * @param int $id  ID de la oferta que se desea editar.
     *
     * @return void
     */
    private function editarOffer(int $id, string $accion){
        $postData=filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $validator=new Validator();
        $array=$this->user->getOfertas();
        $oferta=$this->foundOffer($id,$array);
        if (isset($postData['accion'])&&$postData['accion']=='editar'){
            OfertaValidator::validarOferta($validator,$postData);
            $oferta->actualizarOferta($postData);
            if($validator->validacionPasada()){
                $repo=new RepoOferta();
                $repo->update($oferta);
                $repo = new RepoCicloOferta();
                $repo->updateRelacion($oferta->getId(),$oferta->getCiclos());
                header('location: ?page=oferta');
                
            }
        }
        
        echo $this->templates->render('Ofertas/FormOferta',['empresa'=>$this->user, 'validator'=>$validator, 'oferta'=>$oferta, 'user'=>$this->user, 'accion'=>$accion]);
    }

    /**
     * Elimina una oferta de un usuario.
     *
     * Este método busca la oferta por ID, valida si se ha enviado la acción
     * de eliminación desde el formulario ($_POST), elimina la oferta de la base
     * de datos y de la colección del usuario, y finalmente redirige o renderiza
     * la vista de confirmación de eliminación.
     *
     * @param int    $id     ID de la oferta a eliminar.
     * @param string $accion Acción solicitada (normalmente 'eliminar').
     *
     * @return void
     */
    private function eliminarOffer(int $id, string $accion){
        $validator=new Validator();
        $array=$this->user->getOfertas();
        $oferta=$this->foundOffer($id,$array);
        $postData=filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        if (isset($postData['accion'])&& $postData['accion']=='eliminar'){
            $repo=new RepoCicloOferta();
            $repo->delete($oferta->getId());
            $repo=new RepoOferta();
            $repo->delete($oferta->getId());
            $this->user->deleteOferta($oferta);
            header('location: ?page=oferta');
            
        }
        echo $this->templates->render('Ofertas/EliminarOferta',['empresa'=>$this->user, 'validator'=>$validator, 'oferta'=>$oferta, 'user'=>$this->user, 'accion'=>$accion]);
    }


    /**
     * Filtra las ofertas de un usuario según los parámetros recibidos por GET.
     *
     * Dependiendo de si se recibe la familia y/o el estado, se consultan las
     * ofertas en la base de datos mediante el repositorio correspondiente y
     * se actualiza la colección de ofertas del usuario.
     *
     * Finalmente, renderiza la vista con las ofertas filtradas.
     *
     * @param array $getData Array con los parámetros de filtro (por ejemplo, 'familia' y 'estado').
     *
     * @return void
     */
    private function manejarFiltro(array $getData){
        $repo =new RepoOferta();
        if (isset($getData['familia'])&&isset($getData['estado'])){
            $this->user->setOfertas($repo->findByFamiliaAndEstado((int)$this->user->getId(),(int)$getData['familia'],$getData['estado']));
        }else if(isset($getData['familia'])){
            $this->user->setOfertas($repo->findByFamiliaAndEstado((int)$this->user->getId(),(int)$getData['familia'],''));
        }else if(isset($getData['estado'])){
            $this->user->setOfertas($repo->findByEstado((int)$this->user->getId(),$getData['estado']));
        }else{
            $this->user->setOfertas($repo->findAllByEmpresa((int)$this->user->getId()));
        }
        echo $this->templates->render('Ofertas/Ofertas', ['user'=>$this->user]);
    }


        /**
     * Filtra las ofertas de un usuario según los parámetros recibidos por GET.
     *
     * Dependiendo de si se recibe la familia y/o el estado, se consultan las
     * ofertas en la base de datos mediante el repositorio correspondiente y
     * se actualiza la colección de ofertas del usuario.
     *
     * Finalmente, renderiza la vista con las ofertas filtradas.
     *
     * @param array $getData Array con los parámetros de filtro (por ejemplo, 'familia' y 'estado').
     *
     * @return void
     */
    private function filtroCiclos(array $getData, array $ofertas){
         $repo =new RepoOferta();
         $ofertas=[];
        if (isset($getData['familia'])&&isset($getData['ciclo'])){
            $ciclos[]=new Ciclo(id:$getData['ciclo']);
            $ofertas=$repo->findByCiclos($ciclos);
        }else if(isset($getData['familia'])){
            $idFamilia=$getData['familia'];
            $ofertas=$repo->findByFamilia($idFamilia);
        }else{
            $ofertas=$repo->findByCiclos($this->user->getCiclos());
        } 
        echo $this->templates->render('Ofertas/Ofertas', ['user'=>$this->user,'ofertas'=>$ofertas]);
    }


    /**
     * Maneja la postulación de un alumno a una oferta.
     * Crea una nueva solicitud, la guarda en la base de datos y la añade al objeto alumno.
     *
     * @param array $getData Datos GET que contienen el ID de la oferta.
     * @return void
     */
    private function manejarPostular($getData){
        $oferta_id=(int)$getData['id'];
        $user_id=$this->user->getId();
        $repo=new RepoSolicitud();
        $solicitud=new Solicitud(alumnoId:$user_id,ofertaId:$oferta_id);
        $solicitud->setId($repo->save($solicitud));
        $this->user->addSolicitud($solicitud);
        header('location: ?page=oferta');
        
    }

    /**
     * Maneja la renuncia de un alumno a una oferta.
     * Elimina la solicitud de la base de datos y del array de solicitudes del alumno.
     *
     * @param array $getData Datos GET que contienen el ID de la oferta.
     * @return void
     */
    private function manejarRenunciar($getData){
        $oferta_id=(int)$getData['id'];
        $user_id=$this->user->getId();
        $repo=new RepoSolicitud();
        $solicitud=$this->user->getSolicitudByOfertaId($oferta_id);
        $repo->delete($solicitud->getId());
        $this->user->deleteSolicitud($solicitud);
        header('location: ?page=oferta');
        
    }

    /**
     * Busca una oferta dentro de un array por su ID y devuelve el objeto encontrado.
     *
     * Utiliza array_filter() para localizar el elemento y reset() para obtener
     * directamente el objeto, ya que el filtro devuelve un array asociativo.
     *
     * Se asume que el ID es único y siempre existe una coincidencia.
     *
     * @param int   $id    ID de la oferta a buscar.
     * @param array $array Array de objetos Oferta donde se realizará la búsqueda.
     *
     * @return Oferta  El objeto Oferta correspondiente al ID dado.
     */
    private function foundOffer(int $id,array $array):Oferta{
        $oferta=array_filter($array,fn($oferta)=>$oferta->getId()===$id);
        return reset($oferta); /* El reset mueve el puntero, para que sea un objeto en vez de un array con una sola posición */
    }
}
?>