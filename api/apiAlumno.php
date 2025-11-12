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
use app\helpers\Converter;
use app\helpers\Validator;
use app\models\Alumno;
use app\models\AlumCursadoCiclo;
use DateTime;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/* Comprobar si tiene token y si pertene a administrador */
$auth=$_SERVER['HTTP_AUTHORIZATION']??'';
router();
/* if ($auth!=''){
    
}else{
    
} */

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
            /* put y delete */
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
        case "familias":
            responseFamilias();
            break;
        case "ciclos":
            responseCiclos();
            break;
    }
}

function responseAlumnos(){
    $repo=new RepoAlumno();
    $alumnos=$repo->findAll();
    $json=Converter::arrayToJson($alumnos);
    echo $json;
}
function responseFamilias(){
    $repo=new RepoFamilia();
    $familias=$repo->findAll();
    $json=Converter::arrayToJson($familias);
    echo $json;
}
function responseCiclos(){
    error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    $id=$_GET['id']??false;
    $json;
    if ($id){
        $repo=new RepoCiclo();
        $familias=$repo->findByFamily($id);
        $json=Converter::arrayToJson($familias);
        
    }else{
        $json = ['response'=>false];
    }
    echo $json;
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
        
        $cicloObj = $repoCiclo->findByNameAndFamily($ciclo, $familia); 
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
        // Manejar caso sin MOCK
        http_response_code(400);
        echo json_encode(['error' => 'MOCK header required']);
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
 *  Devuelve un json con true o false si se ha eliminado el alumno o no
 * @return void
 */
function manejarDelete(){
    $data=json_decode(file_get_contents('php://input'),true);
    $repo=new RepoAlumno();
    $bool=$repo->delete($data["id"]);
    echo json_encode(["success"=>$bool]);
}
?>