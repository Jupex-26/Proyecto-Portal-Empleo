<?php
namespace app\helpers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Correo{
    public function emailEmpresaActiva(){
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP (MailHog)
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST') ?: 'mailhog';
            $mail->Port = getenv('MAIL_PORT') ?: 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPDebug = 0;
            // esto te va mostrando lo que hace, en 0 no lo muestra

            // Remitente y destinatario
            $mail->setFrom("gestor.email@portalzuelas.com");
            $mail->addAddress($empresa->getCorreo());

            $plantilla = file_get_contents('./assets/plates/plantillaEmailUser.html');
            $css = file_get_contents('./assets/css/estiloEmail.css');
            $plantilla = str_replace('<link rel="stylesheet" href="estilos.css" />', "<style>$css</style>", $plantilla);
            $plantilla = str_replace('{{nombre}}', $empresa->getNombre(), $plantilla);
            $cuerpo = "Nos ponemos en contacto con ustedes para informarles que su empresa ha sido ";
            $activa === true ? $cuerpo.="<strong>activada</strong>" : $cuerpo.="<strong>desactivada</strong>";
            $plantilla = str_replace('{{cuerpo}}', $cuerpo, $plantilla);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activaciones';
            $mail->Body    = $plantilla;

            $mail->send();
        } catch (Exception $e) {
        }
        
    }
}

?>