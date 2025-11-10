<?php
require __DIR__ . '/../bootstrap.php'; 

// Carga las clases
require PROJECT_ROOT . 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;


$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // necesario si usas imágenes remotas
$options->setChroot(PROJECT_ROOT.'/public');
$dompdf = new Dompdf($options);
// instantiate and use the dompdf class
$logo='/assets/img/logo.png';
$dompdf->loadHtml(PROJECT_ROOT.$logo.' <img src="'.$logo.'">');


// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('ejemplo_dompdf.pdf', ['Attachment'=>false]); /* Attachment por defecto es true, si quiero mostrarlo en web es false */
?>