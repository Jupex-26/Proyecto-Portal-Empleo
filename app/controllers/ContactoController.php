<?php
namespace app\controllers;
use League\Plates\Engine;
use app\helpers\Validator;
use app\helpers\Correo;
use app\helpers\Session;
class ContactoController{
    private $templates;
    private $user;
    public function __construct($platePath){
        $this->templates=new Engine($platePath); /* Cuando se haga una instancia de este controlador, creo una propiedad con la instancia de engine para los plates*/
        $this->user=Session::readSession('user');
    }
    public function index(){
        $validator = new Validator();
        $postData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Usamos los métodos existentes en tu clase Validator
            $validator->validarNombre('nombre', $postData);
            $validator->validarNombre('apellido1', $postData);
            $validator->validarCorreo('correo', $postData);
            $validator->required('asunto', $postData);
            $validator->required('mensaje', $postData);

            if ($validator->validacionPasada()) {
                $correo = new Correo();
                $destinatario = 'emplenow@gmail.com';
                $asunto = 'Contacto desde la web: ' . $postData['asunto'];
                
                // Construimos el cuerpo del correo para que encaje en la plantilla email.html
                $nombreCompleto = htmlspecialchars($postData['nombre'] . ' ' . $postData['apellido1'] . ' ' . $postData['apellido2']);
                $correoRemitente = htmlspecialchars($postData['correo']);
                $mensajeUsuario = nl2br(htmlspecialchars($postData['mensaje']));

                $cuerpo = "Se ha recibido un nuevo mensaje desde el formulario de contacto con los siguientes datos:<br><br>"
                         . "Nombre: {$nombreCompleto}<br>"
                         . "Correo del remitente: {$correoRemitente}<br><br>"
                         . "Mensaje:<br>"
                         . $mensajeUsuario;

                
                $correo->enviarEmail(
                    destinatario: $destinatario,
                    nombreDestinatario: 'Admin EmpleNow', 
                    asunto: $asunto,
                    titulo: 'Nuevo Mensaje de Contacto', 
                    mensaje: $cuerpo
                );
                $validator->mensajeExito("¡Correo enviado! Gracias por contactarnos.");
                $postData = []; // Limpiamos los datos para que el formulario aparezca vacío
            }
        }
        
        echo $this->templates->render('contacto', ['user' => $this->user, 'validator' => $validator, 'datos' => $postData]);
        
    }
}
?>