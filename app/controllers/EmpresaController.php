<?php
namespace app\controllers;
use League\Plates\Engine;
use app\repositories\RepoEmpresa;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Paginator;
use app\helpers\Validator;
use app\models\User;

class EmpresaController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index(){
        
        if (Login::isLogin() && $this->user->getRol()==1){
            $accion=$_GET['accion']??'listado';
            switch ($accion){
                case 'listado':
                    $this->manejarListado($accion);
                    break;
                case 'solicitudes':
                    $this->manejarSolicitudes($accion);
                    break;
                case 'inscribir':
                    break;
                default: header('location:?page=empresas');
            }
        }else{
            header('location:?page=home');
        }
        /* 
        Acciones:
            Listado -> posibilidad de editar, eliminar, ver más detalles
            Inscribir Empresa
            Solicitudes de Empresas aprobar o no


         */

    }     

    
    /**
     * manejarSolicitudes
     *
     * @param  mixed $accion
     * @return void
     */
    private function manejarSolicitudes($accion){
        $repo=new RepoEmpresa();
        if (isset($_POST['accion'])){
            $this->chooseAction($repo);
            if ($_POST['accion']=='aceptar'){
                $this->activateEmpresa($repo);
            }
        }
        else{
            $this->paginacionListadoEmpresas(false,$repo,$accion); 
        }
    }

    
    /**
     * manejarListado
     *
     * @return void
     */
    private function manejarListado($accion){
        $repo=new RepoEmpresa();
        if (isset($_POST['accion'])){
            $this->chooseAction($repo);
            
        }
        else{
            $this->paginacionListadoEmpresas(true,$repo,$accion); 
        }
    }
    
    /**
     * activateEmpresa
     *  Este método activa una empresa el cual un admin haya aceptado
     * @param  mixed $repo
     * @return void
     */
    private function activateEmpresa($repo){
        
        $empresa=$repo->findById($_POST['id']);
        $empresa->setActivo(true);
        /* echo "<h1>".$empresa->getToken()."</h1>"; */
        $repo->update($empresa); 
        header('location?page=empresas&accion='.$_GET['accion']);
    }
    
    /**
     * chooseAction
     * Según la accion que indique mediante el POST, hará una opción entre las que vienen incluidas
     *
     * @return void
     */
    private function chooseAction($repo){
        $accion=$_POST['accion'];
            switch($accion){
                case 'editar': 
                    $this->editarEmpresa($repo);
                    break;
                case 'eliminar':
                    $this->eliminarEmpresa($repo);
                    break;
                case 'ver':
                    $this->verEmpresa($repo);
                break;
        }
    }

    

    
    /**
     * paginacionListadoEmpresas
     *
     * Este método obtiene los datos necesarios para la paginación de empresas
     * A su vez hace busca según los cálculos el array de empresas necesario y si están activas o no
     * @param  mixed $activo
     * @param  mixed $repo
     * @return void
     */
    private function paginacionListadoEmpresas(bool $activo, $repo, $accion){
        $empresas=$repo->findAll();
        $total=$repo->getCount();
        $page=$_GET['pagina']??1;
        $size=$_GET['size']??10;
        $index=Paginator::getIndex($size,$page);
        $pages=Paginator::getPages($total,$size); 
        $empresas=$repo->findAllLimitWActive($index,$size,$activo);
        if ($page>$pages) $page--;
        $paginator=Paginator::renderPagination($page,$size,$pages);
        print $this->templates->render('Admin/AdminListado',['empresas'=>$empresas,'paginator'=>$paginator, 'page'=>$_GET['page'], 'activo'=>$activo,'accion'=>$accion]); 
    }

        
    /**
     * editarEmpresa
     *
     * @param  mixed $repo
     * @return void
     */
    private function editarEmpresa($repo){
        $empresa=$repo->findById($_POST['id']);
        $validator=new Validator();
        if (isset($_POST['action'])){
            $action=$_POST['action'];
            switch ($action){
                case 'guardar':
                    $this->manejarGuardar($empresa,$validator, $repo);
                    break;
                case 'cancelar':
                    header("location:?page=empresas&accion=".$_GET['accion']);
                    break;
            }
        }
        echo $this->templates->render('Admin/AdminEditEmpresa',['empresa'=>$empresa, 'validator'=>$validator, 'page'=>$_GET['page'], 'accion'=>$_GET['accion']]);
    }
        
    /**
     * eliminarEmpresa
     *
     * @param  mixed $repo
     * @return void
     */
    private function eliminarEmpresa($repo){
        if (isset($_POST['action'])){
            $action=$_POST['action'];
            $id=$_POST['id'];
            switch ($action){
                case 'eliminar':
                    $repo->delete($id);
                case 'cancelar':
                    header("location:?page=empresas&accion=".$_GET['accion']);
                    break;
            }
        }else{
            $empresa=$repo->findById($_POST['id']);
            print $this->templates->render('Admin/AdminEliminarEmpresa',['empresa'=>$empresa,'page'=>$_GET['page'],'accion'=>$_GET['accion']]);
        }
        
    }    
    /**
     * verEmpresa
     *
     * @param  mixed $repo
     * @param  mixed $accion
     * @return void
     */
    private function verEmpresa($repo){
        if (isset($_POST['action'])&&$_POST['action']=='cancelar'){
            header("location:?page=empresas&accion=".$_GET['accion']);
        }else{
            $empresa=$repo->findById($_POST['id']);
            print $this->templates->render('Admin/AdminVerEmpresa',['empresa'=>$empresa,'page'=>$_GET['page'],'accion'=>$_GET['accion']]);
        }
        
    }


    private function manejarGuardar($empresa,$validator, $repo){
        $this->actualizarEmpresa($empresa);
        $this->validarEmpresa($validator);
        if ($validator->validacionPasada()){
            $repo->update($empresa);
        }
    }    
    /**
     * validarEmpresa
     * 
     * Valida la empresa según los campos que le han llegado por método POST
     *
     * @param  mixed $validator
     * @return void
     */
    private function validarEmpresa($validator) {
        $validator->validarEmail('correo',$_POST);
        $validator->validarEmail('correo_contacto',$_POST);
        $validator->validarTelefono('telefono_contacto',$_POST);
        $validator->validarNombre('nombre',$_POST);
        $validator->required('direccion', $_POST);
        if ($validator->validacionPasada()){
            $validator->mensajeExito();
        }
    }
    
    /**
     * actualizarEmpresa
     *  Actualiza la empresa con los campos que le han llegado por POST,
     *  Si no le llego nada por post, se deja el nombre que tenía
     * @param  mixed $empresa
     * @return void
     */
    private function actualizarEmpresa($empresa){
        $nombre=$_POST['nombre']??$empresa->getNombre();
        $correo=$_POST['correo']??$empresa->getEmail();
        $correo_contacto=$_POST['correo_contacto']??$empresa->getCorreoContacto();
        $telefono_contato=$_POST['telefono_contacto']??$empresa->getTelefonoContacto();
        $direccion=$_POST['direccion']??$empresa->getDireccion();
        $empresa->setNombre($nombre);
        $empresa->setEmail($correo);
        $empresa->setCorreoContacto($correo_contacto);
        $empresa->setTelefonoContacto($telefono_contato);
        $empresa->setDireccion($direccion);
    }
    

    
}
?>