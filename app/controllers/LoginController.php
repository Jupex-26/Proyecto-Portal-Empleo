<?php
namespace app\controllers;
use app\views\formulario;
use app\repositories\RepoUser;
use League\Plates\Engine;
use app\helpers\Login;
use app\helpers\Validator;
use app\repositories\RepoAlumno;
use app\repositories\RepoEmpresa;
use app\helpers\Converter;
use app\helpers\Session;
use app\helpers\Security;
use app\helpers\EmpresaValidator;
use app\models\Empresa;


class LoginController {
    private $templates;
    private $user;
    private $page;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user'); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->page=$_GET['page'];
    }

    /**
     * Método principal del controlador de login/registro.
     * Determina qué acción realizar según el valor enviado por POST['accion'].
     */
    public function index() {
        $validator = new Validator();
        $repo = new RepoUser();
        $accionPost = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $accionGet=filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($accionPost)){
            $this->manejarLogin($validator, $repo);
        }else if ($accionGet=='registroEmpresa'){
            $this->manejarRegistro($validator, $accionGet);
        }else if($accionGet=='registrado'){
            echo $this->templates->render('Login/RegistroDone');
        }else{
            echo $this->templates->render('login', ['validator' => $validator]);
        }
        
        
    }


    /**
     * Maneja el proceso de login de un usuario.
     * Valida el correo, obtiene los datos del usuario y realiza el login.
     *
     * @param Validator $validator Instancia del validador de campos
     * @param RepoUser $repo Repositorio para buscar usuarios
     * @return void
     */
    private function manejarLogin($validator, $repo) {
        $correo = $_POST['correo_login'];
        $pass   = $_POST['passwd_login'];
        $validator->validarCorreo('correo_login', $_POST);
        $user = $repo->findUser($correo);
        if ($user  && Security::validatePasswd($pass, $user->getPassword() )) {
            $user = $this->getUser($user);
            if ($user){
                Login::login($user);
                header('location:?page=home');
                exit;
            }else{
                $validator->insertarError('correo_login',"Esta cuenta no está activada");
                echo $this->templates->render('login', ['validator' => $validator,'correo'=>$correo]);
            }
        } else {
            $validator->insertarError('correo_login', "Correo o contraseña incorrecta");
            echo $this->templates->render('login', ['validator' => $validator,'correo'=>$correo]);
        }
    }

    /**
     * Maneja la visualización del formulario de registro de una nueva empresa.
     * 
     * @param Validator $validator Instancia del validador para mostrar errores si existen
     * @param RepoUser $repo Repositorio de usuarios 
     * @return void
     */
    private function manejarRegistro($validator,$accion){
        $empresa=new Empresa();
        $repo=new RepoEmpresa();
        $postData=$_POST;
        if (isset($postData['action'])&&$postData['action']=='guardar'){
            EmpresaValidator::validarEmpresa($validator,$postData,$_FILES['foto']);
            $empresa->actualizarEmpresa( $postData, $_FILES['foto']);
            if ($empresa->getFoto()!=''){
                $validator->remove('foto');
            }
            if ($validator->validacionPasada()){
                $empresa->setPassword(Security::passwdToHash($empresa->getPassword()));
                $repo->save($empresa);
                header('location:?page=login&accion=registrado');
                exit;
            }
            
        }
        echo $this->templates->render('Login/RegisterEmpresa',['empresa'=>$empresa,'validator'=>$validator,'page'=>$this->page,'accion'=>$accion]);
    }

    /**
    * Obtiene un objeto de usuario completo según su rol.
    * 
    * Si el usuario es de tipo empresa o alumno, busca sus datos detallados
    * en el repositorio correspondiente. Si es un administrador, se devuelve tal cual.
    * 
    * @param object $user Instancia básica del usuario (con al menos ID y rol)
    * @return object|null Devuelve el usuario completo según su tipo, o null si el rol no es válido.
    */
    private function getUser($user) {
        // Determina el tipo de usuario según su rol numérico.
        switch($user->getRol()) {
            case 1:
                return $user;
                break;
            case 2:
                $repo = new RepoEmpresa();
                break;
            case 3:
                $repo = new RepoAlumno();
                break;
            default: 
                return null;
        }
        $user = $repo->findById($user->getId());
        if ($user instanceof Empresa && !$user->isActivo()){
            return null;
        }
        return $user;
    }
}
?>