<?php
namespace app\models;
enum EstadoSolicitud: string {
    case PROCESO = 'PROCESO';
    case ACEPTADO = 'ACEPTADO';
    case DENEGADO = 'DENEGADO';
    case INTERESADO = 'INTERESADO';
}
?>