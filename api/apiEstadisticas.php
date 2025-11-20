<?php
namespace api;
require __DIR__ . '/../bootstrap.php'; 

// Carga las clases
require PROJECT_ROOT . 'vendor/autoload.php';

use app\repositories\RepoAlumno;
use app\repositories\RepoAlumCiclo;
use app\repositories\RepoFamilia;
use app\repositories\RepoEmpresa;
use app\repositories\RepoCiclo;
use app\repositories\RepoSolicitud;
use app\repositories\RepoOferta;
use app\repositories\RepoToken;
use app\helpers\Converter;
use app\helpers\Validator;
use app\helpers\Security;
use app\helpers\Generator;
use app\models\Alumno;
use app\models\Ciclo;
use app\models\AlumCursadoCiclo;
use app\models\EstadoSolicitud;
use DateTime;
use app\helpers\Correo;



/* Comprobar si tiene token y si pertenece a administrador */

/**
 * Valida el token de la petición
 * @return object|false Retorna el objeto Token si es válido, false si no lo es
 */
function validarToken() {
    $mock = $_SERVER['HTTP_MOCK'] ?? false;
    
    // Si es POST sin MOCK (registro), no validar token
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$mock) {
        return true;
    }
    
    $auth = $_SERVER['HTTP_AUTH'] ?? '';

    if (empty($auth)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
        return false;
    }
    
    $tokenString = $auth;
    if (preg_match('/Bearer\s+(\S+)/', $auth, $matches)) {
        $tokenString = $matches[1];
    }

    $repoToken = new RepoToken();
    $tokenObj = $repoToken->findByCodigo($tokenString);
    
    if (!$tokenObj || !Security::validateToken($tokenString, $tokenObj->getCodigo())) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token inválido']);
        return false;
    }
    
    if (new DateTime() > $tokenObj->getExpiresAt()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token expirado']);
        return false;
    }
    
    return $tokenObj;
}

$tokenData = validarToken();

// Si el token no es válido, detener la ejecución
if ($tokenData === false) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

// Token válido, continuar con el router
router();

function router(){
    header('Content-Type: application/json');
    $reporte = $_GET['reporte'] ?? '';

    switch($reporte){
        case 'alumnosPorCiclo':
            getAlumnosPorCiclo();
            break;
        case 'empresasPorActividad':
            getEmpresasActividad();
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Reporte no especificado o inválido']);
            break;
    }
}

function getAlumnosPorCiclo(){
    $repo = new RepoAlumCiclo();
    $datos = $repo->contarAlumnosPorCiclo();
    echo json_encode($datos);
}

/**
 * Obtiene el número de empresas activas e inactivas.
 * Utiliza el método `contarPorActividad` en `RepoEmpresa`.
 */
function getEmpresasActividad(){
    $repo = new RepoEmpresa();
    $datos = $repo->contarPorActividad();
    echo json_encode($datos);
}


?>