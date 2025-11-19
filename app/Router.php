<?php
namespace app;
use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\UserController;
use app\controllers\ContactoController;
use app\controllers\SolicitudController;
use app\controllers\NotificacionController;
use app\controllers\OfertaController;
use app\controllers\EmpresaController;
use app\controllers\StatController;
use app\controllers\PerfilController;
use app\helpers\Session;
class Router{
    public function __construct(){
        Session::openSession();
    }

    public function route(){
        $platePath=PROJECT_ROOT . 'app/views/plates';
        $url=$_GET['page']??'/';
        switch ($url) {
            case '/':
            case 'home':
                $controller=new HomeController($platePath);
                $controller->index();
                break;
            case 'login':
                $controller=new LoginController($platePath);
                $controller->index();
                break;  
            case 'contacto':
                $controller=new ContactoController($platePath);
                $controller->index();
                break; 
            case 'solicitud':
                $controller=new SolicitudController($platePath);
                $controller->index();
                break;
            case 'oferta':
                $controller=new OfertaController($platePath);
                $controller->index();
                break;
            case 'users':
                $controller=new UserController($platePath);
                $controller->index();
                break; 
            case 'empresas':
                $controller=new EmpresaController($platePath);
                $controller->index();
                break;
            case 'stats':
                $controller=new StatController($platePath);
                $controller->index();
                break;
            case 'perfil':
                $controller=new PerfilController($platePath);
                $controller->index();
                break;
            case'logout':
                Session::closeSession();
                header('location: ?page=home');
                break;
            
            default:
                echo "404 Not Found";
                break;
        }
    }
}