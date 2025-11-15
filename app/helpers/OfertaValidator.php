<?php
namespace app\helpers;
use app\repositories\RepoUser; 
use DateTime;
class OfertaValidator {

     /**
     * Valida los campos de una empresa.
     * 
     * @param Validator $validator Instancia del validador
     * @param array $data Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    public static function validarOferta(Validator $validator, array $data): void {
        $validator->validarNombre('nombre', $data);
        $validator->required('descripcion', $data);
        $validator->validarFecha('fecha_inicio',$data);
        $validator->validarFecha('fecha_fin',$data);
        $fecha_inicio=new DateTime($data['fecha_inicio']);
        $fecha_fin=new DateTime($data['fecha_fin']);
        if ($fecha_inicio>=$fecha_fin){
            $validator->insertarError('fecha_fin',"La fecha fin no puede ser anterior a la fecha inicio");
        }
        if (!isset($data['ciclos']) ){
            $validator->insertarError('ciclos',"Debe introducir un ciclo al menos");
        }
    }
    
}

?>