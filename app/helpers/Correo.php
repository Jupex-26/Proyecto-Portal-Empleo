<?php
namespace app\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Servicio de envío de emails
 */
class Correo
{
    private $mail;
    private $emailRemitente;

    /**
     * Constructor - Configura PHPMailer
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        
        // Configuración del servidor SMTP
        $this->mail->isSMTP();
        $this->mail->Host = getenv('MAIL_HOST') ?: 'mailhog';
        $this->mail->Port = getenv('MAIL_PORT') ?: 1025;
        $this->mail->SMTPAuth = false;
        $this->mail->SMTPDebug = 0;
        
        $this->emailRemitente = 'noreply@emplenow.com';
    }

    /**
     * Envía email cuando una empresa es activada o desactivada
     * 
     * @param object $empresa Objeto empresa con getCorreo() y getNombre()
     * @param bool $activa True si está activa, false si está desactivada
     * @return bool True si se envió correctamente
     */
    public function emailEmpresaActiva($empresa, $activa = true)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->setFrom($this->emailRemitente);
            $this->mail->addAddress($empresa->getCorreo());

            $cuerpo = "Nos ponemos en contacto con ustedes para informarles que su empresa ha sido ";
            $cuerpo .= $activa ? "<strong>activada</strong>" : "<strong>desactivada</strong>";
            $cuerpo .= " en nuestro sistema.";

            $titulo = $activa ? "¡Tu empresa ha sido activada!" : "Tu empresa ha sido desactivada";
            
            $plantilla = $this->obtenerPlantillaEmail(
                $empresa->getNombre(),
                $titulo,
                $cuerpo,
                'Acceder al panel',
                'http://localhost:8080/public?page=login'
            );

            // Contenido del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Activaciones';
            $this->mail->Body = $plantilla;

            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error al enviar email de activación: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Envía email de bienvenida a usuario registrado
     * 
     * @param object $user Objeto usuario con propiedades: email, nombre, password
     * @return bool True si se envió correctamente
     */
    public function usuarioRegistrado($user, $password)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->setFrom($this->emailRemitente);
            $this->mail->addAddress($user->getCorreo(), $user->getNombre());
            
            $mensaje = "Tu cuenta ha sido creada exitosamente. A continuación encontrarás tus credenciales de acceso:<br><br>";
            $mensaje .= "<strong>Email:</strong> {$user->getCorreo()}<br>";
            $mensaje .= "<strong>Contraseña:</strong> {$password}<br><br>";
            $mensaje .= "Por seguridad, te recomendamos cambiar tu contraseña después del primer inicio de sesión.";
            
            $plantilla = $this->obtenerPlantillaEmail(
                $user->getNombre(),
                '¡Bienvenido a nuestra plataforma!',
                $mensaje,
                'Iniciar sesión',
                'http://localhost:8080/public?page=login'
            );
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Bienvenido - Tu cuenta ha sido creada';
            $this->mail->Body = $plantilla;
            $this->mail->AltBody = strip_tags($mensaje);
            
            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error al enviar email de registro: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Método genérico para enviar emails personalizados
     * 
     * @param string $destinatario Email del destinatario
     * @param string $nombreDestinatario Nombre del destinatario
     * @param string $asunto Asunto del email
     * @param string $titulo Título principal del email
     * @param string $mensaje Mensaje del email
     * @param string $botonTexto Texto del botón (opcional)
     * @param string $botonUrl URL del botón (opcional)
     * @return bool True si se envió correctamente
     */
    public function enviarEmail($destinatario, $nombreDestinatario, $asunto, $titulo, $mensaje, $botonTexto = null, $botonUrl = null)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->setFrom($this->emailRemitente);
            $this->mail->addAddress($destinatario, $nombreDestinatario);
            
            $plantilla = $this->obtenerPlantillaEmail(
                $nombreDestinatario,
                $titulo,
                $mensaje,
                $botonTexto ?? 'Ver más',
                $botonUrl ?? 'http://localhost:8080/public?page=login'
            );
            
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $plantilla;
            $this->mail->AltBody = strip_tags($mensaje);
            
            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error al enviar email: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Genera la plantilla HTML del correo
     * 
     * @param string $nombre Nombre del destinatario
     * @param string $titulo Título del email
     * @param string $mensaje Mensaje del email
     * @param string $botonTexto Texto del botón
     * @param string $botonUrl URL del botón
     * @return string HTML del email
     */
    private function obtenerPlantillaEmail($nombre, $titulo, $mensaje, $botonTexto, $botonUrl)
    {
        // Cargar la plantilla HTML y CSS usando PROJECT_ROOT
        $plantilla = file_get_contents(PROJECT_ROOT . 'public/assets/plates/email.html');
        $css = file_get_contents(PROJECT_ROOT . 'public/assets/css/email.css');
        
        // Insertar CSS en la plantilla
        $plantilla = str_replace('{CSS}', $css, $plantilla);
        
        // Reemplazar las variables
        $plantilla = str_replace('{NOMBRE}', htmlspecialchars($nombre), $plantilla);
        $plantilla = str_replace('{TITULO}', htmlspecialchars($titulo), $plantilla);
        $plantilla = str_replace('{MENSAJE}', $mensaje, $plantilla);
        $plantilla = str_replace('{BOTON_TEXTO}', htmlspecialchars($botonTexto), $plantilla);
        $plantilla = str_replace('{BOTON_URL}', htmlspecialchars($botonUrl), $plantilla);
        $plantilla = str_replace('{ANIO}', date('Y'), $plantilla);
        
        return $plantilla;
    }
}
?>