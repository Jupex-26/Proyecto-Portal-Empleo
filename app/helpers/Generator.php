<?php
namespace app\helpers;
use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\Parts\FilePart;
use GeminiAPI\Enums\MimeType;


/**
 * Generator
 */
class Generator{
    private Client $client;

    public function __construct(){
        $config = require PROJECT_ROOT . 'config/config.php';
        $this->client = new Client($config['token']);
    }

    /**
     * Se usa así:
     * $generator=new Generator();
     *  echo $generator->generateDescription('cvs/curriculum.pdf');    
     * Genera un resumen conciso y sin formato de un CV en PDF.
     * * @param string $pdfPath La ruta absoluta al archivo PDF del currículum.
     * @return string El resumen generado por Gemini.
     * @throws \RuntimeException Si el archivo no se puede leer.
     */
    public function generateDescription(string $pdfPath): string{
        // 1. Cargar el contenido del archivo
        $fullPath=  PROJECT_ROOT . $pdfPath;
        $pdfContent = file_get_contents($fullPath);
        
        if ($pdfContent === false) {
            throw new \RuntimeException("Error: No se pudo leer el archivo PDF en {$pdfPath}");
        }

        // 2. Definición de la Instrucción (Prompt)
        $promptText = "Por favor, lee el contenido de este PDF y hazme un resumen para ver una descripción del curriculum. Quiero que te centres en aspectos académicos, profesional y en el sobre mí. Además quiero que sea texto sin formato, solo me agregues br en cada frase y no quiero ninguna respuesta tuya, solo el resumen. Quiero que el resumen sea breve y muy conciso para Recursos Humanos, además quiero que el texto no supere los 500 carácteres";
        
        // 3. Crear las Partes del Contenido
        
        $pdfPart = new FilePart(
            mimeType: MimeType::FILE_PDF,
            data: base64_encode($pdfContent)
        );
        
        // Parte 2: La Instrucción de Texto
        $promptPart = new TextPart($promptText);

        // 4. Enviar la solicitud a Gemini
        // Nota: En esta sintaxis de generateContent, le pasamos las partes como argumentos variádicos.
        $response = $this->client
            ->generativeModel('gemini-2.5-flash')
            ->generateContent(
                $pdfPart, 
                $promptPart
            );

        // 5. Devolver el texto sin formato de la respuesta
        return $response->text();
    }
}


?>