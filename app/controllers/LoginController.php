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


class LoginController {
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath);
        $this->user=Session::readSession('user');  /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
    }
    public function index() {
        // Lógica para la acción de inicio de sesión
        // Por ejemplo, cargar una vista
        /* $usuario=RepoUsuario::findById(1);
        print_r($usuario); */
        
        if ($_POST){
            $correo=$_POST['correo_login'];
            $pass=$_POST['passwd_login'];
            $validator=new Validator();
            $validator->validarCorreo('correo_login', $_POST);
            $bool=$validator->ValidacionPasada();
            
            /* $pass_hash=Converter::passwdToHash($pass); */ // TO-DO
            if ($bool){
                echo $bool;
                $repo=new RepoUser();
                $user=$repo->findUser($correo,$pass);
                if ($user){
                    echo "españa va bien";
                    $user=$this->getUser($user);
                    Login::login($user);
                    header('location:?page=home');
                }
                else{
                    echo "españa no va bien";
                    echo $this->templates->render('login');
                }
                 
                /* Generar Token */
            }else{
                echo "not work".$correo;
                $validator->imprimirError('correo');
                echo $this->templates->render('login');
            }
            
        }else{
            echo $this->templates->render('login');
            
        }
    }

    private function getUser($user){
        switch($user->getRol()){
            case 1:
                return $user;
                break;
            case 2:
                $repo=new RepoEmpresa();
                break;
            case 3:
                $repo=new RepoAlumno();
                break;
            
            default: return null;
        }
        $user=$repo->findById($user->getId());
        return $user;

    }
}
?>