<?php
namespace api;
require __DIR__ . '/../bootstrap.php'; 

// Carga las clases
require PROJECT_ROOT . 'vendor/autoload.php';

use app\repositories\RepoAlumno;
use app\repositories\RepoAlumCiclo;
use app\repositories\RepoFamilia;
use app\repositories\RepoUser;
use app\repositories\RepoCiclo;
use app\repositories\RepoSolicitud;
use app\repositories\RepoOferta;
use app\repositories\RepoToken;
use app\helpers\Converter;
use app\helpers\Validator;
use app\helpers\Security;
use app\models\Alumno;
use app\models\AlumCursadoCiclo;
use DateTime;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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
    exit;
}

// Token válido, continuar con el router
router();

function router(){
    $req=$_SERVER['REQUEST_METHOD'];

    switch($req){
        case "GET": /* Obtener */
            manejarGet();
            break;
        case "POST": /* Añadir */
            manejarPost();
            break;
        case "PUT": /* Actualizar */
            manejarPut();
            break;
        case "DELETE": /* Eliminar */
            manejarDelete();
            break;
    }
}


function manejarGet(){
    $accion=$_GET['menu'];
    
    switch($accion){
        case "listadoAlumnos":
            responseAlumnos();
            break;
        case "prueba":
            $repo=new RepoAlumno();
            $alumno=new Alumno(
                nombre:"Juan Pedro",
                ap1:"Exposito",
                ap2:"Pozuelo", 
                correo:"jexppoz579@g.educaand.es",
                direccion:"Calle True 123, Andújar",
                fechaNacimiento:new \DateTime("2003-03-26"));
            $repo->save($alumno);
            break;
        case "alumno":
            responseAlumno();
            break;
        case "ciclos":
            responseCiclos();
            break;
        case "familias":
            responseFamilias();
            break;
        case "niveles":
            responseNiveles();
            break;
        case "solicitudes":
            responseSolicitudes();
            break;
        case 'ofertas':
            responseOfertas();
            break;
        case 'solicitudesOferta':
            responseSolicitudesOferta();
            break;
    }
}

function responseAlumnos(){
    $repo=new RepoAlumno();
    $alumnos=$repo->findAll();
    $json=Converter::arrayToJson($alumnos);
    echo json_encode($json);
}

function responseAlumno(){
    $id=(int)$_GET['id'];
    $repo=new RepoAlumno();
    $alumno=$repo->findById($id);
    echo json_encode($alumno->toJson(), JSON_UNESCAPED_UNICODE);
}

function responseFamilias(){
    $repo=new RepoFamilia();
    $familias=$repo->findAll();
    $json=Converter::arrayToJson($familias);
    echo json_encode($json);
}

function responseNiveles(){
    $repo=new RepoCiclo();
    $id=$_GET['id']??false;
    $niveles=$repo->findNivelByFamily($id);
    $json=Converter::arrayToJson($niveles);
    echo json_encode($json);
}

function responseCiclos(){  
    $id=$_GET['id']??false;
    $nivel=$_GET['nivel']??false;
    $json;
    if ($id||$nivel){
        $repo=new RepoCiclo();
        $familias=$repo->findByNivelFamily($id,$nivel);
        $json=Converter::arrayToJson($familias);
        
    }else{
        $json = ['response'=>false];
    }
    echo json_encode($json);
}

/**
 * responseSolicitudes
 * 
 * Obtiene todas las ofertas solicitadas por un alumno específico
 * Requiere el parámetro 'id' en la URL con el ID del alumno
 * 
 * Devuelve un array de objetos con:
 * - Información completa de la oferta
 * - Estado de la solicitud
 * - ID de la solicitud
 * 
 * Ejemplo de uso:
 * GET /api/apiAlumno.php?menu=solicitudes&id=5
 * 
 * @return void Imprime un JSON con el array de ofertas y sus estados
 */
function responseSolicitudes(){
    $alumnoId = (int)($_GET['id'] ?? 0);
    
    if ($alumnoId === 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'ID de alumno no proporcionado o inválido'
        ]);
        return;
    }
    
    try {
        $repoSolicitud = new RepoSolicitud();
        $repoOferta = new RepoOferta();
        
        // Obtener todas las solicitudes del alumno
        $solicitudes = $repoSolicitud->findByAlumno($alumnoId);
        
        // Construir array con ofertas completas y estado de solicitud
        $resultado = [];
        foreach ($solicitudes as $solicitud) {
            $oferta = $repoOferta->findById($solicitud->getOfertaId());
            if ($oferta) {
                $resultado[] = [
                    'solicitud_id' => $solicitud->getId(),
                    'estado' => $solicitud->getEstado()->value,
                    'oferta' => $oferta->toJson()
                ];
            }
        }
        
        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error al obtener solicitudes: ' . $e->getMessage()
        ]);
    }
}

/**
 * responseOfertas
 * 
 * Obtiene todas las ofertas publicadas por una empresa específica
 * Requiere el parámetro 'id' en la URL con el ID de la empresa
 * 
 * Devuelve un array de ofertas de la empresa
 * 
 * Ejemplo de uso:
 * GET /api/apiAlumno.php?menu=ofertas&id=5
 * 
 * @return void Imprime un JSON con el array de ofertas
 */
function responseOfertas(){
    $empresaId = (int)($_GET['id'] ?? 0);
    
    if ($empresaId === 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'ID de empresa no proporcionado o inválido'
        ]);
        return;
    }
    
    try {
        $repoOferta = new RepoOferta();
        
        // Obtener todas las ofertas de la empresa
        $ofertas = $repoOferta->findAllByEmpresa($empresaId);
        
        // Convertir a JSON
        $json = Converter::arrayToJson($ofertas);
        echo json_encode($json);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error al obtener ofertas: ' . $e->getMessage()
        ]);
    }
}

/**
 * responseSolicitudesOferta
 *
 * Obtiene todos los alumnos que han solicitado una oferta específica.
 * Devuelve un JSON con éxito o error.
 *
 * Espera el parámetro GET:
 *  - ofertaId: ID de la oferta para filtrar los alumnos
 *
 * JSON de respuesta (success = true):
 * {
 *   "success": true,
 *   "alumnos": [
 *       {
 *           "id": 3,
 *           "nombre": "Laura",
 *           "ap1": "Martínez",
 *           "ap2": "Díaz",
 *           "correo": "laura@mail.com",
 *           "direccion": "Calle Sol 22",
 *           "foto": "laura.jpg",
 *           "ciclos": [...],
 *           "solicitudes": [...],
 *           "cv": "",
 *           "descripcion": "",
 *           "fechaNacimiento": "2004-02-11"
 *       },
 *       ...
 *   ]
 * }
 *
 * JSON de error (success = false):
 * {
 *   "success": false,
 *   "message": "Falta parámetro ofertaId"
 * }
 *
 * @return void Imprime directamente JSON
 */
function responseSolicitudesOferta(){

    // Validar parámetro ofertaId
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta parámetro ofertaId"
        ]);
        return;
    }

    $ofertaId = intval($_GET['id']);

    // Llamamos al repositorio de alumnos
    $repoAlumno = new RepoAlumno();
    $alumnos = Converter::arrayToJson($repoAlumno->findAllByOferta($ofertaId));
    
    // Respuesta
    echo json_encode([
        "success" => true,
        "alumnos" => $alumnos
    ]);
}

/**
 * manejarPost
 *
 *
 *  Este método se va a encargar lo que llegue al servidor mediante POST.
 *  En este caso, al llamarlo desde POST insertara uno o varios alumnos(mock) en la base de datos
 *  si se quiere insertar mediante un mockeo, se tiene que enviar en body en formato JSON
 *  además hay que agregar en el header: Mock:true
 *  Ejemplo desde JavaScript:
 *  fetch('/api/apiAlumno.php', {
 *  method: 'POST', // o PUT
 *  headers: { 
 *        'Content-Type': 'application/json',
 *        'MOCK': 'true' 
 *    },
 *    body: JSON.stringify(alumnos) 
 * })
 *  
 *  Para el caso de los JSON, tiene que seguir este formato:
 *  * Ejemplo JSON:
 * {
    *   "nombre": "María",
    *   "ap1": "Lopez",
    *   "ap2": "Lopez",
    *   "correo": "maria.lopez@example.com",
    *   "rol": 1,
    *   "direccion": "Calle Sol 22",
    *   "familia":"familia";
    *   "ciclo":"ciclo";
    *   
    *   "fechaNacimiento": "2000-05-20"
 *  }
 *
 *
 *
 */
function manejarPost(){
    $validator = new Validator();
    $mock = $_SERVER['HTTP_MOCK'] ?? false;
    
    if ($mock){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $familia = $data['familia'];
        $ciclo = $data['ciclo'];
        $alumnosArray = $data['alumnos'];
        $hoy = new DateTime();
        
        /* Añadir el validator y pasarle los strings, para la fecha hacer uso de:
            $fecha = DateTime::createFromFormat('d/m/Y', fecha);
         */
        $alumnos = array_map(
            fn($array) => new Alumno(
                nombre: $array['nombre'],
                ap1: $array['ap1'],
                ap2: $array['ap2'] ?? '',
                correo: $array['correo'], 
                fechaNacimiento: new DateTime($array['fechaNacimiento']),
                direccion: $array['direccion'],
                rol: 3,
                passwd: $array['nombre'] . '@' . $hoy->format('i') . $hoy->format('m')
            ),
            $alumnosArray // ← CORREGIDO: pasar el array correcto
        );
        
        $correos = array_map(fn($alumno) => $alumno->getCorreo(), $alumnos);
        $repoUser = new RepoUser();
        $correos_existentes = $repoUser->existenCorreos($correos); 
        
        $alumnosNoExisten = array_filter(
            $alumnos, 
            fn($alumno) => !in_array($alumno->getCorreo(), $correos_existentes)
        );
        
        $repoAlumno = new RepoAlumno(); 
        $repoCiclo = new RepoCiclo();
        $repoAlumCiclo = new RepoAlumCiclo();
        $cicloObj = $repoCiclo->findById($ciclo); 
        $estudio = new AlumCursadoCiclo();
        
        $estudio->setCicloId($cicloObj->getId());
        $estudio->setFechaInicio($hoy);
        
        foreach($alumnosNoExisten as $alumno){
            $id = $repoAlumno->save($alumno); 
            $estudio->setAlumnoId($id);
            $repoAlumCiclo->save($estudio);
        }
        
        echo json_encode($correos_existentes);
    } else {
        insertarAlumno();
    }
}


function insertarAlumno(){
    try {
        $nombre = $_POST['nombre'] ?? '';
        $ap1 = $_POST['ap1'] ?? '';
        $ap2 = $_POST['ap2'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $passwd = $_POST['passwd'] ?? '';
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';

        // Validar campos requeridos
        if (empty($nombre) || empty($ap1) || empty($correo) || empty($direccion) || empty($passwd) || empty($fechaNacimiento)) {
            echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
            return;
        }

        // Verificar si el correo ya existe
        $repoUser = new RepoUser();
        $correoExiste = $repoUser->existenCorreos([$correo]);

        if (!empty($correoExiste)) {
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
            return;
        }

        // Crear el objeto Alumno
        $alumno = new Alumno(
            nombre: $nombre,
            ap1: $ap1,
            ap2: $ap2,
            correo: $correo,
            fechaNacimiento: new DateTime($fechaNacimiento),
            direccion: $direccion,
            rol: 3,
            passwd: password_hash($passwd, PASSWORD_DEFAULT)
        );

        // Guardar el alumno
        $repoAlumno = new RepoAlumno();
        $alumnoId = $repoAlumno->save($alumno);

        if (!$alumnoId) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el alumno']);
            return;
        }

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => 'Alumno registrado correctamente',
            'alumno_id' => $alumnoId
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error del servidor: ' . $e->getMessage()
        ]);
    }
}

/**
 * manejarPut
 * 
 * 
 * 
 * Este método se va a encargar de lo que le llegue mediante el método PUT.
 * En este caso lo que hará será actualizar el usuario con sus propiedades 
 * enviados por json en el body de la request
 * IMPORTANTE:
 * El orden del JSON tiene que ser tal y como pone en el ejemplo
 * Ejemplo JSON:
 * {
    *   "id": 3,
    *   "nombre": "María López",
    *   "correo": "maria.lopez@example.com",
    *   "rol": 1,
    *   "direccion": "Calle Sol 22",
    *   "foto": "maria.jpg",
    *   "ap1": "López",
    *   "ap2": "García",
    *   "ciclos": [
    *   {
    *   "ciclo": "2023",
    *   "inicio": "2023-01-10",
    *   "fin": "2023-06-20"
    *   },
    *   {
    *   "ciclo": "2024",
    *   "inicio": "2024-01-15",
    *   "fin": "2024-06-25"
    *   }
    *   ],
    *   "cv": "cv_maria.pdf",
    *   "fechaNacimiento": "2000-02-20"
 *  }
 *
 * @return void
 */
function manejarPut(){
    
    $data=json_decode(file_get_contents('php://input'),true); /* Para convertirlo a array, sino seria stdClass */
    list($nombre,$correo,$direccion,$fecha)=$data;
    

}
/**
 * manejarDelete
 *  Elimina un registro según el header ACCION
 * @return void
 */
function manejarDelete() {
    $data = json_decode(file_get_contents('php://input'), true);
    $accion = $_SERVER['HTTP_ACCION'] ?? '';

    switch ($accion) {
        case 'solicitud':
            manejarDeleteSolicitud($data);
            break;
        case 'alumno':
            manejarDeleteAlumno($data);
            break;
        default:
            respuestaAccionInvalida();
            break;
    }
}

/**
 * Maneja eliminación de solicitud
 */
function manejarDeleteSolicitud(array $data) {
    $repo = new RepoSolicitud();
    $bool = $repo->delete($data["id"]);
    echo json_encode(["success" => $bool]);
}

/**
 * Maneja eliminación de alumno
 */
function manejarDeleteAlumno(array $data) {
    $repo = new RepoAlumno();
    $bool = $repo->delete($data["id"]);
    echo json_encode(["success" => $bool]);
}

/**
 * Respuesta para acción inválida
 */
function respuestaAccionInvalida() {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida'
    ]);
}

?>